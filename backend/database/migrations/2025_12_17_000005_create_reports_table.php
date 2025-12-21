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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            
            // Report metadata
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['assessment', 'response', 'analytics', 'custom'])->default('assessment');
            $table->enum('format', ['pdf', 'excel', 'csv', 'json'])->default('pdf');
            
            // Creator
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            
            // Parameters and filters
            $table->json('parameters')->nullable();
            $table->json('filters')->nullable();
            
            // Schedule
            $table->boolean('is_scheduled')->default(false);
            $table->string('schedule_frequency')->nullable(); // daily, weekly, monthly
            $table->json('schedule_config')->nullable();
            $table->timestamp('last_generated_at')->nullable();
            $table->timestamp('next_generation_at')->nullable();
            
            // Generated report
            $table->string('file_path')->nullable();
            $table->integer('file_size')->nullable(); // in bytes
            $table->enum('status', ['pending', 'generating', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            
            // Email recipients
            $table->json('email_recipients')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('created_by');
            $table->index('type');
            $table->index('status');
            $table->index('is_scheduled');
            $table->index('next_generation_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
