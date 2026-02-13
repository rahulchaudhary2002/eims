<?php

use App\Models\CourseCategory;
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
        Schema::create('course_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        CourseCategory::insert([
            [
                'name' => 'Management',
                'slug' => 'management',
            ],
            [
                'name' => 'Engineering',
                'slug' => 'engineering',
            ],
            [
                'name' => 'Medical',
                'slug' => 'medical',
            ],
            [
                'name' => 'Law',
                'slug' => 'law',
            ],
            [
                'name' => 'Arts',
                'slug' => 'arts',
            ],
            [
                'name' => 'Science',
                'slug' => 'science',
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
            ],
            [
                'name' => 'Agriculture',
                'slug' => 'agriculture',
            ],
            [
                'name' => 'IT',
                'slug' => 'it',
            ],
            [
                'name' => 'Hotel Management',
                'slug' => 'hotel-management',
            ],
            [
                'name' => 'Architecture',
                'slug' => 'architecture',
            ],
            [
                'name' => 'Pharmacy',
                'slug' => 'pharmacy',
            ],
            [
                'name' => 'Nursing',
                'slug' => 'nursing',
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_categories');
    }
};
