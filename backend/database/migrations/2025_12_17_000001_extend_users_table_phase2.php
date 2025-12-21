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
        Schema::table('users', function (Blueprint $table) {
            // Update existing role column to include new values
            // Note: SQLite doesn't support modifying enums directly, so we'll skip role modification
            // Role already exists from previous migration with values: 'user', 'admin'
            
            // Profile information
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('photo')->nullable()->after('address');
            $table->string('organization')->nullable()->after('photo');
            $table->string('position')->nullable()->after('organization');
            
            // Preferences
            $table->json('preferences')->nullable()->after('position');
            
            // Activity tracking
            $table->timestamp('last_login_at')->nullable()->after('preferences');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->text('last_login_device')->nullable()->after('last_login_ip');
            
            // Security
            $table->boolean('is_active')->default(true)->after('last_login_device');
            $table->boolean('two_factor_enabled')->default(false)->after('is_active');
            $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');
            
            // Add indexes
            $table->index('role');
            $table->index('is_active');
            $table->index('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'photo',
                'organization',
                'position',
                'preferences',
                'last_login_at',
                'last_login_ip',
                'last_login_device',
                'is_active',
                'two_factor_enabled',
                'two_factor_secret'
            ]);
        });
    }
};
