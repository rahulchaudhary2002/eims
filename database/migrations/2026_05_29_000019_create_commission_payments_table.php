<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commission_invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 14, 4);
            $table->string('payment_method');
            $table->string('transaction_reference')->nullable();
            $table->string('payment_proof')->nullable();
            $table->date('payment_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['commission_invoice_id']);
            $table->index(['payment_method', 'payment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_payments');
    }
};
