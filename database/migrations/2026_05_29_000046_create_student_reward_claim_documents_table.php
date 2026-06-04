<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_reward_claim_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_reward_claim_id')->constrained('student_reward_claims')->cascadeOnDelete();
            $table->string('document_type', 60);
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_note')->nullable();
            $table->timestamps();

            $table->index(['student_reward_claim_id', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_reward_claim_documents');
    }
};
