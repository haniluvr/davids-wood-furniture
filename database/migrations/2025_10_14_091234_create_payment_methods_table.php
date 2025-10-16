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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'card' or 'gcash'
            $table->string('card_type')->nullable(); // 'visa', 'mastercard', etc.
            $table->string('card_last_four')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->integer('card_expiry_month')->nullable();
            $table->integer('card_expiry_year')->nullable();
            $table->string('gcash_number')->nullable();
            $table->string('gcash_name')->nullable();
            $table->json('billing_address')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
