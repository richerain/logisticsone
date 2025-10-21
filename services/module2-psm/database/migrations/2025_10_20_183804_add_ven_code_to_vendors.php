<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Vendor;
use Haruncpi\LaravelIdGenerator\IdGenerator;

return new class extends Migration
{
    public function up()
    {
        // Add ven_code column if it doesn't exist
        if (!Schema::hasColumn('psm_vendors', 'ven_code')) {
            Schema::table('psm_vendors', function (Blueprint $table) {
                $table->string('ven_code', 20)->nullable()->after('ven_id');
            });

            // Generate codes for existing vendors
            $vendors = Vendor::all();
            foreach ($vendors as $vendor) {
                $vendor->ven_code = IdGenerator::generate([
                    'table' => 'psm_vendors',
                    'field' => 'ven_code',
                    'length' => 8,
                    'prefix' => 'VEN'
                ]);
                $vendor->save();
            }

            // Make the column unique and not nullable
            Schema::table('psm_vendors', function (Blueprint $table) {
                $table->string('ven_code', 20)->unique()->change();
            });
        }
    }

    public function down()
    {
        Schema::table('psm_vendors', function (Blueprint $table) {
            $table->dropColumn('ven_code');
        });
    }
};