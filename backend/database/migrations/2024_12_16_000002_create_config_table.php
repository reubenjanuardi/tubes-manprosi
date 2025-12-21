<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('config', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->timestamps();
        });
        
        // Insert initial indicator version tracking
        DB::table('config')->insert([
            'key' => 'indicator_version',
            'value' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('config')->insert([
            'key' => 'indicator_last_updated',
            'value' => now()->toIso8601String(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};
