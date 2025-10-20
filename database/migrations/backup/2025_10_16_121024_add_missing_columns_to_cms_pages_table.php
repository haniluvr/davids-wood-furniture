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
        Schema::table('cms_pages', function (Blueprint $table) {
            if (! Schema::hasColumn('cms_pages', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('content');
            }
            if (! Schema::hasColumn('cms_pages', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('excerpt');
            }
            if (! Schema::hasColumn('cms_pages', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (! Schema::hasColumn('cms_pages', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('meta_description');
            }
            if (! Schema::hasColumn('cms_pages', 'type')) {
                $table->enum('type', ['page', 'blog', 'faq', 'policy'])->default('page')->after('meta_keywords');
            }
            if (! Schema::hasColumn('cms_pages', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('type');
            }
            if (! Schema::hasColumn('cms_pages', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->dropColumn([
                'excerpt',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'type',
                'is_active',
                'published_at',
            ]);
        });
    }
};
