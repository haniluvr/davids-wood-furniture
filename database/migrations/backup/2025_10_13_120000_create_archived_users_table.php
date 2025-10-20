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
        Schema::create('archived_users', function (Blueprint $table) {
            $table->id();

            // Store the original user ID
            $table->unsignedBigInteger('original_user_id');

            // User authentication fields
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();

            // Personal information
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone', 11)->nullable();

            // Address fields
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('region')->nullable();
            $table->string('zip_code', 4)->nullable();

            // Newsletter preferences
            $table->boolean('newsletter_product_updates')->default(false);
            $table->boolean('newsletter_special_offers')->default(false);

            // Google OAuth fields
            $table->string('google_id')->nullable();
            $table->string('avatar')->nullable();

            // Archive metadata
            $table->timestamp('archived_at')->nullable();
            $table->string('archive_reason')->nullable();
            $table->text('archive_notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('original_user_id');
            $table->index('email');
            $table->index('archived_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_users');
    }
};
