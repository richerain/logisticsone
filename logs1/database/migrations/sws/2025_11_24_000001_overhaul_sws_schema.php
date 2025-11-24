<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'sws';

    public function up(): void
    {
        // Warehouses (plural)
        if (!Schema::connection('sws')->hasTable('sws_warehouses')) {
            Schema::connection('sws')->create('sws_warehouses', function (Blueprint $table) {
                $table->increments('ware_id');
                $table->string('ware_name', 100);
                $table->string('ware_location', 255)->nullable();
                $table->integer('ware_capacity')->nullable();
                $table->integer('ware_capacity_used')->nullable();
                $table->integer('ware_capacity_free')->nullable();
                $table->decimal('ware_utilization', 5, 2)->default(0.00);
                $table->enum('ware_status', ['active', 'inactive', 'maintenance'])->default('active');
                $table->enum('ware_zone_type', ['liquid', 'illiquid', 'climate_controlled', 'general'])->default('general');
                $table->boolean('ware_supports_fixed_items')->default(true);
                $table->timestamp('ware_created_at')->useCurrent();
                $table->timestamp('ware_updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }

        // Items
        if (!Schema::connection('sws')->hasTable('sws_items')) {
            Schema::connection('sws')->create('sws_items', function (Blueprint $table) {
                $table->increments('item_id');
                $table->string('item_code')->nullable();
                $table->string('item_name', 255);
                $table->text('item_description')->nullable();
                $table->string('item_stock_keeping_unit', 100)->unique()->nullable();
                $table->unsignedInteger('item_category_id')->nullable();
                $table->string('item_stored_from')->nullable();
                $table->enum('item_item_type', ['liquid', 'illiquid', 'hybrid'])->default('illiquid');
                $table->boolean('item_is_fixed')->default(false);
                $table->date('item_expiration_date')->nullable();
                $table->date('item_warranty_end')->nullable();
                $table->decimal('item_unit_price', 10, 2)->nullable();
                $table->integer('item_total_quantity')->default(0);
                $table->integer('item_current_stock')->nullable();
                $table->integer('item_max_stock')->nullable();
                $table->enum('item_liquidity_risk_level', ['high', 'medium', 'low'])->default('medium');
                $table->boolean('item_is_collateral')->default(false);
                $table->timestamp('item_created_at')->useCurrent();
                $table->timestamp('item_updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }

        // Categories
        if (!Schema::connection('sws')->hasTable('sws_categories')) {
            Schema::connection('sws')->create('sws_categories', function (Blueprint $table) {
                $table->increments('cat_id');
                $table->string('cat_name', 100)->unique();
                $table->text('cat_description')->nullable();
                $table->timestamp('cat_created_at')->useCurrent();
            });
        }

        // Locations
        if (!Schema::connection('sws')->hasTable('sws_locations')) {
            Schema::connection('sws')->create('sws_locations', function (Blueprint $table) {
                $table->increments('loc_id');
                $table->string('loc_name', 100);
                $table->enum('loc_type', ['warehouse', 'storage_room', 'office', 'facility', 'drop_point', 'bin', 'department', 'room'])->nullable();
                $table->enum('loc_zone_type', ['liquid', 'illiquid', 'climate_controlled', 'general'])->nullable();
                $table->boolean('loc_supports_fixed_items')->default(true);
                $table->integer('loc_capacity')->nullable();
                $table->unsignedInteger('loc_parent_id')->nullable();
                $table->string('loc_department_code', 50)->nullable();
                $table->boolean('loc_is_active')->default(true);
                $table->timestamp('loc_created_at')->useCurrent();
            });
        }

        // Transactions
        if (!Schema::connection('sws')->hasTable('sws_transactions')) {
            Schema::connection('sws')->create('sws_transactions', function (Blueprint $table) {
                $table->increments('tra_id');
                $table->unsignedInteger('tra_item_id');
                $table->enum('tra_type', ['inbound', 'outbound', 'transfer', 'pick_up', 'drop_off', 'adjustment']);
                $table->integer('tra_quantity');
                $table->unsignedInteger('tra_from_location_id')->nullable();
                $table->unsignedInteger('tra_to_location_id')->nullable();
                $table->unsignedInteger('tra_warehouse_id')->nullable();
                $table->timestamp('tra_transaction_date')->useCurrent();
                $table->string('tra_reference_id', 100)->nullable();
                $table->enum('tra_status', ['pending', 'in_transit', 'completed', 'cancelled'])->default('pending');
                $table->text('tra_notes')->nullable();

                $table->foreign('tra_item_id')->references('item_id')->on('sws_items');
                $table->foreign('tra_from_location_id')->references('loc_id')->on('sws_locations');
                $table->foreign('tra_to_location_id')->references('loc_id')->on('sws_locations');
                $table->foreign('tra_warehouse_id')->references('ware_id')->on('sws_warehouses');
            });
        }

        // Transaction logs
        if (!Schema::connection('sws')->hasTable('sws_transaction_logs')) {
            Schema::connection('sws')->create('sws_transaction_logs', function (Blueprint $table) {
                $table->increments('log_id');
                $table->unsignedInteger('log_transaction_id');
                $table->string('log_event', 100)->nullable();
                $table->text('log_details')->nullable();
                $table->timestamp('log_logged_at')->useCurrent();
                $table->string('log_logged_by', 100)->nullable();

                $table->foreign('log_transaction_id')->references('tra_id')->on('sws_transactions');
            });
        }

        // Inventory snapshots
        if (!Schema::connection('sws')->hasTable('sws_inventory_snapshots')) {
            Schema::connection('sws')->create('sws_inventory_snapshots', function (Blueprint $table) {
                $table->increments('snap_id');
                $table->unsignedInteger('snap_item_id');
                $table->unsignedInteger('snap_location_id');
                $table->unsignedInteger('snap_warehouse_id')->nullable();
                $table->integer('snap_current_quantity');
                $table->integer('snap_min_threshold')->default(0);
                $table->enum('snap_alert_level', ['normal', 'low', 'critical'])->default('normal');
                $table->date('snap_snapshot_date');
                $table->string('snap_recorded_by', 100)->nullable();
                $table->text('snap_notes')->nullable();

                $table->foreign('snap_item_id')->references('item_id')->on('sws_items');
                $table->foreign('snap_location_id')->references('loc_id')->on('sws_locations');
                $table->foreign('snap_warehouse_id')->references('ware_id')->on('sws_warehouses');
            });
        }

        // Inventory audits
        if (!Schema::connection('sws')->hasTable('sws_inventory_audits')) {
            Schema::connection('sws')->create('sws_inventory_audits', function (Blueprint $table) {
                $table->increments('aud_id');
                $table->unsignedInteger('aud_item_id')->nullable();
                $table->unsignedInteger('aud_location_id')->nullable();
                $table->unsignedInteger('aud_warehouse_id')->nullable();
                $table->enum('aud_adjustment_type', ['count', 'adjustment', 'expiration_writeoff', 'warranty_check'])->nullable();
                $table->integer('aud_quantity_change')->nullable();
                $table->text('aud_reason')->nullable();
                $table->timestamp('aud_audit_date')->useCurrent();
                $table->string('aud_audited_by', 100)->nullable();

                $table->foreign('aud_item_id')->references('item_id')->on('sws_items');
                $table->foreign('aud_location_id')->references('loc_id')->on('sws_locations');
                $table->foreign('aud_warehouse_id')->references('ware_id')->on('sws_warehouses');
            });
        }

        // Optional: rename old table if present
        if (Schema::connection('sws')->hasTable('sws_warehouse') && !Schema::connection('sws')->hasTable('sws_warehouses')) {
            DB::connection('sws')->statement('RENAME TABLE sws_warehouse TO sws_warehouses');
        }
    }

    public function down(): void
    {
        Schema::connection('sws')->dropIfExists('sws_inventory_audits');
        Schema::connection('sws')->dropIfExists('sws_inventory_snapshots');
        Schema::connection('sws')->dropIfExists('sws_transaction_logs');
        Schema::connection('sws')->dropIfExists('sws_transactions');
        Schema::connection('sws')->dropIfExists('sws_locations');
        Schema::connection('sws')->dropIfExists('sws_categories');
        Schema::connection('sws')->dropIfExists('sws_items');
        Schema::connection('sws')->dropIfExists('sws_warehouses');
    }
};