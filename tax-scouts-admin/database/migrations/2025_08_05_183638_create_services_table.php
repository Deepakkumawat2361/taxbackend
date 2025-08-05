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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('detailed_description')->nullable();
            $table->enum('category', ['tax_returns', 'tax_advice', 'business_planning', 'compliance', 'consultation']);
            $table->decimal('base_price', 8, 2);
            $table->decimal('premium_price', 8, 2)->nullable();
            $table->json('features'); // Array of service features
            $table->json('suitable_for'); // Array of client types
            $table->integer('estimated_duration_hours')->nullable();
            $table->boolean('requires_consultation')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('icon')->nullable();
            $table->string('banner_image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->json('required_documents')->nullable(); // Array of required documents
            $table->text('process_steps')->nullable();
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
            $table->index(['is_featured', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
