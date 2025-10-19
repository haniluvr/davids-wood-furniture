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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('fulfillment_status', ['pending', 'packed', 'shipped', 'delivered'])->default('pending')->after('status');
            $table->enum('return_status', ['none', 'requested', 'approved', 'received', 'completed'])->default('none')->after('fulfillment_status');
            $table->string('rma_number')->nullable()->after('return_status');
            $table->string('carrier')->nullable()->after('tracking_number');
            $table->boolean('requires_approval')->default(false)->after('carrier');
            $table->text('approval_reason')->nullable()->after('requires_approval');
            $table->timestamp('approved_at')->nullable()->after('approval_reason');
            $table->foreignId('approved_by')->nullable()->constrained('employees')->onDelete('set null')->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'fulfillment_status',
                'return_status', 
                'rma_number',
                'carrier',
                'requires_approval',
                'approval_reason',
                'approved_at',
                'approved_by'
            ]);
        });
    }
};
