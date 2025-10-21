<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if request_code column exists and handle it
        if (Schema::hasColumn('psm_quotes', 'request_code')) {
            // If request_code exists but request_id doesn't, rename it
            if (!Schema::hasColumn('psm_quotes', 'request_id')) {
                Schema::table('psm_quotes', function (Blueprint $table) {
                    $table->renameColumn('request_code', 'request_id');
                });
            } else {
                // If both exist, drop request_code
                Schema::table('psm_quotes', function (Blueprint $table) {
                    $table->dropColumn('request_code');
                });
            }
        }

        // Ensure request_id is properly configured
        Schema::table('psm_quotes', function (Blueprint $table) {
            // Make sure request_id has the correct structure
            if (Schema::hasColumn('psm_quotes', 'request_id')) {
                $table->string('request_id', 20)->nullable(false)->change();
                
                // Re-add foreign key constraint if it doesn't exist
                $table->foreign('request_id')
                      ->references('request_id')
                      ->on('psm_purchase_orders')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to reverse this fix
    }
};