<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::connection('sws')->hasTable('sws_incoming_asset')) {
            Schema::connection('sws')->create('sws_incoming_asset', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('sws_purcprod_id')->index();
                $table->string('sws_purcprod_prod_id')->nullable()->index();
                $table->string('sws_purcprod_prod_name')->nullable();
                $table->decimal('sws_purcprod_prod_price', 12, 2)->default(0);
                $table->integer('sws_purcprod_prod_unit')->default(0);
                $table->string('sws_purcprod_prod_type', 50)->nullable();
                $table->string('sws_purcprod_status', 50)->nullable();
                $table->date('sws_purcprod_date')->nullable();
                $table->date('sws_purcprod_warranty')->nullable();
                $table->date('sws_purcprod_expiration')->nullable();
                $table->text('sws_purcprod_desc')->nullable();
                $table->enum('sws_purcprod_inventory', ['no', 'yes'])->default('no');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::connection('sws')->dropIfExists('sws_incoming_asset');
    }
};

