<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_reward_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->foreignId('institution_program_id')->nullable()->constrained('institution_programs')->nullOnDelete();
            $table->foreignId('application_id')->nullable()->unique()->constrained('applications')->nullOnDelete();
            $table->foreignId('referral_id')->nullable()->constrained('referrals')->nullOnDelete();
            $table->unsignedBigInteger('admission_id')->nullable();
            $table->string('claim_number')->unique();
            $table->string('status', 40)->default('submitted');
            $table->date('admission_date')->nullable();
            $table->string('admission_number')->nullable();
            $table->string('intake')->nullable();
            $table->decimal('claimed_reward_amount', 12, 2)->default(0);
            $table->decimal('approved_reward_amount', 12, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->json('payment_details')->nullable();
            $table->text('student_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'status']);
            $table->index(['institution_id', 'status']);
            $table->index('referral_id');
            $table->index('admission_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_reward_claims');
    }
};
