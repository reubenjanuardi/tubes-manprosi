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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // User who performed the action
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Action details
            $table->string('action'); // e.g., 'created', 'updated', 'deleted', 'viewed'
            $table->string('entity_type'); // e.g., 'assessment', 'indicator', 'user'
            $table->string('entity_id')->nullable();
            $table->text('description')->nullable();
            
            // Data
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            
            // Request metadata
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('method')->nullable(); // GET, POST, PUT, DELETE
            $table->string('url')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('action');
            $table->index('entity_type');
            $table->index('entity_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
