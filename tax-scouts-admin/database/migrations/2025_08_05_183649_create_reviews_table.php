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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('accountant_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('tax_return_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('overall_rating'); // 1-5
            $table->integer('communication_rating')->nullable(); // 1-5
            $table->integer('expertise_rating')->nullable(); // 1-5
            $table->integer('timeliness_rating')->nullable(); // 1-5
            $table->integer('value_rating')->nullable(); // 1-5
            $table->text('review_text')->nullable();
            $table->text('positive_feedback')->nullable();
            $table->text('improvement_suggestions')->nullable();
            $table->boolean('would_recommend')->default(true);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('moderated_at')->nullable();
            $table->timestamps();
            
            $table->index(['accountant_id', 'is_published']);
            $table->index(['overall_rating', 'is_published']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
