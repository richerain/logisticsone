<?php

namespace App\Http\Controllers;

use App\Models\DtlrDocument;
use App\Models\DtlrDocumentType;
use App\Models\DtlrBranch;
use App\Models\DtlrDocumentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DtlrDocumentController extends Controller
{
    public function index(Request $request)
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

        $documents = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $documents,
            'document_types' => DtlrDocumentType::all(),
            'stats' => [
                'total' => DtlrDocument::count(),
                'pending' => DtlrDocument::where('status', 'pending')->count(),
                'processed' => DtlrDocument::where('status', 'processed')->count(),
                'approved' => DtlrDocument::where('status', 'approved')->count(),
            ]
        ]);
    }

    public function store(Request $request)
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

    public function show($id)
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

    public function update(Request $request, $id)
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

    public function destroy($id)
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
}