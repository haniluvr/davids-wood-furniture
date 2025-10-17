@extends('admin.layouts.app')

@section('title', 'Create CMS Page')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Create CMS Page
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ route('admin.dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ route('admin.cms-pages.index') }}">CMS Pages /</a>
            </li>
            <li class="font-medium text-primary">Create</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="max-w-6xl mx-auto">
    <form action="{{ route('admin.cms-pages.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Basic Information</h4>
            
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Title -->
                <div class="lg:col-span-2">
                    <label for="title" class="mb-2.5 block text-black dark:text-white">
                        Page Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('title') border-red-500 @enderror"
                        required
                    />
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="mb-2.5 block text-black dark:text-white">
                        URL Slug <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">/</span>
                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            value="{{ old('slug') }}"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent pl-8 pr-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('slug') border-red-500 @enderror"
                            required
                        />
                    </div>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Page Type -->
                <div>
                    <label for="page_type" class="mb-2.5 block text-black dark:text-white">
                        Page Type
                    </label>
                    <select
                        id="page_type"
                        name="page_type"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('page_type') border-red-500 @enderror"
                    >
                        <option value="page" {{ old('page_type', 'page') === 'page' ? 'selected' : '' }}>Regular Page</option>
                        <option value="homepage" {{ old('page_type') === 'homepage' ? 'selected' : '' }}>Homepage</option>
                        <option value="about" {{ old('page_type') === 'about' ? 'selected' : '' }}>About Us</option>
                        <option value="contact" {{ old('page_type') === 'contact' ? 'selected' : '' }}>Contact</option>
                        <option value="privacy" {{ old('page_type') === 'privacy' ? 'selected' : '' }}>Privacy Policy</option>
                        <option value="terms" {{ old('page_type') === 'terms' ? 'selected' : '' }}>Terms of Service</option>
                        <option value="faq" {{ old('page_type') === 'faq' ? 'selected' : '' }}>FAQ</option>
                    </select>
                    @error('page_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="mb-2.5 block text-black dark:text-white">
                        Status
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('status') border-red-500 @enderror"
                    >
                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Description -->
                <div class="lg:col-span-2">
                    <label for="meta_description" class="mb-2.5 block text-black dark:text-white">
                        Meta Description
                    </label>
                    <textarea
                        id="meta_description"
                        name="meta_description"
                        rows="3"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('meta_description') border-red-500 @enderror"
                        placeholder="Brief description for search engines (150-160 characters)"
                    >{{ old('meta_description') }}</textarea>
                    @error('meta_description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Page Content</h4>
            
            <!-- Content Editor -->
            <div>
                <label for="content" class="mb-2.5 block text-black dark:text-white">
                    Content <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="content"
                    name="content"
                    rows="20"
                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('content') border-red-500 @enderror"
                    required
                >{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">SEO Settings</h4>
            
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Meta Keywords -->
                <div>
                    <label for="meta_keywords" class="mb-2.5 block text-black dark:text-white">
                        Meta Keywords
                    </label>
                    <input
                        type="text"
                        id="meta_keywords"
                        name="meta_keywords"
                        value="{{ old('meta_keywords') }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('meta_keywords') border-red-500 @enderror"
                        placeholder="keyword1, keyword2, keyword3"
                    />
                    @error('meta_keywords')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Title -->
                <div>
                    <label for="meta_title" class="mb-2.5 block text-black dark:text-white">
                        Meta Title
                    </label>
                    <input
                        type="text"
                        id="meta_title"
                        name="meta_title"
                        value="{{ old('meta_title') }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('meta_title') border-red-500 @enderror"
                        placeholder="SEO title (50-60 characters)"
                    />
                    @error('meta_title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured Image -->
                <div class="lg:col-span-2">
                    <label for="featured_image" class="mb-2.5 block text-black dark:text-white">
                        Featured Image
                    </label>
                    <input
                        type="file"
                        id="featured_image"
                        name="featured_image"
                        accept="image/*"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('featured_image') border-red-500 @enderror"
                    />
                    @error('featured_image')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Advanced Settings -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Advanced Settings</h4>
            
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Template -->
                <div>
                    <label for="template" class="mb-2.5 block text-black dark:text-white">
                        Template
                    </label>
                    <select
                        id="template"
                        name="template"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('template') border-red-500 @enderror"
                    >
                        <option value="default" {{ old('template', 'default') === 'default' ? 'selected' : '' }}>Default</option>
                        <option value="full-width" {{ old('template') === 'full-width' ? 'selected' : '' }}>Full Width</option>
                        <option value="sidebar" {{ old('template') === 'sidebar' ? 'selected' : '' }}>With Sidebar</option>
                        <option value="landing" {{ old('template') === 'landing' ? 'selected' : '' }}>Landing Page</option>
                    </select>
                    @error('template')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="mb-2.5 block text-black dark:text-white">
                        Sort Order
                    </label>
                    <input
                        type="number"
                        id="sort_order"
                        name="sort_order"
                        value="{{ old('sort_order', 0) }}"
                        min="0"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('sort_order') border-red-500 @enderror"
                    />
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Show in Navigation -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                name="show_in_navigation"
                                value="1"
                                {{ old('show_in_navigation') ? 'checked' : '' }}
                                class="mr-2 rounded border-stroke dark:border-strokedark"
                            />
                            <span class="text-black dark:text-white">Show in Navigation Menu</span>
                        </label>
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                name="show_in_footer"
                                value="1"
                                {{ old('show_in_footer') ? 'checked' : '' }}
                                class="mr-2 rounded border-stroke dark:border-strokedark"
                            />
                            <span class="text-black dark:text-white">Show in Footer</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.cms-pages.index') }}" class="flex items-center gap-2 rounded-lg border border-stroke bg-white px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-300 dark:hover:bg-gray-800">
                <i data-lucide="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit" name="action" value="draft" class="flex items-center gap-2 rounded-lg border border-gray-500 bg-gray-500 px-6 py-3 text-white hover:bg-gray-600 transition-colors duration-200">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save as Draft
            </button>
            <button type="submit" name="action" value="publish" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-6 py-3 text-white hover:bg-primary/90 transition-colors duration-200">
                <i data-lucide="globe" class="w-4 h-4"></i>
                Publish
            </button>
        </div>
    </form>
</div>

<!-- TinyMCE Script -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE
    tinymce.init({
        selector: '#content',
        height: 500,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
        branding: false,
        promotion: false,
        menubar: false,
        statusbar: false,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });
    
    slugInput.addEventListener('input', function() {
        this.dataset.autoGenerated = 'false';
    });
});
</script>
@endsection
