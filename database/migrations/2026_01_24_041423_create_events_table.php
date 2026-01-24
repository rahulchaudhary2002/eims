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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institution_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->longText('description')->nullable();
            $table->timestamps();

            $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
