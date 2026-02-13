<?php

use App\Models\InstitutionCategory;
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
        Schema::create('institution_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        InstitutionCategory::insert([
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
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_categories');
    }
};
