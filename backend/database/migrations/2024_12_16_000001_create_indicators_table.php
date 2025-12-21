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
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            
            // Grouping
            $table->string('group_name'); // e.g., "Kebijakan dan Tata Kelola SPBE"
            
            // Indicator text
            $table->text('indicator_text');
            
            // Type: 'scale', 'boolean', 'text'
            $table->enum('type', ['scale', 'boolean', 'text'])->default('scale');
            
            // For scale type - JSON array of values, e.g., [1, 2, 3, 4, 5]
            $table->json('scale_values')->nullable();
            
            // For scale type - JSON array of labels, e.g., ["Initial", "Managed", ...]
            $table->json('scale_labels')->nullable();
            
            // Display order
            $table->integer('display_order')->default(0);
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for performance
            $table->index('group_name');
            $table->index('is_active');
            $table->index('display_order');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};
