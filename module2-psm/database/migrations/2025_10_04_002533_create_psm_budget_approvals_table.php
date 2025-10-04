<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('psm_budget_approvals')) {
            Schema::create('psm_budget_approvals', function (Blueprint $table) {
                $table->id('bud_id');
                $table->enum('entity_type', ['order', 'restock', 'requisition']);
                $table->unsignedBigInteger('entity_id');
                $table->string('bud_name', 255)->nullable();
                $table->integer('quantity')->nullable();
                $table->decimal('unit_price', 10, 2)->nullable();
                $table->decimal('total_budget', 12, 2)->nullable();
                $table->text('bud_desc')->nullable();
                $table->enum('bud_status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->integer('approver_user_id')->nullable();
                $table->integer('finance_budget_id')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
                
                $table->index('bud_status');
                $table->index(['entity_type', 'entity_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('psm_budget_approvals');
    }
};