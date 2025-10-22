<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\DocumentTracker;
use App\Models\LogisticsRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class DTLRController extends Controller
{
    // Document Tracker Methods

    /**
     * Get all documents with optional filtering
     */
    public function getDocuments(Request $request)
    {
        try {
            Log::info('Fetching documents with filters:', $request->all());

            $query = DocumentTracker::query();

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('document_id', 'LIKE', "%{$search}%")
                      ->orWhere('document_type', 'LIKE', "%{$search}%")
                      ->orWhere('linked_transaction', 'LIKE', "%{$search}%")
                      ->orWhere('uploaded_by', 'LIKE', "%{$search}%");
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

            $documents = $query->orderBy('created_at', 'desc')->get();

            Log::info('Documents retrieved successfully', ['count' => $documents->count()]);

            return response()->json([
                'success' => true,
                'data' => $documents,
                'message' => 'Documents retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching documents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single document by ID
     */
    public function getDocument($id)
    {
        try {
            Log::info('Fetching document by ID:', ['id' => $id]);

            $document = DocumentTracker::find($id);

            if (!$document) {
                Log::warning('Document not found', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $document,
                'message' => 'Document retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve document'
            ], 500);
        }
    }

    /**
     * Create new document
     */
    public function createDocument(Request $request)
    {
        try {
            Log::info('Creating new document', $request->except(['document_file']));

            $validator = Validator::make($request->all(), [
                'document_type' => 'required|string|max:255',
                'linked_transaction' => 'nullable|string|max:255',
                'extracted_fields' => 'nullable|string',
                'uploaded_by' => 'required|string|max:255',
                'status' => 'required|string|in:Indexed,Pending Review,Archived',
                'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240' // 10MB max
            ]);

            if ($validator->fails()) {
                Log::warning('Document validation failed', ['errors' => $validator->errors()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Generate document ID
            $lastDocument = DocumentTracker::orderBy('id', 'desc')->first();
            $nextId = $lastDocument ? intval(substr($lastDocument->document_id, 3)) + 1 : 1;
            $documentId = 'DOC' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            $documentData = [
                'document_id' => $documentId,
                'document_type' => $request->document_type,
                'linked_transaction' => $request->linked_transaction,
                'extracted_fields' => $request->extracted_fields,
                'uploaded_by' => $request->uploaded_by,
                'status' => $request->status,
                'upload_date' => now()
            ];

            // Handle file upload
            if ($request->hasFile('document_file')) {
                $file = $request->file('document_file');
                $fileName = $documentId . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('documents', $fileName, 'public');
                $documentData['file_path'] = $filePath;
                $documentData['file_name'] = $file->getClientOriginalName();
                $documentData['file_size'] = $file->getSize();
                $documentData['file_type'] = $file->getMimeType();
                
                Log::info('File uploaded successfully', [
                    'file_path' => $filePath,
                    'file_size' => $documentData['file_size']
                ]);
            }

            // Simulate OCR extraction if no extracted fields provided
            if (empty($request->extracted_fields) && isset($documentData['file_path'])) {
                $documentData['extracted_fields'] = $this->simulateOCRExtraction($documentData['file_name'] ?? '');
                Log::info('OCR extraction simulated', ['extracted_fields' => $documentData['extracted_fields']]);
            }

            $document = DocumentTracker::create($documentData);

            // Log the document upload activity
            $this->createLogisticsRecord([
                'action' => 'Document Uploaded',
                'module' => 'Document Tracker',
                'performed_by' => $request->uploaded_by,
                'ai_ocr_used' => !empty($documentData['extracted_fields']),
                'details' => "Document {$documentId} uploaded successfully"
            ]);

            DB::commit();

            Log::info('Document created successfully', ['document_id' => $documentId]);

            return response()->json([
                'success' => true,
                'data' => $document,
                'message' => 'Document created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update document
     */
    public function updateDocument(Request $request, $id)
    {
        try {
            Log::info('Updating document', ['id' => $id, 'data' => $request->all()]);

            $document = DocumentTracker::find($id);

            if (!$document) {
                Log::warning('Document not found for update', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'document_type' => 'sometimes|required|string|max:255',
                'linked_transaction' => 'nullable|string|max:255',
                'extracted_fields' => 'nullable|string',
                'status' => 'sometimes|required|string|in:Indexed,Pending Review,Archived',
                'uploaded_by' => 'sometimes|required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $document->update($validator->validated());

            // Log the update activity
            $this->createLogisticsRecord([
                'action' => 'Document Updated',
                'module' => 'Document Tracker',
                'performed_by' => $request->uploaded_by ?? 'System',
                'ai_ocr_used' => false,
                'details' => "Document {$document->document_id} updated"
            ]);

            Log::info('Document updated successfully', ['document_id' => $document->document_id]);

            return response()->json([
                'success' => true,
                'data' => $document,
                'message' => 'Document updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document'
            ], 500);
        }
    }

    /**
     * Delete document
     */
    public function deleteDocument($id)
    {
        try {
            Log::info('Deleting document', ['id' => $id]);

            $document = DocumentTracker::find($id);

            if (!$document) {
                Log::warning('Document not found for deletion', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found'
                ], 404);
            }

            // Delete associated file
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
                Log::info('Document file deleted', ['file_path' => $document->file_path]);
            }

            $documentId = $document->document_id;
            $document->delete();

            // Log the deletion activity
            $this->createLogisticsRecord([
                'action' => 'Document Deleted',
                'module' => 'Document Tracker',
                'performed_by' => 'System',
                'ai_ocr_used' => false,
                'details' => "Document {$documentId} deleted"
            ]);

            Log::info('Document deleted successfully', ['document_id' => $documentId]);

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document'
            ], 500);
        }
    }

    /**
     * Download document file
     */
    public function downloadDocument($id)
    {
        try {
            Log::info('Downloading document', ['id' => $id]);

            $document = DocumentTracker::find($id);

            if (!$document || !$document->file_path) {
                Log::warning('Document file not found for download', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Document file not found'
                ], 404);
            }

            if (!Storage::disk('public')->exists($document->file_path)) {
                Log::error('File not found on server', ['file_path' => $document->file_path]);
                return response()->json([
                    'success' => false,
                    'message' => 'File not found on server'
                ], 404);
            }

            Log::info('Document download successful', ['document_id' => $document->document_id]);

            return Storage::disk('public')->download($document->file_path, $document->file_name);

        } catch (\Exception $e) {
            Log::error('Error downloading document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to download document'
            ], 500);
        }
    }

    // Logistics Record Methods

    /**
     * Get all logistics records with optional filtering
     */
    public function getLogisticsRecords(Request $request)
    {
        try {
            Log::info('Fetching logistics records with filters:', $request->all());

            $query = LogisticsRecord::query();

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('log_id', 'LIKE', "%{$search}%")
                      ->orWhere('action', 'LIKE', "%{$search}%")
                      ->orWhere('module', 'LIKE', "%{$search}%")
                      ->orWhere('performed_by', 'LIKE', "%{$search}%")
                      ->orWhere('details', 'LIKE', "%{$search}%");
                });
            }

            // Filter by module
            if ($request->has('module') && $request->module) {
                $query->where('module', $request->module);
            }

            // Filter by AI/OCR usage
            if ($request->has('ai_ocr_used') && $request->ai_ocr_used !== '') {
                $query->where('ai_ocr_used', $request->ai_ocr_used === 'true');
            }

            // Date range filter
            if ($request->has('date_from') && $request->date_from) {
                $query->where('timestamp', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->where('timestamp', '<=', $request->date_to . ' 23:59:59');
            }

            $records = $query->orderBy('timestamp', 'desc')->get();

            Log::info('Logistics records retrieved successfully', ['count' => $records->count()]);

            return response()->json([
                'success' => true,
                'data' => $records,
                'message' => 'Logistics records retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching logistics records: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve logistics records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single logistics record by ID
     */
    public function getLogisticsRecord($id)
    {
        try {
            Log::info('Fetching logistics record by ID:', ['id' => $id]);

            $record = LogisticsRecord::find($id);

            if (!$record) {
                Log::warning('Logistics record not found', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Logistics record not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $record,
                'message' => 'Logistics record retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching logistics record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve logistics record'
            ], 500);
        }
    }

    /**
     * Create logistics record (internal method)
     */
    private function createLogisticsRecord($data)
    {
        try {
            // Generate log ID
            $lastLog = LogisticsRecord::orderBy('id', 'desc')->first();
            $nextId = $lastLog ? intval(substr($lastLog->log_id, 3)) + 1 : 1;
            $logId = 'LOG' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            $logData = [
                'log_id' => $logId,
                'action' => $data['action'],
                'module' => $data['module'],
                'performed_by' => $data['performed_by'],
                'ai_ocr_used' => $data['ai_ocr_used'],
                'details' => $data['details'] ?? '',
                'timestamp' => now()
            ];

            LogisticsRecord::create($logData);

            Log::info('Logistics record created', ['log_id' => $logId]);

        } catch (\Exception $e) {
            Log::error('Error creating logistics record: ' . $e->getMessage());
        }
    }

    /**
     * Export logistics records
     */
    public function exportLogisticsRecords(Request $request)
    {
        try {
            Log::info('Exporting logistics records with filters:', $request->all());

            $query = LogisticsRecord::query();

            // Apply the same filters as getLogisticsRecords
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('log_id', 'LIKE', "%{$search}%")
                      ->orWhere('action', 'LIKE', "%{$search}%")
                      ->orWhere('module', 'LIKE', "%{$search}%")
                      ->orWhere('performed_by', 'LIKE', "%{$search}%")
                      ->orWhere('details', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('module') && $request->module) {
                $query->where('module', $request->module);
            }

            if ($request->has('ai_ocr_used') && $request->ai_ocr_used !== '') {
                $query->where('ai_ocr_used', $request->ai_ocr_used === 'true');
            }

            if ($request->has('date_from') && $request->date_from) {
                $query->where('timestamp', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->where('timestamp', '<=', $request->date_to . ' 23:59:59');
            }

            $records = $query->orderBy('timestamp', 'desc')->get();

            $exportData = [];
            foreach ($records as $record) {
                $exportData[] = [
                    'Log ID' => $record->log_id,
                    'Action' => $record->action,
                    'Module' => $record->module,
                    'Performed By' => $record->performed_by,
                    'Timestamp' => $record->timestamp,
                    'AI/OCR Used' => $record->ai_ocr_used ? 'Yes' : 'No',
                    'Details' => $record->details
                ];
            }

            Log::info('Logistics records exported successfully', ['count' => count($exportData)]);

            return response()->json([
                'success' => true,
                'data' => $exportData,
                'message' => 'Logistics records exported successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error exporting logistics records: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to export logistics records'
            ], 500);
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats()
    {
        try {
            Log::info('Fetching DTLR statistics');

            $documentStats = [
                'total_documents' => DocumentTracker::count(),
                'indexed_documents' => DocumentTracker::where('status', 'Indexed')->count(),
                'pending_review' => DocumentTracker::where('status', 'Pending Review')->count(),
                'archived_documents' => DocumentTracker::where('status', 'Archived')->count()
            ];

            $logStats = [
                'total_logs' => LogisticsRecord::count(),
                'logs_today' => LogisticsRecord::whereDate('timestamp', today())->count(),
                'ai_ocr_used' => LogisticsRecord::where('ai_ocr_used', true)->count(),
            ];

            // Get top module
            $topModule = LogisticsRecord::groupBy('module')
                ->select('module', DB::raw('count(*) as count'))
                ->orderBy('count', 'desc')
                ->first();

            $logStats['top_module'] = $topModule ? $topModule->module : 'No data';

            Log::info('Statistics retrieved successfully', [
                'documents' => $documentStats,
                'logs' => $logStats
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'documents' => $documentStats,
                    'logs' => $logStats
                ],
                'message' => 'Statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics'
            ], 500);
        }
    }

    /**
     * Simulate OCR extraction (placeholder for real OCR implementation)
     */
    private function simulateOCRExtraction($fileName)
    {
        // This is a simulation - in real implementation, integrate with OCR service like Tesseract, Google Vision, etc.
        $extractedData = [
            'document_name' => $fileName,
            'extraction_date' => now()->toDateTimeString(),
            'extracted_fields' => [
                'vendor_name' => 'Sample Vendor',
                'po_number' => 'PO-' . rand(1000, 9999),
                'date' => now()->format('Y-m-d'),
                'amount' => number_format(rand(1000, 50000) / 100, 2),
                'items_count' => rand(1, 20)
            ],
            'confidence_score' => rand(85, 98) / 100
        ];

        return json_encode($extractedData);
    }

    /**
     * Health check endpoint
     */
    public function health()
    {
        return response()->json([
            'success' => true,
            'message' => 'DTLR Service is running',
            'timestamp' => now()
        ]);
    }
}