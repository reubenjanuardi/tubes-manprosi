<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates sync_tracking table as per PRD Phase 1 specifications
     */
    public function up(): void
    {
        Schema::create('sync_tracking', function (Blueprint $table) {
            $table->id();
            $table->string('component_name', 50)->unique();
            $table->timestamp('last_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->unsignedBigInteger('version_number')->default(1);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->text('change_description')->nullable();
            
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index('component_name');
            $table->index('version_number');
        });

        // Insert initial tracking for indicators component
        DB::table('sync_tracking')->insert([
            'component_name' => 'indicators',
            'last_updated_at' => now(),
            'version_number' => 1,
            'updated_by' => null,
            'change_description' => 'Initial system setup'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_tracking');
    }
};
