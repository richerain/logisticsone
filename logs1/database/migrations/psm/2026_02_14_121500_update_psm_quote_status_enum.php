<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Expand quo_status enum to include new labels while keeping legacy values for backward compatibility
        $table = 'psm_quote';
        if (Schema::connection('psm')->hasColumn($table, 'quo_status')) {
            DB::connection('psm')->statement("
                ALTER TABLE `psm_quote`
                MODIFY `quo_status` ENUM(
                    'Reject',
                    'Cancel',
                    'PO Received',
                    'Processing Order',
                    'Dispatched',
                    'Delivered',
                    'Vendor-Review',
                    'In-Progress',
                    'Completed'
                ) NOT NULL DEFAULT 'PO Received'
            ");
        }
    }

    public function down(): void
    {
        // Revert to original enum set
        $table = 'psm_quote';
        if (Schema::connection('psm')->hasColumn($table, 'quo_status')) {
            DB::connection('psm')->statement("
                ALTER TABLE `psm_quote`
                MODIFY `quo_status` ENUM(
                    'Reject',
                    'Cancel',
                    'Vendor-Review',
                    'In-Progress',
                    'Completed'
                ) NOT NULL DEFAULT 'Vendor-Review'
            ");
        }
    }
};

