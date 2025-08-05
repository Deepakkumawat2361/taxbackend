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
        Schema::create('tax_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('accountant_id')->constrained()->onDelete('cascade');
            $table->string('tax_year'); // e.g., '2023-24'
            $table->enum('return_type', ['self_assessment', 'corporation_tax', 'vat_return', 'paye']);
            $table->enum('status', ['pending', 'in_progress', 'review', 'completed', 'filed', 'rejected'])->default('pending');
            $table->decimal('total_income', 12, 2)->nullable();
            $table->decimal('total_expenses', 12, 2)->nullable();
            $table->decimal('taxable_income', 12, 2)->nullable();
            $table->decimal('tax_due', 12, 2)->nullable();
            $table->decimal('tax_paid', 12, 2)->nullable();
            $table->decimal('refund_due', 12, 2)->nullable();
            $table->date('deadline');
            $table->date('submitted_date')->nullable();
            $table->date('filed_date')->nullable();
            $table->string('hmrc_reference')->nullable();
            $table->json('income_sources')->nullable(); // Employment, self-employment, rental, etc.
            $table->json('deductions')->nullable();
            $table->json('reliefs_claimed')->nullable();
            $table->text('notes')->nullable();
            $table->text('hmrc_response')->nullable();
            $table->boolean('amendments_required')->default(false);
            $table->timestamps();
            
            $table->index(['client_id', 'tax_year']);
            $table->index(['status', 'deadline']);
            $table->index('return_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_returns');
    }
};
