<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('username')->nullable()->after('last_name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('region')->nullable()->after('phone');
            $table->string('google_id')->nullable()->after('region');
            $table->text('avatar')->nullable()->after('google_id');
            $table->string('provider')->default('local')->after('avatar');
            $table->string('street')->nullable()->after('provider');
            $table->string('barangay')->nullable()->after('street');
            $table->string('city')->nullable()->after('barangay');
            $table->string('province')->nullable()->after('city');
            $table->string('zip_code')->nullable()->after('province');
            $table->boolean('is_suspended')->default(false)->after('email_verified_at');
            $table->boolean('newsletter_product_updates')->default(true)->after('remember_token');
            $table->boolean('newsletter_special_offers')->default(false)->after('newsletter_product_updates');
            $table->boolean('marketing_emails')->default(false)->after('newsletter_special_offers');
            $table->boolean('newsletter_subscribed')->default(false)->after('marketing_emails');
            $table->boolean('two_factor_enabled')->default(false)->after('newsletter_subscribed');
            $table->timestamp('two_factor_verified_at')->nullable()->after('two_factor_enabled');

            // Remove the name column
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Re-add the name column
            $table->string('name')->after('id');

            // Drop the new columns
            $table->dropColumn([
                'first_name', 'last_name', 'username', 'phone', 'region',
                'google_id', 'avatar', 'provider', 'street', 'barangay',
                'city', 'province', 'zip_code', 'is_suspended',
                'newsletter_product_updates', 'newsletter_special_offers',
                'marketing_emails', 'newsletter_subscribed',
                'two_factor_enabled', 'two_factor_verified_at',
            ]);
        });
    }
};
