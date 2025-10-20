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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gateway_key')->unique(); // stripe, paypal, etc.
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->json('config')->nullable(); // Encrypted configuration (API keys, etc.)
            $table->json('supported_currencies')->nullable();
            $table->json('supported_countries')->nullable();
            $table->decimal('transaction_fee_percentage', 5, 4)->default(0);
            $table->decimal('transaction_fee_fixed', 10, 2)->default(0);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_test_mode')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
