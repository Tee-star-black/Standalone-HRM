<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreignId('converted_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamp('converted_at')->nullable();
            $table->timestamp('offer_letter_generated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['converted_employee_id']);
            $table->dropColumn([
                'converted_employee_id',
                'converted_at',
                'offer_letter_generated_at',
            ]);
        });
    }
};