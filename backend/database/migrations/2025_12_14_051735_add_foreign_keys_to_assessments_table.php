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
            // Add foreign keys
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->foreignId('organization_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            
            // Add completed_at timestamp
            $table->timestamp('completed_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['organization_id']);
            $table->dropColumn(['user_id', 'organization_id', 'completed_at']);
        });
    }
};
