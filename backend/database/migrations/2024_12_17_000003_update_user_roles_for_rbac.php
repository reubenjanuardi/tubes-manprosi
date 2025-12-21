<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update role enum to support PRD Phase 2 requirements:
     * super_admin, admin, viewer, auditor
     */
    public function up(): void
    {
        // For MySQL, we need to alter the enum
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin', 'super_admin', 'viewer', 'auditor') DEFAULT 'user'");
        }
        // For SQLite, enum is not strictly enforced, so we just document the valid values
        // The validation is handled at application level
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') DEFAULT 'user'");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
            
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['user', 'admin'])->default('user')->after('password');
            });
        }
    }
};
