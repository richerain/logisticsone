<?php

namespace App\Services;

use App\Repositories\DTLRRepository;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DTLRService
{
    protected DTLRRepository $dtlrRepository;

    public function __construct(DTLRRepository $dtlrRepository)
    {
        $this->dtlrRepository = $dtlrRepository;
    }

    public function getAllDocuments()
    {
        try {
            if (! Schema::connection('dtlr')->hasTable('dtlr_documents')) {
                return [
                    'success' => true,
                    'data' => [],
                    'message' => 'No documents found',
                ];
            }
            $docs = $this->dtlrRepository->listDocuments();

            return [
                'success' => true,
                'data' => $docs,
                'message' => 'Documents retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching DTLR documents: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve documents',
            ];
        }
    }

    public function createDocument(array $payload, $file)
    {
        DB::connection('dtlr')->beginTransaction();
        try {
            $this->ensureDocumentsTableExists();
            $docId = $this->generateDocId();
            $ext = $file ? strtolower($file->getClientOriginalExtension() ?: 'pdf') : 'pdf';
            $storeName = $docId.'.'.$ext;
            $path = $file ? $file->storeAs('dtlr/documents', $storeName, 'local') : null;

            $doc = $this->dtlrRepository->createDocument([
                'doc_id' => $docId,
                'doc_type' => $payload['doc_type'],
                'doc_title' => $payload['doc_title'],
                'doc_status' => $payload['doc_status'] ?? 'pending_review',
                'doc_file_available' => array_key_exists('doc_file_available', $payload) ? (bool) $payload['doc_file_available'] : true,
                'doc_file_path' => $path,
                'doc_file_original_name' => $file ? $file->getClientOriginalName() : null,
                'doc_file_mime' => $file ? $file->getClientMimeType() : null,
                'doc_file_size' => $file ? (int) $file->getSize() : null,
            ]);

            $this->upsertLogisticsRecordFromDocument($doc, 'Upload document');

            DB::connection('dtlr')->commit();

            return [
                'success' => true,
                'data' => $doc,
                'message' => 'Document created successfully',
            ];
        } catch (\Exception $e) {
            DB::connection('dtlr')->rollBack();
            Log::error('Error creating DTLR document: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to create document',
            ];
        }
    }

    public function getLogisticsRecords()
    {
        try {
            if (! Schema::connection('dtlr')->hasTable('dtlr_logistics_records')) {
                return [
                    'success' => true,
                    'data' => [],
                    'message' => 'No records found',
                ];
            }

            $this->syncLogisticsRecordsFromDocuments();
            $records = $this->dtlrRepository->listLogisticsRecords();

            return [
                'success' => true,
                'data' => $records,
                'message' => 'Records retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching DTLR logistics records: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve records',
            ];
        }
    }

    public function logDocumentAction(string $docId, string $performedAction): void
    {
        try {
            if (! Schema::connection('dtlr')->hasTable('dtlr_logistics_records')) {
                return;
            }

            if (! Schema::connection('dtlr')->hasTable('dtlr_documents')) {
                return;
            }

            if (! Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_id')) {
                return;
            }

            $doc = $this->dtlrRepository->findDocument($docId);
            if (! $doc) {
                return;
            }

            if (! in_array((string) $doc->doc_status, ['indexed', 'archived'], true)) {
                DB::connection('dtlr')->table('dtlr_logistics_records')->where('doc_id', $docId)->delete();

                return;
            }

            $record = $this->dtlrRepository->findLogisticsRecordByDocId($docId);
            if (! $record) {
                $record = $this->dtlrRepository->createLogisticsRecord([
                    'log_id' => $this->generateLogId(),
                    'doc_id' => $docId,
                    'doc_type' => $doc->doc_type,
                    'doc_title' => $doc->doc_title,
                    'doc_status' => $doc->doc_status,
                    'module' => 'Document Tracking & Logistics Record',
                    'submodule' => 'Document Tracker',
                    'performed_action' => $performedAction,
                    'performed_by' => $this->currentUserLabel(),
                    'created_at' => $doc->created_at,
                    'updated_at' => $doc->updated_at,
                ]);

                return;
            }

            $this->dtlrRepository->updateLogisticsRecord($record, [
                'doc_type' => $doc->doc_type,
                'doc_title' => $doc->doc_title,
                'doc_status' => $doc->doc_status,
                'performed_action' => $performedAction,
                'performed_by' => $this->currentUserLabel(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to write DTLR logistics record: '.$e->getMessage());
        }
    }

    public function deleteDocument(string $docId)
    {
        DB::connection('dtlr')->beginTransaction();
        try {
            $doc = $this->dtlrRepository->findDocument($docId);
            if (! $doc) {
                return [
                    'success' => false,
                    'message' => 'Document not found',
                ];
            }

            $path = $doc->doc_file_path;
            $this->dtlrRepository->deleteDocument($doc);
            if ($path) {
                Storage::disk('local')->delete($path);
            }

            if (Schema::connection('dtlr')->hasTable('dtlr_logistics_records') && Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_id')) {
                DB::connection('dtlr')->table('dtlr_logistics_records')->where('doc_id', $docId)->delete();
            }

            DB::connection('dtlr')->commit();

            return [
                'success' => true,
                'message' => 'Document deleted successfully',
            ];
        } catch (\Exception $e) {
            DB::connection('dtlr')->rollBack();
            Log::error('Error deleting DTLR document: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to delete document',
            ];
        }
    }

    public function updateDocumentStatus(string $docId, string $status)
    {
        DB::connection('dtlr')->beginTransaction();
        try {
            if (! Schema::connection('dtlr')->hasTable('dtlr_documents')) {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => 'Documents table not found',
                ];
            }

            $doc = $this->dtlrRepository->findDocument($docId);
            if (! $doc) {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => 'Document not found',
                ];
            }

            $updated = $this->dtlrRepository->updateDocumentStatus($doc, $status);
            $this->upsertLogisticsRecordFromDocument($updated, 'Update status');

            DB::connection('dtlr')->commit();

            return [
                'success' => true,
                'data' => $updated,
                'message' => 'Document reviewed successfully',
            ];
        } catch (\Exception $e) {
            DB::connection('dtlr')->rollBack();
            Log::error('Error updating DTLR document status: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to review document',
            ];
        }
    }

    private function upsertLogisticsRecordFromDocument($doc, string $performedAction, ?string $performedBy = null): void
    {
        try {
            if (! $doc || empty($doc->doc_id)) {
                return;
            }
            if (! Schema::connection('dtlr')->hasTable('dtlr_logistics_records')) {
                return;
            }
            if (! Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_id')) {
                return;
            }

            $docId = (string) $doc->doc_id;
            if (! in_array((string) ($doc->doc_status ?? ''), ['indexed', 'archived'], true)) {
                DB::connection('dtlr')->table('dtlr_logistics_records')->where('doc_id', $docId)->delete();

                return;
            }

            $record = $this->dtlrRepository->findLogisticsRecordByDocId($docId);

            $data = [
                'doc_id' => $docId,
                'doc_type' => $doc->doc_type ?? null,
                'doc_title' => $doc->doc_title ?? null,
                'doc_status' => $doc->doc_status ?? null,
                'module' => 'Document Tracking & Logistics Record',
                'submodule' => 'Document Tracker',
            ];

            if (! $record) {
                $this->dtlrRepository->createLogisticsRecord(array_merge($data, [
                    'log_id' => $this->generateLogId(),
                    'performed_action' => $performedAction,
                    'performed_by' => $performedBy ?? $this->currentUserLabel(),
                    'created_at' => $doc->created_at ?? now(),
                    'updated_at' => $doc->updated_at ?? now(),
                ]));

                return;
            }

            $update = $data;
            $update['performed_action'] = $performedAction;
            $update['performed_by'] = $performedBy ?? $this->currentUserLabel();

            $this->dtlrRepository->updateLogisticsRecord($record, $update);
        } catch (\Throwable $e) {
            Log::warning('Failed syncing logistics record from document: '.$e->getMessage());
        }
    }

    private function syncLogisticsRecordsFromDocuments(): void
    {
        try {
            if (! Schema::connection('dtlr')->hasTable('dtlr_documents')) {
                return;
            }
            if (! Schema::connection('dtlr')->hasTable('dtlr_logistics_records')) {
                return;
            }
            if (! Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_id')) {
                return;
            }

            $docs = $this->dtlrRepository->listDocuments()
                ->filter(function ($doc) {
                    return in_array((string) ($doc->doc_status ?? ''), ['indexed', 'archived'], true);
                })
                ->values();
            $docIds = $docs->pluck('doc_id')->filter()->map(fn ($v) => (string) $v)->values()->all();

            foreach ($docs as $doc) {
                if (! $doc || empty($doc->doc_id)) {
                    continue;
                }

                $docId = (string) $doc->doc_id;
                $record = $this->dtlrRepository->findLogisticsRecordByDocId($docId);
                if (! $record) {
                    $this->dtlrRepository->createLogisticsRecord([
                        'log_id' => $this->generateLogId(),
                        'doc_id' => $docId,
                        'doc_type' => $doc->doc_type,
                        'doc_title' => $doc->doc_title,
                        'doc_status' => $doc->doc_status,
                        'module' => 'Document Tracking & Logistics Record',
                        'submodule' => 'Document Tracker',
                        'performed_action' => 'Upload document',
                        'performed_by' => 'system',
                        'created_at' => $doc->created_at ?? now(),
                        'updated_at' => $doc->updated_at ?? now(),
                    ]);

                    continue;
                }

                $this->dtlrRepository->updateLogisticsRecord($record, [
                    'doc_type' => $doc->doc_type,
                    'doc_title' => $doc->doc_title,
                    'doc_status' => $doc->doc_status,
                    'module' => 'Document Tracking & Logistics Record',
                    'submodule' => 'Document Tracker',
                ]);
            }

            if (count($docIds) === 0) {
                DB::connection('dtlr')->table('dtlr_logistics_records')->delete();

                return;
            }

            DB::connection('dtlr')->table('dtlr_logistics_records')
                ->where(function ($q) use ($docIds) {
                    $q->whereNull('doc_id')->orWhereNotIn('doc_id', $docIds);
                })
                ->delete();
        } catch (\Throwable $e) {
            Log::warning('Failed syncing logistics records from documents: '.$e->getMessage());
        }
    }

    public function getDocumentOrNull(string $docId)
    {
        try {
            return $this->dtlrRepository->findDocument($docId);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function documentTypes()
    {
        return ['Contract', 'Purchase Order', 'Invoice', 'Quotation', 'Good Received Note'];
    }

    private function generateDocId(): string
    {
        $prefix = 'DOC'.now()->format('Ymd');
        $maxTries = 20;
        for ($i = 0; $i < $maxTries; $i++) {
            $candidate = $prefix.$this->randomAlphaNum(5);
            if (! $this->dtlrRepository->findDocument($candidate)) {
                return $candidate;
            }
        }

        return $prefix.$this->randomAlphaNum(5);
    }

    private function generateLogId(): string
    {
        $prefix = 'REC'.now()->format('Ymd');
        $maxTries = 20;
        for ($i = 0; $i < $maxTries; $i++) {
            $candidate = $prefix.$this->randomAlphaNum(5);
            if (! $this->dtlrRepository->findLogisticsRecord($candidate)) {
                return $candidate;
            }
        }

        return $prefix.$this->randomAlphaNum(5);
    }

    private function currentUserLabel(): string
    {
        $user = auth()->user();
        if (! $user) {
            return 'system';
        }

        $name = $this->titleCase(trim(($user->firstname ?? '').' '.($user->lastname ?? '')));
        $role = $this->titleCase(trim((string) ($user->roles ?? '')));
        $label = trim($name.($role ? ' - '.$role : ''));

        return $label !== '' ? $label : ($user->email ?? 'system');
    }

    private function titleCase(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }
        $parts = preg_split('/\s+/', $value) ?: [];
        $out = array_map(function ($w) {
            $w = strtolower((string) $w);

            return ucfirst($w);
        }, $parts);

        return trim(implode(' ', $out));
    }

    private function ensureDocumentsTableExists(): void
    {
        if (Schema::connection('dtlr')->hasTable('dtlr_documents')) {
            return;
        }

        Schema::connection('dtlr')->create('dtlr_documents', function (Blueprint $table) {
            $table->string('doc_id', 20)->primary();
            $table->enum('doc_type', ['Contract', 'Purchase Order', 'Invoice', 'Quotation', 'Good Received Note']);
            $table->string('doc_title', 255);
            $table->enum('doc_status', ['pending_review', 'indexed', 'archived'])->default('pending_review');
            $table->boolean('doc_file_available')->default(true);
            $table->string('doc_file_path', 500)->nullable();
            $table->string('doc_file_original_name', 255)->nullable();
            $table->string('doc_file_mime', 100)->nullable();
            $table->unsignedBigInteger('doc_file_size')->nullable();
            $table->timestamps();

            $table->index('doc_type');
            $table->index('doc_status');
            $table->index('created_at');
        });
    }

    private function randomAlphaNum(int $len): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $out = '';
        for ($i = 0; $i < $len; $i++) {
            $out .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        return $out;
    }
}
