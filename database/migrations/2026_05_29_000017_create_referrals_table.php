<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('referral_number')->unique();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referred_by')->constrained('users')->cascadeOnDelete();
            $table->nullableMorphs('applicable'); // InstitutionProgram, ConsultancyService, InstitutionCourse, InstitutionCertification
            $table->foreignId('referral_agreement_id')->nullable()->constrained('referral_agreements')->nullOnDelete();
            $table->string('status', 40)->default('sent');
            $table->timestamp('referred_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->boolean('is_profile_unlocked')->default(false);
            $table->timestamp('profile_unlocked_at')->nullable();
            $table->foreignId('profile_unlocked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('agreement_accepted_at')->nullable();
            $table->date('protection_starts_at')->nullable();
            $table->date('protection_expires_at')->nullable();
            $table->text('institution_response_note')->nullable();
            $table->text('platform_note')->nullable();
            $table->string('unlock_ip')->nullable();
            $table->text('unlock_user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['institution_id', 'status']);
            $table->index(['student_id', 'status']);
            $table->index(['application_id']);
            $table->index(['referred_by']);
            $table->index('is_profile_unlocked');
            $table->index('protection_expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
