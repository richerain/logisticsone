<?php

namespace App\Repositories;

use App\Models\DTLR\Document;
use App\Models\DTLR\LogisticsRecord;

class DTLRRepository
{
    public function listDocuments()
    {
        return Document::orderBy('created_at', 'desc')->get();
    }

    public function findDocument(string $docId)
    {
        return Document::find($docId);
    }

    public function createDocument(array $data)
    {
        return Document::create($data);
    }

    public function deleteDocument(Document $doc)
    {
        return $doc->delete();
    }

    public function updateDocumentStatus(Document $doc, string $status)
    {
        $doc->doc_status = $status;
        $doc->save();
        $doc->refresh();

        return $doc;
    }

    public function listLogisticsRecords()
    {
        return LogisticsRecord::orderBy('created_at', 'desc')->get();
    }

    public function findLogisticsRecord(string $logId)
    {
        return LogisticsRecord::find($logId);
    }

    public function createLogisticsRecord(array $data)
    {
        return LogisticsRecord::create($data);
    }

    public function findLogisticsRecordByDocId(string $docId)
    {
        return LogisticsRecord::where('doc_id', $docId)->first();
    }

    public function updateLogisticsRecord(LogisticsRecord $record, array $data)
    {
        $record->fill($data);
        $record->save();
        $record->refresh();

        return $record;
    }
}
