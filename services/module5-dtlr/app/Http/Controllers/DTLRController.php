<?php

namespace App\Http\Controllers;

use App\Models\DtlrDocument;
use App\Models\DtlrDocumentType;
use App\Models\DtlrBranch;
use App\Models\DtlrDocumentLog;
use App\Models\DtlrDocumentReview;
use App\Models\DtlrUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DTLRController extends Controller
{
    // ==================== DOCUMENT MANAGEMENT METHODS ====================

    public function getDocuments(Request $request)
    {
        $query = DtlrDocument::with(['documentType', 'currentBranch', 'creator'])
            ->latest();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by document type
        if ($request->has('document_type') && $request->document_type != '') {
            $query->where('document_type_id', $request->document_type);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $documents = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $documents,
            'stats' => [
                'total' => DtlrDocument::count(),
                'pending' => DtlrDocument::where('status', 'pending')->count(),
                'processed' => DtlrDocument::where('status', 'processed')->count(),
                'approved' => DtlrDocument::where('status', 'approved')->count(),
            ]
        ]);
    }

    public function createDocument(Request $request)
    {
        $request->validate([
            'document_type_id' => 'required|exists:dtlr_document_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'current_branch_id' => 'required|exists:dtlr_branches,id',
            'created_by' => 'required|exists:dtlr_users,id'
        ]);

        try {
            // Generate tracking number
            $trackingNumber = 'DTLR-' . date('Y') . '-' . str_pad(DtlrDocument::count() + 1, 5, '0', STR_PAD_LEFT);

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $trackingNumber . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('documents', $fileName, 'public');
            }

            $document = DtlrDocument::create([
                'tracking_number' => $trackingNumber,
                'document_type_id' => $request->document_type_id,
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath,
                'current_branch_id' => $request->current_branch_id,
                'created_by' => $request->created_by,
                'status' => 'pending'
            ]);

            // Log the upload action
            DtlrDocumentLog::create([
                'document_id' => $document->id,
                'action' => 'accessed',
                'from_branch_id' => $request->current_branch_id,
                'performed_by' => $request->created_by,
                'notes' => 'Document uploaded and digitized',
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'data' => $document
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDocument($id)
    {
        $document = DtlrDocument::with(['documentType', 'currentBranch', 'creator', 'documentLogs.performer'])->find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $document
        ]);
    }

    public function updateDocument(Request $request, $id)
    {
        $document = DtlrDocument::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pending,processed,approved,archived,rejected',
            'updated_by' => 'required|exists:dtlr_users,id'
        ]);

        try {
            $document->update($request->only(['title', 'description', 'status']) + [
                'updated_by' => $request->updated_by
            ]);

            // Log status change if status was updated
            if ($request->has('status')) {
                DtlrDocumentLog::create([
                    'document_id' => $document->id,
                    'action' => 'status_changed',
                    'from_branch_id' => $document->current_branch_id,
                    'performed_by' => $request->updated_by,
                    'notes' => 'Document status changed to: ' . $request->status,
                    'ip_address' => $request->ip()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Document updated successfully',
                'data' => $document
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteDocument($id)
    {
        $document = DtlrDocument::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        try {
            // Delete associated file
            Storage::disk('public')->delete($document->file_path);
            
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function transferDocument(Request $request, $id)
    {
        $document = DtlrDocument::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        $request->validate([
            'to_branch_id' => 'required|exists:dtlr_branches,id',
            'performed_by' => 'required|exists:dtlr_users,id',
            'notes' => 'nullable|string'
        ]);

        try {
            $oldBranchId = $document->current_branch_id;
            
            $document->update([
                'current_branch_id' => $request->to_branch_id,
                'updated_by' => $request->performed_by
            ]);

            // Log the transfer action
            DtlrDocumentLog::create([
                'document_id' => $document->id,
                'action' => 'transferred',
                'from_branch_id' => $oldBranchId,
                'to_branch_id' => $request->to_branch_id,
                'performed_by' => $request->performed_by,
                'notes' => $request->notes ?? 'Document transferred between branches',
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document transferred successfully',
                'data' => $document
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to transfer document: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== DOCUMENT LOGS METHODS ====================

    public function getDocumentLogs(Request $request)
    {
        $query = DtlrDocumentLog::with(['document', 'fromBranch', 'toBranch', 'performer'])
            ->latest();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('document', function($q2) use ($search) {
                    $q2->where('tracking_number', 'like', "%{$search}%")
                       ->orWhere('title', 'like', "%{$search}%");
                })
                ->orWhere('notes', 'like', "%{$search}%")
                ->orWhere('action', 'like', "%{$search}%");
            });
        }

        // Filter by action
        if ($request->has('action') && $request->action != '') {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $logs,
            'stats' => [
                'total' => DtlrDocumentLog::count(),
                'accessed' => DtlrDocumentLog::where('action', 'accessed')->count(),
                'printed' => DtlrDocumentLog::where('action', 'printed')->count(),
                'transferred' => DtlrDocumentLog::where('action', 'transferred')->count(),
                'reviewed' => DtlrDocumentLog::where('action', 'reviewed')->count(),
            ]
        ]);
    }

    public function getDocumentLog($id)
    {
        $log = DtlrDocumentLog::with(['document', 'fromBranch', 'toBranch', 'performer'])->find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Log not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $log
        ]);
    }

    // ==================== DOCUMENT REVIEWS METHODS ====================

    public function getDocumentReviews(Request $request)
    {
        $query = DtlrDocumentReview::with(['document', 'reviewer'])
            ->latest();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('document', function($q2) use ($search) {
                    $q2->where('tracking_number', 'like', "%{$search}%")
                       ->orWhere('title', 'like', "%{$search}%");
                })
                ->orWhere('comments', 'like', "%{$search}%");
            });
        }

        // Filter by review status
        if ($request->has('review_status') && $request->review_status != '') {
            $query->where('review_status', $request->review_status);
        }

        $reviews = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $reviews,
            'stats' => [
                'total' => DtlrDocumentReview::count(),
                'pending' => DtlrDocumentReview::where('review_status', 'pending')->count(),
                'approved' => DtlrDocumentReview::where('review_status', 'approved')->count(),
                'rejected' => DtlrDocumentReview::where('review_status', 'rejected')->count(),
            ]
        ]);
    }

    public function createDocumentReview(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:dtlr_documents,id',
            'reviewer_id' => 'required|exists:dtlr_users,id',
            'review_status' => 'required|in:pending,approved,rejected',
            'comments' => 'nullable|string'
        ]);

        try {
            $review = DtlrDocumentReview::create([
                'document_id' => $request->document_id,
                'reviewer_id' => $request->reviewer_id,
                'review_status' => $request->review_status,
                'comments' => $request->comments,
                'reviewed_at' => $request->review_status !== 'pending' ? now() : null
            ]);

            // Update document status based on review
            $document = DtlrDocument::find($request->document_id);
            if ($document && $request->review_status !== 'pending') {
                $document->update([
                    'status' => $request->review_status === 'approved' ? 'approved' : 'rejected',
                    'updated_by' => $request->reviewer_id
                ]);

                // Log the review action
                DtlrDocumentLog::create([
                    'document_id' => $document->id,
                    'action' => 'reviewed',
                    'from_branch_id' => $document->current_branch_id,
                    'performed_by' => $request->reviewer_id,
                    'notes' => 'Document reviewed: ' . $request->review_status . '. Comments: ' . ($request->comments ?? 'None'),
                    'ip_address' => $request->ip()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully',
                'data' => $review
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDocumentReview(Request $request, $id)
    {
        $review = DtlrDocumentReview::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        $request->validate([
            'review_status' => 'required|in:pending,approved,rejected',
            'comments' => 'nullable|string'
        ]);

        try {
            $review->update([
                'review_status' => $request->review_status,
                'comments' => $request->comments,
                'reviewed_at' => $request->review_status !== 'pending' ? now() : null
            ]);

            // Update document status based on review
            $document = DtlrDocument::find($review->document_id);
            if ($document && $request->review_status !== 'pending') {
                $document->update([
                    'status' => $request->review_status === 'approved' ? 'approved' : 'rejected',
                    'updated_by' => $review->reviewer_id
                ]);

                // Log the review update action
                DtlrDocumentLog::create([
                    'document_id' => $document->id,
                    'action' => 'reviewed',
                    'from_branch_id' => $document->current_branch_id,
                    'performed_by' => $review->reviewer_id,
                    'notes' => 'Review updated: ' . $request->review_status . '. Comments: ' . ($request->comments ?? 'None'),
                    'ip_address' => $request->ip()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== UTILITY METHODS ====================

    public function getDocumentTypes()
    {
        $documentTypes = DtlrDocumentType::all();

        return response()->json([
            'success' => true,
            'data' => $documentTypes
        ]);
    }

    public function getBranches()
    {
        $branches = DtlrBranch::all();

        return response()->json([
            'success' => true,
            'data' => $branches
        ]);
    }

    public function getOverviewStats()
    {
        $stats = [
            'total_documents' => DtlrDocument::count(),
            'pending_documents' => DtlrDocument::where('status', 'pending')->count(),
            'processed_documents' => DtlrDocument::where('status', 'processed')->count(),
            'approved_documents' => DtlrDocument::where('status', 'approved')->count(),
            'total_logs' => DtlrDocumentLog::count(),
            'total_reviews' => DtlrDocumentReview::count(),
            'today_uploads' => DtlrDocument::whereDate('created_at', today())->count(),
            'ocr_processed' => DtlrDocument::whereNotNull('ocr_processed_at')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    // ==================== OCR PROCESSING METHODS ====================

    public function processOCR($id)
    {
        $document = DtlrDocument::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        try {
            // Simulate OCR processing
            // In a real implementation, you would integrate with an OCR service like Tesseract, Google Vision, etc.
            $extractedData = [
                'pages' => 1,
                'text_length' => rand(100, 1000),
                'processed_at' => now()->toISOString(),
                'confidence_score' => rand(80, 95) / 100
            ];

            $document->update([
                'extracted_data' => $extractedData,
                'ocr_processed_at' => now(),
                'status' => 'processed'
            ]);

            // Log OCR processing
            DtlrDocumentLog::create([
                'document_id' => $document->id,
                'action' => 'processed',
                'from_branch_id' => $document->current_branch_id,
                'performed_by' => 1, // System user
                'notes' => 'Document processed with OCR',
                'ip_address' => request()->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OCR processing completed',
                'data' => [
                    'document' => $document,
                    'extracted_data' => $extractedData
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'OCR processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== SEARCH METHODS ====================

    public function searchDocuments(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $documents = DtlrDocument::with(['documentType', 'currentBranch'])
            ->where(function($q) use ($request) {
                $q->where('tracking_number', 'like', "%{$request->query}%")
                  ->orWhere('title', 'like', "%{$request->query}%")
                  ->orWhere('description', 'like', "%{$request->query}%");
            })
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $documents
        ]);
    }
}