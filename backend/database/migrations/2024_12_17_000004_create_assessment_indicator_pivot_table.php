<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create assessment_indicator_pivot table as per PRD Phase 2
     * This enables many-to-many relationship between assessments and indicators
     */
    public function up(): void
    {
        Schema::create('assessment_indicator_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('indicator_id');
            $table->integer('display_order_in_assessment')->default(0);
            $table->boolean('is_active_in_assessment')->default(true);
            $table->decimal('custom_weight', 5, 2)->nullable()->comment('Custom weight for this indicator in this assessment');
            $table->timestamp('created_at')->useCurrent();
            
            // Primary key (composite)
            $table->primary(['assessment_id', 'indicator_id'], 'assessment_indicator_primary');
            
            // Foreign keys
            $table->foreign('assessment_id')
                ->references('id')
                ->on('assessments')
                ->onDelete('cascade');
                
            $table->foreign('indicator_id')
                ->references('id')
                ->on('indicators')
                ->onDelete('cascade');
            
            // Indexes for performance
            $table->index('assessment_id');
            $table->index('indicator_id');
            $table->index('display_order_in_assessment');
            $table->index('is_active_in_assessment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_indicator_pivot');
    }
};
