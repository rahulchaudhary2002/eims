<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('institution_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->decimal('admission_fee', 12, 2)->nullable();
            $table->decimal('monthly_fee', 12, 2)->nullable();
            $table->decimal('semester_fee', 12, 2)->nullable();
            $table->decimal('annual_fee', 12, 2)->nullable();
            $table->decimal('total_fee', 12, 2)->nullable();
            $table->unsignedSmallInteger('duration_months')->nullable();
            $table->unsignedSmallInteger('total_seats')->nullable();
            $table->unsignedSmallInteger('available_seats')->nullable();
            $table->decimal('minimum_gpa', 4, 2)->nullable();
            $table->decimal('minimum_percentage', 5, 2)->nullable();
            $table->date('admission_start_date')->nullable();
            $table->date('admission_end_date')->nullable();
            $table->string('status', 20)->default('closed');

            $table->unique(['institution_id', 'program_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_programs');
    }
};
