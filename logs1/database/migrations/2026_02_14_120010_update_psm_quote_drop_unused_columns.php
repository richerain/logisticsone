<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('psm')->table('psm_quote', function (Blueprint $table) {
            if (Schema::connection('psm')->hasColumn('psm_quote', 'quo_item_drop_to')) {
                $table->dropColumn('quo_item_drop_to');
            }
            if (Schema::connection('psm')->hasColumn('psm_quote', 'quo_payment')) {
                $table->dropColumn('quo_payment');
            }
        });
    }

    public function down(): void
    {
        Schema::connection('psm')->table('psm_quote', function (Blueprint $table) {
            if (! Schema::connection('psm')->hasColumn('psm_quote', 'quo_item_drop_to')) {
                $table->string('quo_item_drop_to')->nullable();
            }
            if (! Schema::connection('psm')->hasColumn('psm_quote', 'quo_payment')) {
                $table->string('quo_payment')->nullable();
            }
        });
    }
};

