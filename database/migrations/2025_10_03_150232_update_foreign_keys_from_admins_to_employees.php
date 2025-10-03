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
        // Update foreign key constraints that reference 'admins' to 'employees'
        
        // Update cms_pages table foreign keys
        if (Schema::hasTable('cms_pages')) {
            Schema::table('cms_pages', function (Blueprint $table) {
                // Drop existing foreign key constraints
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
            });
            
            Schema::table('cms_pages', function (Blueprint $table) {
                // Add new foreign key constraints pointing to employees table
                $table->foreign('created_by')->references('id')->on('employees');
                $table->foreign('updated_by')->references('id')->on('employees');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert foreign key constraints back to 'admins'
        
        // Update cms_pages table foreign keys
        if (Schema::hasTable('cms_pages')) {
            Schema::table('cms_pages', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
            });
            
            Schema::table('cms_pages', function (Blueprint $table) {
                $table->foreign('created_by')->references('id')->on('admins');
                $table->foreign('updated_by')->references('id')->on('admins');
            });
        }
    }
};