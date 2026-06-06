<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_documents', function (Blueprint $table) {
            $table->foreignId('academic_record_id')
                ->nullable()
                ->after('student_id')
                ->constrained('student_academic_records')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('student_documents', function (Blueprint $table) {
            $table->dropForeign(['academic_record_id']);
            $table->dropColumn('academic_record_id');
        });
    }
};
