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
        Schema::rename('contact_messages', 'contacts');
        
        // Optionally, rename columns to match requirement
        Schema::table('contacts', function (Blueprint $table) {
            $table->renameColumn('institution', 'organization_name');
            $table->renameColumn('fullname', 'full_name');
            $table->renameColumn('service_type', 'service');
            $table->timestamp('submitted_at')->useCurrent()->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->renameColumn('organization_name', 'institution');
            $table->renameColumn('full_name', 'fullname');
            $table->renameColumn('service', 'service_type');
            $table->dropColumn('submitted_at');
        });
        
        Schema::rename('contacts', 'contact_messages');
    }
};
