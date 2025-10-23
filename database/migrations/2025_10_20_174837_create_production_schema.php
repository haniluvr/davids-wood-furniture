<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cache table
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        // Cache locks table
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // Failed jobs table
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Jobs table
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue');
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        // Job batches table
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        // Migrations table
        Schema::create('migrations', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('migration');
            $table->integer('batch');
        });

        // Password reset tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Admin Permissions
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('permission');
            $table->boolean('granted')->default(true);
            $table->timestamps();
        });

        // Archived Users
        Schema::create('archived_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_user_id');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone', 11)->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('region')->nullable();
            $table->string('zip_code', 4)->nullable();
            $table->boolean('newsletter_product_updates')->default(false);
            $table->boolean('newsletter_special_offers')->default(false);
            $table->string('google_id')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->string('archive_reason')->nullable();
            $table->text('archive_notes')->nullable();
            $table->timestamps();
        });

        // Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_type');
            $table->unsignedBigInteger('user_id');
            $table->string('action');
            $table->string('model')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });


        // Cart Items
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id', 128)->nullable();
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->json('product_data')->nullable();
            $table->timestamps();
        });

        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->integer('category_order')->default(0);
            $table->timestamps();
        });

        // CMS Pages
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->enum('type', ['page', 'blog', 'faq', 'policy'])->default('page');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('featured_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        // Contact Messages
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->text('message');
            $table->enum('status', ['new', 'read', 'responded', 'archived'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Employees
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('role', ['super_admin', 'admin', 'manager', 'staff'])->default('admin');
            $table->json('permissions')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Notifications (Custom table - not Laravel's default notifications)
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'email', 'sms', 'push', 'system'
            $table->string('recipient_type'); // 'admin', 'user', 'all'
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data for the notification
            $table->enum('status', ['pending', 'sent', 'failed', 'read'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->string('channel')->nullable(); // email, sms, etc.
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['recipient_type', 'recipient_id']);
            $table->index('status');
            $table->index('type');
        });

        // Guest Sessions
        Schema::create('guest_sessions', function (Blueprint $table) {
            $table->string('session_id', 128)->primary();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
        });

        // Inventory Movements
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer'])->default('in');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(0);
            $table->integer('previous_stock')->default(0);
            $table->integer('new_stock')->default(0);
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // Order Activities
        Schema::create('order_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('action');
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Order Fulfillment
        Schema::create('order_fulfillment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->boolean('items_packed')->default(false);
            $table->boolean('label_printed')->default(false);
            $table->boolean('shipped')->default(false);
            $table->string('carrier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('packed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->text('packing_notes')->nullable();
            $table->text('shipping_notes')->nullable();
            $table->unsignedBigInteger('packed_by')->nullable();
            $table->unsignedBigInteger('shipped_by')->nullable();
            $table->timestamps();
        });

        // Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->string('product_sku');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->json('product_data')->nullable();
            $table->timestamps();
        });

        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('fulfillment_status', ['pending', 'packed', 'shipped', 'delivered'])->default('pending');
            $table->enum('return_status', ['none', 'requested', 'approved', 'received', 'completed'])->default('none');
            $table->string('rma_number')->nullable();
            $table->string('carrier')->nullable();
            $table->boolean('requires_approval')->default(false);
            $table->text('approval_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('PHP');
            $table->json('billing_address');
            $table->json('shipping_address');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('shipping_method')->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('tracking_number')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });

        // Payment Gateways
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gateway_key')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->json('config')->nullable();
            $table->json('supported_currencies')->nullable();
            $table->json('supported_countries')->nullable();
            $table->decimal('transaction_fee_percentage', 5, 4)->default(0);
            $table->decimal('transaction_fee_fixed', 10, 2)->default(0);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_test_mode')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Payment Methods
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->string('card_type')->nullable();
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

        // Product Popularity
        Schema::create('product_popularity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('sku');
            $table->string('product_name');
            $table->integer('wishlist_count')->default(0);
            $table->integer('cart_count')->default(0);
            $table->integer('total_popularity_score')->default(0);
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();
        });

        // Product Reviews
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id');
            $table->integer('rating');
            $table->string('title')->nullable();
            $table->text('review');
            $table->boolean('is_verified_purchase')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->integer('helpful_count')->default(0);
            $table->text('admin_response')->nullable();
            $table->unsignedBigInteger('responded_by')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });

        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->json('room_category')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('tax_class')->nullable();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->boolean('manage_stock')->default(true);
            $table->boolean('in_stock')->default(true);
            $table->string('weight')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('material')->nullable();
            $table->json('images')->nullable();
            $table->json('gallery')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->integer('sort_order')->default(0);
            $table->json('meta_data')->nullable();
            $table->timestamps();
        });

        // Returns Repairs
        Schema::create('returns_repairs', function (Blueprint $table) {
            $table->id();
            $table->string('rma_number');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['return', 'repair', 'exchange'])->default('return');
            $table->enum('status', ['requested', 'approved', 'received', 'processing', 'repaired', 'refunded', 'completed', 'rejected'])->default('requested');
            $table->text('reason');
            $table->text('description')->nullable();
            $table->json('products');
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->string('refund_method')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('customer_notes')->nullable();
            $table->json('photos')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamps();
        });

        // Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->default('general');
            $table->string('label');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Shipping Methods
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['flat_rate', 'free_shipping', 'weight_based', 'price_based']);
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('free_shipping_threshold', 10, 2)->nullable();
            $table->decimal('minimum_order_amount', 10, 2)->default(0);
            $table->decimal('maximum_order_amount', 10, 2)->nullable();
            $table->json('zones')->nullable();
            $table->json('weight_rates')->nullable();
            $table->integer('estimated_days_min')->nullable();
            $table->integer('estimated_days_max')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->string('region')->nullable();
            $table->string('google_id')->nullable();
            $table->text('avatar')->nullable();
            $table->string('provider')->default('local');
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_suspended')->default(false);
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->boolean('newsletter_product_updates')->default(true);
            $table->boolean('newsletter_special_offers')->default(false);
            $table->boolean('marketing_emails')->default(false);
            $table->boolean('newsletter_subscribed')->default(false);
            $table->timestamps();
        });

        // Wishlist Items
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id', 128)->nullable();
            $table->unsignedBigInteger('product_id');
            $table->timestamp('created_at')->useCurrent();
        });

        // Wishlists
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamps();
        });

        // Add foreign key constraints (excluding references to removed tables)
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('session_id')->references('session_id')->on('guest_sessions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::table('cms_pages', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::table('order_activities', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::table('order_fulfillment', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::table('product_popularity', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('responded_by')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('categories')->onDelete('set null');
        });

        Schema::table('returns_repairs', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Drop tables in reverse order
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('users');
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('returns_repairs');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('product_popularity');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('payment_gateways');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_fulfillment');
        Schema::dropIfExists('order_activities');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('guest_sessions');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('archived_users');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('migrations');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
    }
};