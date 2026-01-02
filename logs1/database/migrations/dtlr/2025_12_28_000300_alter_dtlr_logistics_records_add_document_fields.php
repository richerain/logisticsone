<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::connection('dtlr')->hasTable('dtlr_logistics_records')) {
            return;
        }

        Schema::connection('dtlr')->table('dtlr_logistics_records', function (Blueprint $table) {
            if (! Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_id')) {
                $table->string('doc_id', 20)->nullable()->after('log_id');
                $table->index('doc_id');
            }
            if (! Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_type')) {
                $table->string('doc_type', 60)->nullable()->after('doc_id');
            }
            if (! Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_title')) {
                $table->string('doc_title', 255)->nullable()->after('doc_type');
            }
            if (! Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_status')) {
                $table->string('doc_status', 30)->nullable()->after('doc_title');
                $table->index('doc_status');
            }
        });
    }

    public function down()
    {
        if (! Schema::connection('dtlr')->hasTable('dtlr_logistics_records')) {
            return;
        }

        Schema::connection('dtlr')->table('dtlr_logistics_records', function (Blueprint $table) {
            if (Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_id')) {
                $table->dropIndex(['doc_id']);
                $table->dropColumn('doc_id');
            }
            if (Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_type')) {
                $table->dropColumn('doc_type');
            }
            if (Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_title')) {
                $table->dropColumn('doc_title');
            }
            if (Schema::connection('dtlr')->hasColumn('dtlr_logistics_records', 'doc_status')) {
                $table->dropIndex(['doc_status']);
                $table->dropColumn('doc_status');
            }
        });
    }
};
