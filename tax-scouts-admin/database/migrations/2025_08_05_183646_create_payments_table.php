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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('tax_return_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('consultation_id')->nullable()->constrained()->onDelete('set null');
            $table->string('payment_reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0.00); // VAT
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('GBP');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['card', 'bank_transfer', 'paypal', 'stripe', 'cash', 'cheque']);
            $table->string('payment_gateway')->nullable(); // stripe, paypal, etc.
            $table->string('gateway_transaction_id')->nullable();
            $table->json('gateway_response')->nullable();
            $table->text('description')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->datetime('refunded_at')->nullable();
            $table->decimal('refunded_amount', 10, 2)->nullable();
            $table->text('refund_reason')->nullable();
            $table->string('invoice_number')->nullable();
            $table->boolean('invoice_sent')->default(false);
            $table->datetime('invoice_sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['client_id', 'status']);
            $table->index(['payment_reference']);
            $table->index(['status', 'paid_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
