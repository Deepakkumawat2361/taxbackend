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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('tax_return_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('original_filename');
            $table->string('file_path');
            $table->string('mime_type');
            $table->bigInteger('file_size'); // in bytes
            $table->enum('document_type', ['p60', 'p45', 'bank_statement', 'invoice', 'receipt', 'property_statement', 'dividend_voucher', 'pension_statement', 'other']);
            $table->string('tax_year')->nullable();
            $table->enum('status', ['pending_review', 'approved', 'rejected', 'requires_clarification'])->default('pending_review');
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('accountants')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->boolean('is_sensitive')->default(true);
            $table->json('extracted_data')->nullable(); // OCR or manual data extraction
            $table->timestamps();
            
            $table->index(['client_id', 'document_type']);
            $table->index(['status', 'tax_year']);
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
