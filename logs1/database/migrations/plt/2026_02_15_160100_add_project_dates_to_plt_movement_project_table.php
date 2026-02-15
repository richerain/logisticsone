<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('plt')->table('plt_movement_project', function (Blueprint $table) {
            if (!Schema::connection('plt')->hasColumn('plt_movement_project', 'mp_project_start')) {
                $table->date('mp_project_start')->nullable()->after('mp_status');
            }
            if (!Schema::connection('plt')->hasColumn('plt_movement_project', 'mp_project_end')) {
                $table->date('mp_project_end')->nullable()->after('mp_project_start');
            }
        });
    }

    public function down()
    {
        Schema::connection('plt')->table('plt_movement_project', function (Blueprint $table) {
            if (Schema::connection('plt')->hasColumn('plt_movement_project', 'mp_project_end')) {
                $table->dropColumn('mp_project_end');
            }
            if (Schema::connection('plt')->hasColumn('plt_movement_project', 'mp_project_start')) {
                $table->dropColumn('mp_project_start');
            }
        });
    }
};
