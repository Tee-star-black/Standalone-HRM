<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            if (! Schema::hasColumn('leave_types', 'cycle_months')) {
                $table->integer('cycle_months')->default(12);
            }

            if (! Schema::hasColumn('leave_types', 'requires_document')) {
                $table->boolean('requires_document')->default(false);
            }

            if (! Schema::hasColumn('leave_types', 'document_required_after_days')) {
                $table->decimal('document_required_after_days', 8, 2)->nullable();
            }

            if (! Schema::hasColumn('leave_types', 'allow_negative_balance')) {
                $table->boolean('allow_negative_balance')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            foreach ([
                'cycle_months',
                'requires_document',
                'document_required_after_days',
                'allow_negative_balance',
            ] as $column) {
                if (Schema::hasColumn('leave_types', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};