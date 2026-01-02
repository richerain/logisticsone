<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::connection('plt')->hasTable('plt_tracking_logs')) {
            Schema::connection('plt')->create('plt_tracking_logs', function (Blueprint $table) {
                $table->id('track_id');
                $table->foreignId('track_project_id')->constrained('plt_projects', 'pro_id');
                $table->enum('track_log_type', ['milestone_update', 'allocation_change', 'dispatch_event', 'resource_issue']);
                $table->text('track_description');
                $table->string('track_logged_by', 100);
                $table->string('track_reference_id', 100)->nullable();
                $table->timestamps();

                $table->index('track_project_id');
                $table->index('track_log_type');
                $table->index('track_log_date');
            });
        }
    }

    public function down()
    {
        Schema::connection('plt')->dropIfExists('plt_tracking_logs');
    }
};
