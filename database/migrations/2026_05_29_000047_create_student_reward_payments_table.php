<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_reward_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_reward_claim_id')->constrained('student_reward_claims')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 40);
            $table->string('transaction_reference')->nullable();
            $table->string('status', 30)->default('pending');
            $table->json('payment_details')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index('student_reward_claim_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_reward_payments');
    }
};
