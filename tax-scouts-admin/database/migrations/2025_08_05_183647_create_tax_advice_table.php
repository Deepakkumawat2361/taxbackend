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
        Schema::create('tax_advice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('accountant_id')->constrained()->onDelete('cascade');
            $table->foreignId('consultation_id')->nullable()->constrained()->onDelete('set null');
            $table->string('advice_reference')->unique();
            $table->enum('category', ['tax_planning', 'compliance', 'optimization', 'relief_claims', 'dispute_resolution', 'general']);
            $table->string('subject');
            $table->text('client_question');
            $table->longText('advice_given');
            $table->longText('recommendations')->nullable();
            $table->json('action_items')->nullable();
            $table->json('relevant_legislation')->nullable(); // Tax codes, regulations
            $table->json('supporting_documents')->nullable();
            $table->decimal('potential_savings', 10, 2)->nullable();
            $table->date('implementation_deadline')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['draft', 'reviewed', 'sent', 'implemented', 'archived'])->default('draft');
            $table->boolean('requires_follow_up')->default(false);
            $table->date('follow_up_date')->nullable();
            $table->text('follow_up_notes')->nullable();
            $table->integer('client_rating')->nullable(); // 1-5 rating
            $table->text('client_feedback')->nullable();
            $table->timestamps();
            
            $table->index(['client_id', 'category']);
            $table->index(['accountant_id', 'status']);
            $table->index(['advice_reference']);
            $table->index(['priority', 'implementation_deadline']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_advice');
    }
};
