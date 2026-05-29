<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_academic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('level');                          // SLC/SEE, +2/A-Level, Bachelor, Master, etc.
            $table->string('institution_name')->nullable();
            $table->string('board')->nullable();              // NEB, CTEVT, TU, KU, PU, etc.
            $table->string('faculty')->nullable();            // Science, Management, Humanities, etc.
            $table->unsignedSmallInteger('passed_year')->nullable();
            $table->decimal('gpa', 4, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('symbol_number')->nullable();
            $table->string('transcript_file')->nullable();
            $table->string('character_certificate_file')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_academic_records');
    }
};
