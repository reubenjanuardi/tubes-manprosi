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
        Schema::create('assessment_indicators', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->uuid('assessment_id');
            $table->foreignId('indicator_id')->constrained('indicators')->cascadeOnDelete();
            
            // Configuration per assessment
            $table->integer('display_order')->default(0);
            $table->decimal('weight', 5, 2)->default(1.00); // Weight for scoring
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            
            // Custom configuration for this assessment
            $table->json('custom_config')->nullable();
            
            $table->timestamps();
            
            // Foreign key
            $table->foreign('assessment_id')->references('id')->on('assessments')->cascadeOnDelete();
            
            // Unique constraint
            $table->unique(['assessment_id', 'indicator_id']);
            
            // Indexes
            $table->index('assessment_id');
            $table->index('indicator_id');
            $table->index('display_order');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_indicators');
    }
};
