<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_templates', function (Blueprint $table) {
            if (! Schema::hasColumn('document_templates', 'file_path')) {
                $table->string('file_path')->nullable()->after('content');
            }

            if (! Schema::hasColumn('document_templates', 'file_original_name')) {
                $table->string('file_original_name')->nullable()->after('file_path');
            }

            if (! Schema::hasColumn('document_templates', 'file_mime_type')) {
                $table->string('file_mime_type')->nullable()->after('file_original_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('document_templates', function (Blueprint $table) {
            foreach (['file_path', 'file_original_name', 'file_mime_type'] as $column) {
                if (Schema::hasColumn('document_templates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};