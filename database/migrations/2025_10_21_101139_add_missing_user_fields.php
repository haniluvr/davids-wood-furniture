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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'marketing_emails')) {
                $table->boolean('marketing_emails')->default(false);
            }
            if (! Schema::hasColumn('users', 'newsletter_subscribed')) {
                $table->boolean('newsletter_subscribed')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['newsletter_subscribed', 'marketing_emails']);
        });
    }
};
