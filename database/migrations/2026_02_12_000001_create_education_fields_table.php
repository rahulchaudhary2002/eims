<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('education_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        DB::table('education_fields')->insert([
            [
                'name' => 'Science',
                'slug' => 'science',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Management',
                'slug' => 'management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Humanities',
                'slug' => 'humanities',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Engineering',
                'slug' => 'engineering',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Medicine',
                'slug' => 'medicine',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Arts',
                'slug' => 'arts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Law',
                'slug' => 'law',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_fields');
    }
};
