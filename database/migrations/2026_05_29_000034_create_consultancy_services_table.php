<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultancy_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('service_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('service_fee', 10, 4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['institution_id', 'is_active']);
            $table->index(['service_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultancy_services');
    }
};
