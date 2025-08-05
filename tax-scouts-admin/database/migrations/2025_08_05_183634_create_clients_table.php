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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('national_insurance_number')->nullable();
            $table->string('utr_number')->nullable(); // Unique Taxpayer Reference
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->default('UK');
            $table->enum('client_type', ['individual', 'self_employed', 'landlord', 'business', 'high_earner'])->default('individual');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->decimal('annual_income', 12, 2)->nullable();
            $table->json('tax_years')->nullable(); // Array of tax years client needs help with
            $table->text('notes')->nullable();
            $table->foreignId('assigned_accountant_id')->nullable()->constrained('accountants')->onDelete('set null');
            $table->timestamp('last_contact')->nullable();
            $table->timestamps();
            
            $table->index(['email', 'status']);
            $table->index(['client_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
