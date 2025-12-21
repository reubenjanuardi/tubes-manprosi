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
        Schema::table('assessment_responses', function (Blueprint $table) {
            // User who submitted the response
            $table->foreignId('user_id')->nullable()->after('assessment_id')
                  ->constrained('users')->nullOnDelete();
            
            // Response metadata
            $table->enum('response_status', ['draft', 'submitted', 'reviewed', 'approved'])
                  ->default('submitted')
                  ->after('document_path');
            
            $table->timestamp('submitted_at')->nullable()->after('response_status');
            $table->integer('duration_seconds')->nullable()->after('submitted_at');
            $table->string('device_info')->nullable()->after('duration_seconds');
            $table->string('ip_address')->nullable()->after('device_info');
            
            // Review information
            $table->foreignId('reviewed_by')->nullable()->after('ip_address')
                  ->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->text('review_notes')->nullable()->after('reviewed_at');
            
            // Add indexes
            $table->index('user_id');
            $table->index('response_status');
            $table->index('submitted_at');
            $table->index('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_responses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['reviewed_by']);
            
            $table->dropColumn([
                'user_id',
                'response_status',
                'submitted_at',
                'duration_seconds',
                'device_info',
                'ip_address',
                'reviewed_by',
                'reviewed_at',
                'review_notes'
            ]);
        });
    }
};
