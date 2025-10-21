<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('psm_quotes', function (Blueprint $table) {
            // Check if the column doesn't exist before adding it
            if (!Schema::hasColumn('psm_quotes', 'request_id')) {
                $table->string('request_id', 20)->after('quote_code');
                
                // Add foreign key constraint
                $table->foreign('request_id')
                      ->references('request_id')
                      ->on('psm_purchase_orders')
                      ->onDelete('cascade');
                
                // Add index
                $table->index('request_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psm_quotes', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['request_id']);
            // Drop the column
            $table->dropColumn('request_id');
        });
    }
};