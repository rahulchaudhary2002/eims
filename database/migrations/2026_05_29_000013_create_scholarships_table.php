<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->foreignId('institution_program_id')->constrained('institution_programs')->onDelete('cascade');
            $table->string('type', 40);
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('minimum_gpa', 4, 2)->nullable();
            $table->decimal('minimum_percentage', 5, 2)->nullable();
            $table->string('benefit_type', 40);
            $table->decimal('benefit_value', 12, 2)->nullable();
            $table->unsignedInteger('total_slots')->nullable();
            $table->unsignedInteger('used_slots')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamps();

            $table->index(['institution_id', 'status']);
            $table->index(['institution_program_id', 'status']);
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
