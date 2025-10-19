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
        Schema::table('contact_messages', function (Blueprint $table) {
            // Update status enum if it doesn't match
            $table->enum('status', ['new', 'read', 'responded'])->default('new')->change();
            $table->text('internal_notes')->nullable()->after('status');
            $table->json('tags')->nullable()->after('internal_notes');
            $table->foreignId('assigned_to')->nullable()->constrained('employees')->onDelete('set null')->after('tags');
            $table->timestamp('responded_at')->nullable()->after('read_at');
            $table->foreignId('responded_by')->nullable()->constrained('employees')->onDelete('set null')->after('responded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropForeign(['responded_by']);
            $table->dropColumn([
                'status',
                'internal_notes',
                'tags',
                'assigned_to',
                'read_at',
                'responded_at',
                'responded_by'
            ]);
        });
    }
};
