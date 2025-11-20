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
        Schema::create('sws_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->string('item_name', 255);
            $table->text('item_description')->nullable();
            $table->string('item_stock_keeping_unit', 100)->unique()->nullable();
            $table->unsignedInteger('item_category_id')->nullable();
            $table->enum('item_item_type', ['liquid', 'illiquid', 'hybrid'])->default('illiquid');
            $table->boolean('item_is_fixed')->default(false);
            $table->date('item_expiration_date')->nullable();
            $table->date('item_warranty_end')->nullable();
            $table->decimal('item_unit_price', 10, 2)->nullable();
            $table->integer('item_total_quantity')->default(0);
            $table->enum('item_liquidity_risk_level', ['high', 'medium', 'low'])->default('medium');
            $table->boolean('item_is_collateral')->default(false);
            $table->timestamp('item_created_at')->useCurrent();
            $table->timestamp('item_updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('item_category_id')->references('cat_id')->on('sws_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sws_items');
    }
};
