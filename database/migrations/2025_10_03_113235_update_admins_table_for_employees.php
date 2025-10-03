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
        Schema::table('admins', function (Blueprint $table) {
            // Update role enum to include employee roles
            $table->enum('role', ['super_admin', 'admin', 'manager', 'employee'])->default('employee')->change();
            
            // Add employee-specific fields
            $table->string('employee_id')->unique()->after('id');
            $table->string('department')->nullable()->after('role');
            $table->string('position')->nullable()->after('department');
            $table->date('hire_date')->nullable()->after('position');
            $table->decimal('salary', 10, 2)->nullable()->after('hire_date');
            $table->enum('employment_status', ['active', 'inactive', 'terminated', 'on_leave'])->default('active')->after('salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Revert role enum
            $table->enum('role', ['super_admin', 'admin', 'manager', 'staff'])->default('admin')->change();
            
            // Remove employee-specific fields
            $table->dropColumn([
                'employee_id',
                'department',
                'position',
                'hire_date',
                'salary',
                'employment_status'
            ]);
        });
    }
};