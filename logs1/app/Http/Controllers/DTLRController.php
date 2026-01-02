<?php

namespace App\Http\Controllers;

use App\Services\DTLRService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DTLRController extends Controller
{
    protected DTLRService $dtlrService;

    public function __construct(DTLRService $dtlrService)
    {
        $this->dtlrService = $dtlrService;
    }

    public function getDocumentTracker()
    {
        $result = $this->dtlrService->getAllDocuments();

        return response()->json($result, 200);
    }

    public function createDocument(Request $request)
    {
        $validated = $request->validate([
            'doc_type' => ['required', Rule::in($this->dtlrService->documentTypes())],
            'doc_title' => ['required', 'string', 'max:255'],
            'doc_status' => ['nullable', Rule::in(['pending_review', 'indexed', 'archived'])],
            'doc_file_available' => ['nullable', 'boolean'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx', 'max:20480'],
        ]);

        $file = $request->file('file');
        $result = $this->dtlrService->createDocument($validated, $file);

        return response()->json($result, $result['success'] ? 201 : 500);
    }

    public function viewDocument(string $docId)
    {
        $doc = $this->dtlrService->getDocumentOrNull($docId);
        if (! $doc) {
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }

        $path = $doc->doc_file_path;
        if (! $path || ! Storage::disk('local')->exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not available'], 404);
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $name = $doc->doc_file_original_name ?: ($doc->doc_id.'.'.($ext ?: 'file'));

        $mime = Storage::disk('local')->mimeType($path) ?: 'application/octet-stream';

        return Storage::disk('local')->response($path, $name, ['Content-Type' => $mime], 'inline');
    }

    public function downloadDocument(string $docId)
    {
        $doc = $this->dtlrService->getDocumentOrNull($docId);
        if (! $doc) {
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }

        $path = $doc->doc_file_path;
        if (! $path || ! Storage::disk('local')->exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not available'], 404);
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $name = $doc->doc_file_original_name ?: ($doc->doc_id.'.'.($ext ?: 'file'));

        $mime = Storage::disk('local')->mimeType($path) ?: 'application/octet-stream';

        $this->dtlrService->logDocumentAction($docId, 'Download document');

        return Storage::disk('local')->download($path, $name, ['Content-Type' => $mime]);
    }

    public function deleteDocument(string $docId)
    {
        $result = $this->dtlrService->deleteDocument($docId);

        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function updateDocumentStatus(Request $request, string $docId)
    {
        $validated = $request->validate([
            'doc_status' => ['required', Rule::in(['pending_review', 'indexed', 'archived'])],
        ]);

        $result = $this->dtlrService->updateDocumentStatus($docId, $validated['doc_status']);
        if (! $result['success']) {
            $code = ($result['message'] ?? '') === 'Document not found' ? 404 : 400;

            return response()->json($result, $code);
        }

        return response()->json($result, 200);
    }

    public function getLogisticsRecord()
    {
        $result = $this->dtlrService->getLogisticsRecords();

        return response()->json($result, $result['success'] ? 200 : 500);
    }
}
