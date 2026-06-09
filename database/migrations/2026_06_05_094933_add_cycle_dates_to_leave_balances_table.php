<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            if (! Schema::hasColumn('leave_balances', 'cycle_start')) {
                $table->date('cycle_start')->nullable()->after('year');
            }

            if (! Schema::hasColumn('leave_balances', 'cycle_end')) {
                $table->date('cycle_end')->nullable()->after('cycle_start');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            if (Schema::hasColumn('leave_balances', 'cycle_start')) {
                $table->dropColumn('cycle_start');
            }

            if (Schema::hasColumn('leave_balances', 'cycle_end')) {
                $table->dropColumn('cycle_end');
            }
        });
    }
};