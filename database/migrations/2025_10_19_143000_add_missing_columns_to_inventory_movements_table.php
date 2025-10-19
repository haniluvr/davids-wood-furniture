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
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->after('id');
            $table->integer('quantity')->default(0)->after('product_id');
            $table->integer('previous_stock')->default(0)->after('quantity');
            $table->integer('new_stock')->default(0)->after('previous_stock');
            $table->string('reason')->nullable()->after('new_stock');
            $table->text('notes')->nullable()->after('reason');
            $table->string('reference_type')->nullable()->after('notes');
            $table->unsignedBigInteger('reference_id')->nullable()->after('reference_type');
            $table->unsignedBigInteger('created_by')->nullable()->after('reference_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'quantity', 'previous_stock', 'new_stock', 'reason', 'notes', 'reference_type', 'reference_id', 'created_by']);
        });
    }
};
