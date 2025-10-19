<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\LogisticsRecord;
use App\Models\DocumentReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DTLRController extends Controller
{
    // ==================== DOCUMENT METHODS ====================
    
    public function getDocuments(Request $request)
    {
        try {
            $query = Document::query();
            
            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('document_id', 'like', "%{$search}%")
                      ->orWhere('linked_transaction', 'like', "%{$search}%")
                      ->orWhere('extracted_fields', 'like', "%{$search}%")
                      ->orWhere('uploaded_by', 'like', "%{$search}%");
                });
            }
            
            // Filter by document type
            if ($request->has('document_type') && $request->document_type) {
                $query->where('document_type', $request->document_type);
            }
            
            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }
            
            // Pagination
            $perPage = $request->get('limit', 10);
            $documents = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'documents' => $documents->items(),
                    'total' => $documents->total(),
                    'current_page' => $documents->currentPage(),
                    'last_page' => $documents->lastPage(),
                    'per_page' => $documents->perPage()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch documents: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getDocument($id)
    {
        try {
            $document = Document::with('review')->find($id);
            
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
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch document: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function createDocument(Request $request)
{
    try {
        \Log::info('DTLR createDocument called', [
            'has_file' => $request->hasFile('document_file'),
            'all_data' => $request->all(),
            'files' => $request->file() ? array_keys($request->file()) : 'no files',
            'input_data' => $request->except('document_file')
        ]);

        // Log file details if present
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            \Log::info('File details', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension()
            ]);
        }

        $validator = Validator::make($request->all(), [
            'document_type' => 'required|string|max:255',
            'linked_transaction' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'uploaded_by' => 'required|string|max:255',
            'uploaded_to' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240' // 10MB max
        ]);
        
        if ($validator->fails()) {
            \Log::error('Validation failed', ['errors' => $validator->errors()->toArray()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file upload
        $file = $request->file('document_file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $storedFileName = time() . '_' . Str::random(8) . '.' . $extension;
        $filePath = $file->storeAs('documents', $storedFileName, 'public');

        // Create a new document record
        $documentId = 'DOC-' . strtoupper(Str::random(8));
        $document = Document::create([
            'document_id' => $documentId,
            'document_type' => $request->document_type,
            'linked_transaction' => $request->linked_transaction,
            'description' => $request->description,
            'uploaded_by' => $request->uploaded_by,
            'uploaded_to' => $request->uploaded_to,
            'file_path' => $filePath,
            'file_name' => $originalName,
            'status' => 'pending',
            'extracted_fields' => null,
            'ocr_processed' => false,
            'ocr_processed_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Log the action
        $this->createLogisticsRecord(
            'created',
            'Document Tracking',
            "Created document {$documentId}",
            $request->uploaded_by ?? 'System',
            $documentId
        );

        return response()->json([
            'success' => true,
            'message' => 'Document created successfully',
            'data' => $document
        ], 201);
        
    } catch (\Exception $e) {
        \Log::error('Failed to create document: ' . $e->getMessage(), ['exception' => $e]);
        return response()->json([
            'success' => false,
            'message' => 'Failed to create document: ' . $e->getMessage()
        ], 500);
    }
    
    }
    
    public function updateDocument(Request $request, $id)
    {
        try {
            $document = Document::find($id);
            
            if (!$document) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found'
                ], 404);
            }
            
            $validator = Validator::make($request->all(), [
                'document_id' => 'sometimes|string|max:255|unique:documents,document_id,' . $id,
                'document_type' => 'sometimes|string|max:255',
                'linked_transaction' => 'nullable|string|max:255',
                'extracted_fields' => 'nullable|string',
                'status' => 'sometimes|string|in:pending,review,indexed,archived',
                'uploaded_by' => 'sometimes|string|max:255',
                'description' => 'nullable|string'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $document->update($request->all());
            
            // Log the action
            $this->createLogisticsRecord(
                'updated',
                'Document Tracking',
                "Updated document {$document->document_id}",
                $request->uploaded_by ?? 'System',
                $document->document_id
            );
            
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
        try {
            $document = Document::find($id);
            
            if (!$document) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found'
                ], 404);
            }
            
            // Delete associated file
            Storage::disk('public')->delete($document->file_path);
            
            $documentId = $document->document_id;
            $document->delete();
            
            // Log the action
            $this->createLogisticsRecord(
                'deleted',
                'Document Tracking',
                "Deleted document {$documentId}",
                'System',
                $documentId
            );
            
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
    
    public function processOCR($id)
    {
        try {
            $document = Document::find($id);
            
            if (!$document) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found'
                ], 404);
            }
            
            // Simulate OCR processing (Replace with actual MINDEE API integration)
            $extractedData = [
                'po_number' => 'PO-' . rand(100, 999),
                'vendor_name' => 'Vendor ' . Str::random(8),
                'amount' => '$' . rand(1000, 10000) . '.00',
                'date' => now()->format('Y-m-d'),
                'raw_text' => 'Sample extracted text from ' . $document->file_name
            ];
            
            $document->update([
                'extracted_fields' => json_encode($extractedData),
                'ocr_processed' => true,
                'ocr_processed_at' => now(),
                'status' => 'indexed'
            ]);
            
            // Log the action
            $this->createLogisticsRecord(
                'processed',
                'Document Tracking',
                "OCR processed for document {$document->document_id}",
                'System',
                $document->document_id,
                true
            );
            
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
    
    // ==================== LOGISTICS RECORDS METHODS ====================
    
    public function getLogisticsRecords(Request $request)
    {
        try {
            $query = LogisticsRecord::query();
            
            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('log_id', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('performed_by', 'like', "%{$search}%")
                      ->orWhere('related_reference', 'like', "%{$search}%");
                });
            }
            
            // Filter by action
            if ($request->has('action') && $request->action) {
                $query->where('action', $request->action);
            }
            
            // Filter by module
            if ($request->has('module') && $request->module) {
                $query->where('module', $request->module);
            }
            
            // Filter by AI/OCR usage
            if ($request->has('ai_ocr_used')) {
                $query->where('ai_ocr_used', $request->boolean('ai_ocr_used'));
            }
            
            // Pagination
            $perPage = $request->get('limit', 10);
            $records = $query->orderBy('timestamp', 'desc')->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'records' => $records->items(),
                    'total' => $records->total(),
                    'current_page' => $records->currentPage(),
                    'last_page' => $records->lastPage(),
                    'per_page' => $records->perPage()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch logistics records: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function createLogisticsRecord($action, $module, $description, $performedBy, $relatedReference = null, $aiOcrUsed = false)
    {
        try {
            $logId = 'LOG-' . strtoupper(Str::random(6));
            
            $record = LogisticsRecord::create([
                'log_id' => $logId,
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'performed_by' => $performedBy,
                'timestamp' => now(),
                'ai_ocr_used' => $aiOcrUsed,
                'related_reference' => $relatedReference,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return $record;
            
        } catch (\Exception $e) {
            \Log::error('Failed to create logistics record: ' . $e->getMessage());
            return null;
        }
    }
    
    public function addLogisticsRecord(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|string|max:255',
                'module' => 'required|string|max:255',
                'description' => 'required|string',
                'performed_by' => 'required|string|max:255',
                'ai_ocr_used' => 'boolean',
                'related_reference' => 'nullable|string|max:255'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $record = $this->createLogisticsRecord(
                $request->action,
                $request->module,
                $request->description,
                $request->performed_by,
                $request->related_reference,
                $request->boolean('ai_ocr_used')
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Logistics record added successfully',
                'data' => $record
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add logistics record: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function updateLogisticsRecord(Request $request, $id)
    {
        try {
            $record = LogisticsRecord::find($id);
            
            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logistics record not found'
                ], 404);
            }
            
            $validator = Validator::make($request->all(), [
                'log_id' => 'sometimes|string|max:255|unique:logistics_records,log_id,' . $id,
                'action' => 'sometimes|string|max:255',
                'module' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'performed_by' => 'sometimes|string|max:255',
                'ai_ocr_used' => 'boolean',
                'related_reference' => 'nullable|string|max:255'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $record->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Logistics record updated successfully',
                'data' => $record
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update logistics record: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteLogisticsRecord($id)
    {
        try {
            $record = LogisticsRecord::find($id);
            
            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logistics record not found'
                ], 404);
            }
            
            $record->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Logistics record deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete logistics record: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // ==================== STATISTICS METHODS ====================
    
    public function getOverviewStats()
    {
        try {
            $documentStats = [
                'total_documents' => Document::count(),
                'indexed' => Document::indexed()->count(),
                'pending' => Document::pending()->count(),
                'under_review' => Document::underReview()->count(),
                'archived' => Document::archived()->count(),
                'ocr_processed' => Document::ocrProcessed()->count()
            ];
            
            $logStats = [
                'total_logs' => LogisticsRecord::count(),
                'upload_actions' => LogisticsRecord::uploads()->count(),
                'approved_actions' => LogisticsRecord::approvals()->count(),
                'delivery_actions' => LogisticsRecord::deliveries()->count(),
                'ai_used' => LogisticsRecord::aiUsed()->count()
            ];
            
            return response()->json([
                'success' => true,
                'data' => [
                    'documents' => $documentStats,
                    'logs' => $logStats
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // ==================== UTILITY METHODS ====================
    
    public function getDocumentTypes()
    {
        try {
            $types = [
                'PO' => 'Purchase Order',
                'GRN' => 'Goods Received Note',
                'Invoice' => 'Invoice',
                'Delivery Note' => 'Delivery Note',
                'Contract' => 'Contract',
                'Quotation' => 'Quotation',
                'Receipt' => 'Receipt',
                'Other' => 'Other'
            ];
            
            return response()->json([
                'success' => true,
                'data' => $types
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch document types: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function healthCheck()
    {
        try {
            // Check database connection
            \DB::connection()->getPdo();
            
            return response()->json([
                'success' => true,
                'message' => 'DTLR service is healthy',
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'DTLR service is unhealthy: ' . $e->getMessage()
            ], 503);
        }
    }
}