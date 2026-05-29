<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_program_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->json('reasons')->nullable();
            $table->boolean('is_viewed')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'is_viewed']);
            $table->index(['institution_id']);
            $table->index(['student_id', 'score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_recommendations');
    }
};
