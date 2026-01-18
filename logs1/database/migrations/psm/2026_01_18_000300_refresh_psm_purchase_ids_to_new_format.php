<?php

use App\Models\PSM\Purchase;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Purchase::query()
            ->orderBy('id')
            ->chunkById(100, function ($purchases) {
                foreach ($purchases as $purchase) {
                    $purchase->pur_id = $this->generateNewPurchaseId();
                    $purchase->save();
                }
            });
    }

    public function down()
    {
    }

    private function generateNewPurchaseId()
    {
        $prefix = 'PURC';
        $datePart = now()->format('Ymd');

        do {
            $randomPart = $this->randomAlphanumeric(5);
            $purId = $prefix.$datePart.$randomPart;
        } while (Purchase::where('pur_id', $purId)->exists());

        return $purId;
    }

    private function randomAlphanumeric($length = 5)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $result;
    }
};

