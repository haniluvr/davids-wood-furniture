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
        // First, add 'staff' to the enum while keeping 'employee'
        Schema::table('admins', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'manager', 'employee', 'staff'])->default('employee')->change();
        });
        
        // Update existing 'employee' records to 'staff'
        DB::table('admins')->where('role', 'employee')->update(['role' => 'staff']);
        
        // Finally, remove 'employee' from the enum
        Schema::table('admins', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'manager', 'staff'])->default('staff')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update existing 'staff' records back to 'employee'
        DB::table('admins')->where('role', 'staff')->update(['role' => 'employee']);
        
        // Revert the enum back to 'employee'
        Schema::table('admins', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'manager', 'employee'])->default('employee')->change();
        });
    }
};