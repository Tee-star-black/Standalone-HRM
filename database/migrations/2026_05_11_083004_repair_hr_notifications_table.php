<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hr_notifications', function (Blueprint $table) {
            if (! Schema::hasColumn('hr_notifications', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            if (! Schema::hasColumn('hr_notifications', 'type')) {
                $table->string('type')->default('info')->after('user_id');
            }

            if (! Schema::hasColumn('hr_notifications', 'title')) {
                $table->string('title')->nullable()->after('type');
            }

            if (! Schema::hasColumn('hr_notifications', 'message')) {
                $table->text('message')->nullable()->after('title');
            }

            if (! Schema::hasColumn('hr_notifications', 'url')) {
                $table->string('url')->nullable()->after('message');
            }

            if (! Schema::hasColumn('hr_notifications', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hr_notifications', function (Blueprint $table) {
            foreach (['user_id', 'type', 'title', 'message', 'url', 'read_at'] as $column) {
                if (Schema::hasColumn('hr_notifications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};