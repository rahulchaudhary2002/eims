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
        Schema::create('institution_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        DB::table('institution_types')->insert([
            [
                'name' => 'College',
                'slug' => 'college',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'School',
                'slug' => 'school',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'University',
                'slug' => 'university',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Institute',
                'slug' => 'institute',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Training Center',
                'slug' => 'training-center',
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
        Schema::dropIfExists('institution_types');
    }
};
