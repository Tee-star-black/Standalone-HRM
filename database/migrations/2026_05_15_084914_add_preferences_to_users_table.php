<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'theme')) {
                $table->string('theme')->default('light')->after('email');
            }

            if (! Schema::hasColumn('users', 'timezone')) {
                $table->string('timezone')->default('Africa/Johannesburg')->after('theme');
            }

            if (! Schema::hasColumn('users', 'date_format')) {
                $table->string('date_format')->default('d M Y')->after('timezone');
            }

            if (! Schema::hasColumn('users', 'email_notifications')) {
                $table->boolean('email_notifications')->default(true)->after('date_format');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['theme', 'timezone', 'date_format', 'email_notifications'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};