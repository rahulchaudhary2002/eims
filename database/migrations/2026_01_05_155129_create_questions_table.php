<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('category', [
                'academics',
                'admissions',
                'campus_life',
                'colleges',
                'entrance_exams',
                'events',
                'exams',
                'general',
                'programs',
                'scholarships',
            ])->index();

            $table->string('title');
            $table->text('body');

            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_draft')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('replies_count')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
