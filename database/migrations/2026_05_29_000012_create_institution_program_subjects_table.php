<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_program_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_program_id')
                ->constrained('institution_programs')
                ->onDelete('cascade');
            $table->string('subject_name');
            $table->boolean('is_optional')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_program_subjects');
    }
};
