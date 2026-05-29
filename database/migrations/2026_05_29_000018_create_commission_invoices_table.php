<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admission_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('referral_agreement_id')->nullable()->constrained('referral_agreements')->nullOnDelete();
            $table->decimal('admission_paid_amount', 14, 4)->default(0);
            $table->string('commission_type');
            $table->decimal('commission_value', 10, 4)->default(0);
            $table->decimal('commission_amount', 14, 4)->default(0);
            $table->decimal('student_cashback_amount', 14, 4)->default(0);
            $table->decimal('platform_revenue_amount', 14, 4)->default(0);
            $table->string('status')->default('draft');
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['institution_id', 'status']);
            $table->index(['admission_id']);
            $table->index(['referral_agreement_id']);
            $table->index(['status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_invoices');
    }
};
