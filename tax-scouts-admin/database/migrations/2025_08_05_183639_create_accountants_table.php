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
        Schema::create('accountants', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('qualification'); // e.g., ACA, ACCA, CTA
            $table->integer('years_experience');
            $table->json('specializations'); // Array of specializations
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->integer('max_clients')->default(50);
            $table->integer('current_clients')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->json('working_hours')->nullable(); // JSON object for schedule
            $table->boolean('available_for_new_clients')->default(true);
            $table->timestamps();
            
            $table->index(['status', 'available_for_new_clients']);
            $table->index('specializations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountants');
    }
};
