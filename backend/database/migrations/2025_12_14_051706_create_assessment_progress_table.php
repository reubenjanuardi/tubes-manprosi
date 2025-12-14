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
        Schema::create('assessment_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->uuid('assessment_id')->nullable();
            $table->json('progress_data'); // Store form state, responses, etc.
            $table->timestamp('saved_at')->useCurrent();
            $table->timestamps();

            // Optional: Add foreign key to assessments if needed
            // $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_progress');
    }
};
