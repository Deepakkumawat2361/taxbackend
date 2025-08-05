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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('accountant_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['initial', 'follow_up', 'tax_planning', 'review', 'urgent']);
            $table->enum('method', ['phone', 'video', 'in_person', 'email']);
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->datetime('scheduled_at');
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->text('agenda')->nullable();
            $table->text('notes')->nullable();
            $table->text('action_items')->nullable();
            $table->text('client_questions')->nullable();
            $table->json('documents_discussed')->nullable();
            $table->decimal('fee', 8, 2)->nullable();
            $table->boolean('follow_up_required')->default(false);
            $table->date('next_consultation_date')->nullable();
            $table->integer('client_satisfaction')->nullable(); // 1-5 rating
            $table->text('client_feedback')->nullable();
            $table->timestamps();
            
            $table->index(['client_id', 'scheduled_at']);
            $table->index(['accountant_id', 'status']);
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
