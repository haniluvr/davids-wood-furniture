@extends('admin.layouts.app')

@section('title', 'Edit CMS Page')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Edit CMS Page</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Edit "{{ $cmsPage->title }}"</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ admin_route('cms-pages.preview', $cmsPage) }}" target="_blank" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    Preview
                </a>
                <a href="{{ admin_route('cms-pages.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to CMS Pages
                </a>
            </div>
        </div>
    </div>

    <form action="{{ admin_route('cms-pages.update', $cmsPage) }}" method="POST" class="space-y-8" id="cmsForm">
        @csrf
        @method('PUT')

        <!-- Main Content Area -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <!-- Tab Navigation -->
            <div class="border-b border-stone-200 dark:border-strokedark">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button type="button" onclick="switchTab('content')" id="content-tab" class="tab-button active py-4 px-1 border-b-2 font-medium text-sm border-emerald-500 text-emerald-600 dark:text-emerald-400">
                        <i data-lucide="file-text" class="w-5 h-5 mr-2 inline"></i>
                        Content
                    </button>
                    <button type="button" onclick="switchTab('seo')" id="seo-tab" class="tab-button py-4 px-1 border-b-2 font-medium text-sm border-transparent text-stone-500 hover:text-stone-700 hover:border-stone-300 dark:text-gray-400 dark:hover:text-gray-300">
                        <i data-lucide="search" class="w-5 h-5 mr-2 inline"></i>
                        SEO
                    </button>
                    <button type="button" onclick="switchTab('settings')" id="settings-tab" class="tab-button py-4 px-1 border-b-2 font-medium text-sm border-transparent text-stone-500 hover:text-stone-700 hover:border-stone-300 dark:text-gray-400 dark:hover:text-gray-300">
                        <i data-lucide="settings" class="w-5 h-5 mr-2 inline"></i>
                        Settings
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-8">
                <!-- Content Tab -->
                <div id="content-panel" class="tab-panel">
                    <div class="space-y-6">
                        <!-- Title and Slug -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="title" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Page Title <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value="{{ old('title', $cmsPage->title) }}"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('title') border-red-300 @enderror"
                                    placeholder="Enter page title..."
                                    required
                                />
                                @error('title')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="slug" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    URL Slug <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-stone-500 dark:text-gray-400">/</span>
                                    <input
                                        type="text"
                                        id="slug"
                                        name="slug"
                                        value="{{ old('slug', $cmsPage->slug) }}"
                                        class="w-full pl-8 pr-3 py-3 rounded-xl border border-stone-200 bg-white px-4 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('slug') border-red-300 @enderror"
                                        placeholder="url-slug"
                                        required
                                    />
                                </div>
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-stone-500 dark:text-gray-400">Auto-generated from title</p>
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div class="space-y-2">
                            <label for="excerpt" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Excerpt
                            </label>
                            <textarea
                                id="excerpt"
                                name="excerpt"
                                rows="3"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('excerpt') border-red-300 @enderror"
                                placeholder="Brief description of the page..."
                            >{{ old('excerpt', $cmsPage->excerpt) }}</textarea>
                            @error('excerpt')
                                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content Editor -->
                        <div class="space-y-2">
                            <label for="content" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="content"
                                name="content"
                                class="quill-editor w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('content') border-red-300 @enderror"
                                required
                            >{{ old('content', $cmsPage->content) }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO Tab -->
                <div id="seo-panel" class="tab-panel hidden">
                    <div class="space-y-6">
                        <!-- Meta Title -->
                        <div class="space-y-2">
                            <label for="meta_title" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Meta Title
                            </label>
                            <input
                                type="text"
                                id="meta_title"
                                name="meta_title"
                                value="{{ old('meta_title', $cmsPage->meta_title) }}"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('meta_title') border-red-300 @enderror"
                                placeholder="SEO title (50-60 characters)"
                                maxlength="60"
                            />
                            <div class="mt-1 flex justify-between text-xs text-stone-500 dark:text-gray-400">
                                <span>Recommended: 50-60 characters</span>
                                <span id="meta_title_count">{{ strlen(old('meta_title', $cmsPage->meta_title ?? '')) }}/60</span>
                            </div>
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div class="space-y-2">
                            <label for="meta_description" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Meta Description
                            </label>
                            <textarea
                                id="meta_description"
                                name="meta_description"
                                rows="3"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('meta_description') border-red-300 @enderror"
                                placeholder="Brief description for search engines (150-160 characters)"
                                maxlength="160"
                            >{{ old('meta_description', $cmsPage->meta_description) }}</textarea>
                            <div class="mt-1 flex justify-between text-xs text-stone-500 dark:text-gray-400">
                                <span>Recommended: 150-160 characters</span>
                                <span id="meta_description_count">{{ strlen(old('meta_description', $cmsPage->meta_description ?? '')) }}/160</span>
                            </div>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Keywords -->
                        <div class="space-y-2">
                            <label for="meta_keywords" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Meta Keywords
                            </label>
                            <input
                                type="text"
                                id="meta_keywords"
                                name="meta_keywords"
                                value="{{ old('meta_keywords', $cmsPage->meta_keywords) }}"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('meta_keywords') border-red-300 @enderror"
                                placeholder="keyword1, keyword2, keyword3"
                            />
                            @error('meta_keywords')
                                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SEO Preview -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Search Engine Preview
                            </label>
                            <div class="border border-stone-200 dark:border-strokedark rounded-xl p-4 bg-stone-50 dark:bg-gray-800">
                                <div id="seo-preview-title" class="text-blue-600 dark:text-blue-400 text-lg font-medium hover:underline cursor-pointer">
                                    {{ old('meta_title', $cmsPage->meta_title) ?: $cmsPage->title }}
                                </div>
                                <div id="seo-preview-url" class="text-green-700 dark:text-green-400 text-sm">
                                    {{ url('/') }}/<span id="seo-preview-slug">{{ old('slug', $cmsPage->slug) }}</span>
                                </div>
                                <div id="seo-preview-description" class="text-stone-600 dark:text-gray-400 text-sm mt-1">
                                    {{ old('meta_description', $cmsPage->meta_description) ?: 'Your meta description will appear here' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div id="settings-panel" class="tab-panel hidden">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Page Type -->
                            <div class="space-y-2">
                                <label for="type" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Page Type <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="type"
                                    name="type"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('type') border-red-300 @enderror"
                                    required
                                >
                                    <option value="page" {{ old('type', $cmsPage->type) === 'page' ? 'selected' : '' }}>Page</option>
                                    <option value="blog" {{ old('type', $cmsPage->type) === 'blog' ? 'selected' : '' }}>Blog Post</option>
                                    <option value="faq" {{ old('type', $cmsPage->type) === 'faq' ? 'selected' : '' }}>FAQ</option>
                                    <option value="policy" {{ old('type', $cmsPage->type) === 'policy' ? 'selected' : '' }}>Policy</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Status
                                </label>
                                <select
                                    id="status"
                                    name="is_active"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('is_active') border-red-300 @enderror"
                                >
                                    <option value="0" {{ old('is_active', $cmsPage->is_active) == 0 ? 'selected' : '' }}>Draft</option>
                                    <option value="1" {{ old('is_active', $cmsPage->is_active) == 1 ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Publish Date -->
                            <div class="space-y-2">
                                <label for="published_at" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Publish Date
                                </label>
                                <input
                                    type="datetime-local"
                                    id="published_at"
                                    name="published_at"
                                    value="{{ old('published_at', $cmsPage->published_at ? $cmsPage->published_at->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('published_at') border-red-300 @enderror"
                                />
                                @error('published_at')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sort Order -->
                            <div class="space-y-2">
                                <label for="sort_order" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Sort Order
                                </label>
                                <input
                                    type="number"
                                    id="sort_order"
                                    name="sort_order"
                                    value="{{ old('sort_order', $cmsPage->sort_order) }}"
                                    min="0"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('sort_order') border-red-300 @enderror"
                                />
                                @error('sort_order')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Featured Image -->
                            <div class="lg:col-span-2 space-y-2">
                                <label for="featured_image" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Featured Image
                                </label>
                                
                                @if($cmsPage->featured_image)
                                    <div class="mb-4 p-4 border border-stone-200 dark:border-strokedark rounded-xl bg-stone-50 dark:bg-gray-800">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $cmsPage->featured_image_url }}" alt="{{ $cmsPage->title }}" class="w-16 h-16 object-cover rounded">
                                                <div>
                                                    <p class="text-sm font-medium text-stone-900 dark:text-white">Current Featured Image</p>
                                                    <p class="text-xs text-stone-500 dark:text-gray-400">Upload a new image to replace</p>
                                                </div>
                                            </div>
                                            <button type="button" onclick="removeCurrentFeaturedImage()" class="text-red-500 hover:text-red-700">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="flex items-center space-x-4">
                                    <input
                                        type="file"
                                        id="featured_image"
                                        name="featured_image"
                                        accept="image/*"
                                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('featured_image') border-red-300 @enderror"
                                    />
                                    <button type="button" onclick="openMediaLibraryForFeatured()" class="px-4 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors duration-200">
                                        <i data-lucide="image" class="w-4 h-4 mr-2 inline"></i>
                                        Choose from Library
                                    </button>
                                </div>
                                @error('featured_image')
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Options -->
                        <div class="border-t border-stone-200 dark:border-strokedark pt-6">
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        name="is_featured"
                                        value="1"
                                        {{ old('is_featured', $cmsPage->is_featured) ? 'checked' : '' }}
                                        class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500 dark:border-strokedark dark:bg-boxdark"
                                    />
                                    <span class="ml-2 text-sm text-stone-700 dark:text-stone-300">Featured Page</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ admin_route('cms-pages.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 border border-stone-200 bg-white text-sm font-medium text-stone-700 rounded-xl transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit" name="action" value="publish" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-blue-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-emerald-700 hover:to-blue-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                <i data-lucide="save" class="w-4 h-4"></i>
                Update Page
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
            updateSeoPreview();
        }
    });
    
    slugInput.addEventListener('input', function() {
        this.dataset.autoGenerated = 'false';
        updateSeoPreview();
    });

    // Character counters for SEO fields
    const metaTitleInput = document.getElementById('meta_title');
    const metaDescriptionInput = document.getElementById('meta_description');
    const metaTitleCount = document.getElementById('meta_title_count');
    const metaDescriptionCount = document.getElementById('meta_description_count');

    metaTitleInput.addEventListener('input', function() {
        metaTitleCount.textContent = this.value.length + '/60';
        updateSeoPreview();
    });

    metaDescriptionInput.addEventListener('input', function() {
        metaDescriptionCount.textContent = this.value.length + '/160';
        updateSeoPreview();
    });

    // Initialize character counts
    if (metaTitleCount) {
        metaTitleCount.textContent = metaTitleInput.value.length + '/60';
    }
    if (metaDescriptionCount) {
        metaDescriptionCount.textContent = metaDescriptionInput.value.length + '/160';
    }
});

