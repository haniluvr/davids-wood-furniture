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
        Schema::table('products', function (Blueprint $table) {
            // Add admin-specific fields if they don't exist
            if (! Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->nullable()->after('price');
            }
            if (! Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('cost_price');
            }
            if (! Schema::hasColumn('products', 'weight')) {
                $table->decimal('weight', 8, 2)->nullable()->after('sale_price');
            }
            if (! Schema::hasColumn('products', 'dimensions')) {
                $table->string('dimensions')->nullable()->after('weight');
            }
            if (! Schema::hasColumn('products', 'barcode')) {
                $table->string('barcode')->nullable()->after('sku');
            }
            if (! Schema::hasColumn('products', 'tax_class')) {
                $table->string('tax_class')->nullable()->after('dimensions');
            }
            if (! Schema::hasColumn('products', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('is_active');
            }
            if (! Schema::hasColumn('products', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'cost_price',
                'sale_price',
                'weight',
                'dimensions',
                'barcode',
                'tax_class',
                'created_by',
                'updated_by',
            ]);
        });
    }
};
