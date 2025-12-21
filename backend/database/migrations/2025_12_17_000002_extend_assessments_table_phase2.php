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
        Schema::table('assessments', function (Blueprint $table) {
            // Assessment metadata
            $table->string('name')->default('Untitled Assessment')->after('id');
            $table->text('description')->nullable()->after('name');
            
            // Period and status
            $table->date('start_date')->nullable()->after('assessment_date');
            $table->date('end_date')->nullable()->after('start_date');
            $table->enum('assessment_status', ['draft', 'active', 'completed', 'archived'])
                  ->default('draft')
                  ->after('status');
            
            // Owner/administrator
            $table->foreignId('created_by')->nullable()->after('assessment_status')
                  ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')
                  ->constrained('users')->nullOnDelete();
            
            // Configuration
            $table->json('indicator_config')->nullable()->after('updated_by');
            $table->json('settings')->nullable()->after('indicator_config');
            
            // Statistics
            $table->integer('total_participants')->default(0)->after('settings');
            $table->integer('completed_responses')->default(0)->after('total_participants');
            $table->decimal('completion_rate', 5, 2)->default(0)->after('completed_responses');
            
            // Template flag
            $table->boolean('is_template')->default(false)->after('completion_rate');
            
            // Add indexes
            $table->index('assessment_status');
            $table->index('start_date');
            $table->index('end_date');
            $table->index('created_by');
            $table->index('is_template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            $table->dropColumn([
                'name',
                'description',
                'start_date',
                'end_date',
                'assessment_status',
                'created_by',
                'updated_by',
                'indicator_config',
                'settings',
                'total_participants',
                'completed_responses',
                'completion_rate',
                'is_template'
            ]);
        });
    }
};