// Tab switching functionality
function switchTab(tabName) {
    // Hide all panels
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(tab => {
        tab.classList.remove('active', 'border-emerald-500', 'text-emerald-600', 'dark:text-emerald-400');
        tab.classList.add('border-transparent', 'text-stone-500', 'dark:text-gray-400');
    });
    
    // Show selected panel
    document.getElementById(tabName + '-panel').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-emerald-500', 'text-emerald-600', 'dark:text-emerald-400');
    activeTab.classList.remove('border-transparent', 'text-stone-500', 'dark:text-gray-400');
}

// SEO Preview functionality
function updateSeoPreview() {
    const title = document.getElementById('meta_title').value || document.getElementById('title').value || 'Your page title will appear here';
    const description = document.getElementById('meta_description').value || 'Your meta description will appear here';
    const slug = document.getElementById('slug').value || 'your-slug';
    
    document.getElementById('seo-preview-title').textContent = title;
    document.getElementById('seo-preview-description').textContent = description;
    document.getElementById('seo-preview-slug').textContent = slug;
}

// Featured image media library
function openMediaLibraryForFeatured() {
    if (typeof openMediaLibrary === 'function') {
        openMediaLibrary(function(imageUrl) {
            // Create a preview of the selected image
            const featuredImageInput = document.getElementById('featured_image');
            const preview = document.createElement('div');
            preview.className = 'mt-2 p-4 border border-stone-200 dark:border-strokedark rounded-xl bg-stone-50 dark:bg-gray-800';
            preview.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="${imageUrl}" alt="Featured image preview" class="w-16 h-16 object-cover rounded">
                        <div>
                            <p class="text-sm font-medium text-stone-900 dark:text-white">Selected Image</p>
                            <p class="text-xs text-stone-500 dark:text-gray-400">Click to remove</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFeaturedImagePreview()" class="text-red-500 hover:text-red-700">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            `;
            
            // Remove existing preview
            const existingPreview = document.querySelector('.featured-image-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            preview.className += ' featured-image-preview';
            featuredImageInput.parentNode.appendChild(preview);
            
            // Store the image URL for form submission
            featuredImageInput.dataset.selectedImageUrl = imageUrl;
        });
    } else {
        alert('Media library is not available');
    }
}

function removeFeaturedImagePreview() {
    const preview = document.querySelector('.featured-image-preview');
    if (preview) {
        preview.remove();
    }
    
    const featuredImageInput = document.getElementById('featured_image');
    if (featuredImageInput) {
        delete featuredImageInput.dataset.selectedImageUrl;
    }
}

function removeCurrentFeaturedImage() {
    if (confirm('Are you sure you want to remove the current featured image?')) {
        // Add hidden input to indicate removal
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_featured_image';
        hiddenInput.value = '1';
        document.getElementById('cmsForm').appendChild(hiddenInput);
        
        // Hide the current image preview
        const currentImagePreview = document.querySelector('.mb-4.p-4.border');
        if (currentImagePreview) {
            currentImagePreview.style.display = 'none';
        }
    }
}

// Form submission with featured image handling
document.getElementById('cmsForm').addEventListener('submit', function(e) {
    const featuredImageInput = document.getElementById('featured_image');
    const selectedImageUrl = featuredImageInput?.dataset.selectedImageUrl;
    
    if (selectedImageUrl && !featuredImageInput.files.length) {
        // Create a hidden input with the selected image URL
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'featured_image_url';
        hiddenInput.value = selectedImageUrl;
        this.appendChild(hiddenInput);
    }
});
</script>
@endpush
@endsection
