<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::connection('dtlr')->hasTable('dtlr_logistics_records')) {
            Schema::connection('dtlr')->create('dtlr_logistics_records', function (Blueprint $table) {
                $table->string('log_id', 20)->primary();
                $table->string('module', 120);
                $table->string('submodule', 120);
                $table->string('performed_action', 120);
                $table->string('performed_by', 160);
                $table->timestamps();

                $table->index('module');
                $table->index('submodule');
                $table->index('performed_action');
                $table->index('created_at');
            });
        }
    }

    public function down()
    {
        Schema::connection('dtlr')->dropIfExists('dtlr_logistics_records');
    }
};
