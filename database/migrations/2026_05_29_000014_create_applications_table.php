<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            $table->nullableMorphs('applicable'); // InstitutionProgram, ConsultancyService, InstitutionCourse, InstitutionCertification
            $table->foreignId('scholarship_id')->nullable()->constrained('scholarships')->nullOnDelete();
            $table->string('scholarship_status', 30)->nullable();
            $table->decimal('scholarship_approved_amount', 14, 4)->nullable();
            $table->text('scholarship_remarks')->nullable();
            $table->string('source', 40);
            $table->string('status', 30)->default('draft');
            $table->text('student_message')->nullable();
            $table->text('institution_remarks')->nullable();
            $table->text('admin_remarks')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('referred_at')->nullable();
            $table->timestamp('admitted_at')->nullable();
            $table->text('student_note')->nullable();
            $table->text('platform_review_note')->nullable();
            $table->foreignId('platform_reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('platform_reviewed_at')->nullable();
            $table->timestamp('more_info_requested_at')->nullable();
            $table->timestamp('approved_for_referral_at')->nullable();
            $table->timestamp('institution_rejected_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['institution_id', 'status']);
            $table->index(['student_id', 'status']);
            $table->index(['source', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
