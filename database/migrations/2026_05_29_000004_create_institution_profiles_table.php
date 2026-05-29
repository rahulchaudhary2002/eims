<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->json('facilities')->nullable();
            $table->json('infrastructure')->nullable();
            $table->json('achievements')->nullable();
            $table->json('accreditations')->nullable();
            $table->boolean('has_hostel')->default(false);
            $table->boolean('has_transportation')->default(false);
            $table->boolean('has_library')->default(false);
            $table->boolean('has_lab')->default(false);
            $table->boolean('has_cafeteria')->default(false);
            $table->boolean('has_sports')->default(false);
            $table->boolean('has_scholarship')->default(false);
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->timestamps();

            $table->unique('institution_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_profiles');
    }
};
