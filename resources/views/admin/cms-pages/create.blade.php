@extends('admin.layouts.app')

@section('title', 'Create CMS Page')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-stone-900">Create CMS Page</h1>
                    <p class="mt-1 text-sm text-stone-600">Create a new page or blog post</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ admin_route('cms-pages.index') }}" class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                </div>
            </div>
        </div>
</div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form action="{{ admin_route('cms-pages.store') }}" method="POST" class="space-y-6" id="cmsForm">
        @csrf
            
            <!-- Main Content Area -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200">
                <!-- Tab Navigation -->
                <div class="border-b border-stone-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button type="button" onclick="switchTab('content')" id="content-tab" class="tab-button active py-4 px-1 border-b-2 font-medium text-sm border-emerald-500 text-emerald-600">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Content
                        </button>
                        <button type="button" onclick="switchTab('seo')" id="seo-tab" class="tab-button py-4 px-1 border-b-2 font-medium text-sm border-transparent text-stone-500 hover:text-stone-700 hover:border-stone-300">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            SEO
                        </button>
                        <button type="button" onclick="switchTab('settings')" id="settings-tab" class="tab-button py-4 px-1 border-b-2 font-medium text-sm border-transparent text-stone-500 hover:text-stone-700 hover:border-stone-300">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Content Tab -->
                    <div id="content-panel" class="tab-panel">
                        <div class="space-y-6">
                            <!-- Title and Slug -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-stone-700 mb-2">
                        Page Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                                        class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('title') border-red-500 @enderror"
                                        placeholder="Enter page title..."
                        required
                    />
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                                    <label for="slug" class="block text-sm font-medium text-stone-700 mb-2">
                        URL Slug <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-stone-500">/</span>
                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            value="{{ old('slug') }}"
                                            class="w-full pl-8 pr-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('slug') border-red-500 @enderror"
                                            placeholder="url-slug"
                            required
                        />
                    </div>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                                    <p class="mt-1 text-xs text-stone-500">Auto-generated from title</p>
                </div>
                </div>

                            <!-- Excerpt -->
                <div>
                                <label for="excerpt" class="block text-sm font-medium text-stone-700 mb-2">
                                    Excerpt
                    </label>
                    <textarea
                                    id="excerpt"
                                    name="excerpt"
                        rows="3"
                                    class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('excerpt') border-red-500 @enderror"
                                    placeholder="Brief description of the page..."
                                >{{ old('excerpt') }}</textarea>
                                @error('excerpt')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            
            <!-- Content Editor -->
            <div>
                                <label for="content" class="block text-sm font-medium text-stone-700 mb-2">
                    Content <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="content"
                    name="content"
                                    class="quill-editor w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('content') border-red-500 @enderror"
                    required
                >{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
                        </div>
                    </div>

                    <!-- SEO Tab -->
                    <div id="seo-panel" class="tab-panel hidden">
                        <div class="space-y-6">
                            <!-- Meta Title -->
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-stone-700 mb-2">
                                    Meta Title
                                </label>
                                <input
                                    type="text"
                                    id="meta_title"
                                    name="meta_title"
                                    value="{{ old('meta_title') }}"
                                    class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('meta_title') border-red-500 @enderror"
                                    placeholder="SEO title (50-60 characters)"
                                    maxlength="60"
                                />
                                <div class="mt-1 flex justify-between text-xs text-stone-500">
                                    <span>Recommended: 50-60 characters</span>
                                    <span id="meta_title_count">0/60</span>
                                </div>
                                @error('meta_title')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
        </div>

                            <!-- Meta Description -->
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-stone-700 mb-2">
                                    Meta Description
                                </label>
                                <textarea
                                    id="meta_description"
                                    name="meta_description"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('meta_description') border-red-500 @enderror"
                                    placeholder="Brief description for search engines (150-160 characters)"
                                    maxlength="160"
                                >{{ old('meta_description') }}</textarea>
                                <div class="mt-1 flex justify-between text-xs text-stone-500">
                                    <span>Recommended: 150-160 characters</span>
                                    <span id="meta_description_count">0/160</span>
                                </div>
                                @error('meta_description')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                <!-- Meta Keywords -->
                <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-stone-700 mb-2">
                        Meta Keywords
                    </label>
                    <input
                        type="text"
                        id="meta_keywords"
                        name="meta_keywords"
                        value="{{ old('meta_keywords') }}"
                                    class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('meta_keywords') border-red-500 @enderror"
                        placeholder="keyword1, keyword2, keyword3"
                    />
                    @error('meta_keywords')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                            <!-- SEO Preview -->
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-2">
                                    Search Engine Preview
                                </label>
                                <div class="border border-stone-300 rounded-lg p-4 bg-stone-50">
                                    <div id="seo-preview-title" class="text-blue-600 text-lg font-medium hover:underline cursor-pointer">
                                        {{ old('meta_title') ?: 'Your page title will appear here' }}
                                    </div>
                                    <div id="seo-preview-url" class="text-green-700 text-sm">
                                        {{ url('/') }}/<span id="seo-preview-slug">{{ old('slug') ?: 'your-slug' }}</span>
                                    </div>
                                    <div id="seo-preview-description" class="text-stone-600 text-sm mt-1">
                                        {{ old('meta_description') ?: 'Your meta description will appear here' }}
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
                                <div>
                                    <label for="type" class="block text-sm font-medium text-stone-700 mb-2">
                                        Page Type <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="type"
                                        name="type"
                                        class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('type') border-red-500 @enderror"
                                        required
                                    >
                                        <option value="page" {{ old('type', 'page') === 'page' ? 'selected' : '' }}>Page</option>
                                        <option value="blog" {{ old('type') === 'blog' ? 'selected' : '' }}>Blog Post</option>
                                        <option value="faq" {{ old('type') === 'faq' ? 'selected' : '' }}>FAQ</option>
                                        <option value="policy" {{ old('type') === 'policy' ? 'selected' : '' }}>Policy</option>
                                    </select>
                                    @error('type')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                <div>
                                    <label for="status" class="block text-sm font-medium text-stone-700 mb-2">
                                        Status
                    </label>
                                    <select
                                        id="status"
                                        name="is_active"
                                        class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('is_active') border-red-500 @enderror"
                                    >
                                        <option value="0" {{ old('is_active', '0') === '0' ? 'selected' : '' }}>Draft</option>
                                        <option value="1" {{ old('is_active') === '1' ? 'selected' : '' }}>Published</option>
                                    </select>
                                    @error('is_active')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured Image -->
                <div class="lg:col-span-2">
                                    <label for="featured_image" class="block text-sm font-medium text-stone-700 mb-2">
                        Featured Image
                    </label>
                                    <div class="flex items-center space-x-4">
                    <input
                        type="file"
                        id="featured_image"
                        name="featured_image"
                        accept="image/*"
                                            class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('featured_image') border-red-500 @enderror"
                    />
                                        <button type="button" onclick="openMediaLibraryForFeatured()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                            Choose from Library
                                        </button>
                                    </div>
                    @error('featured_image')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                                <!-- Publish Date -->
                <div>
                                    <label for="published_at" class="block text-sm font-medium text-stone-700 mb-2">
                                        Publish Date
                    </label>
                                    <input
                                        type="datetime-local"
                                        id="published_at"
                                        name="published_at"
                                        value="{{ old('published_at') }}"
                                        class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('published_at') border-red-500 @enderror"
                                    />
                                    @error('published_at')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                                    <label for="sort_order" class="block text-sm font-medium text-stone-700 mb-2">
                        Sort Order
                    </label>
                    <input
                        type="number"
                        id="sort_order"
                        name="sort_order"
                        value="{{ old('sort_order', 0) }}"
                        min="0"
                                        class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('sort_order') border-red-500 @enderror"
                    />
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                                </div>
                </div>

                            <!-- Additional Options -->
                            <div class="border-t border-stone-200 pt-6">
                                <div class="flex items-center space-x-6">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                            name="is_featured"
                                value="1"
                                            {{ old('is_featured') ? 'checked' : '' }}
                                            class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500"
                            />
                                        <span class="ml-2 text-sm text-stone-700">Featured Page</span>
                        </label>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
                <button type="submit" name="action" value="draft" class="inline-flex items-center px-6 py-3 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                Save as Draft
            </button>
                <button type="submit" name="action" value="publish" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                Publish
            </button>
        </div>
    </form>
    </div>
</div>

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
    metaTitleCount.textContent = metaTitleInput.value.length + '/60';
    metaDescriptionCount.textContent = metaDescriptionInput.value.length + '/160';
});

// Tab switching functionality
function switchTab(tabName) {
    // Hide all panels
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(tab => {
        tab.classList.remove('active', 'border-emerald-500', 'text-emerald-600');
        tab.classList.add('border-transparent', 'text-stone-500');
    });
    
    // Show selected panel
    document.getElementById(tabName + '-panel').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-emerald-500', 'text-emerald-600');
    activeTab.classList.remove('border-transparent', 'text-stone-500');
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
    openMediaLibrary(function(imageUrl) {
        // Create a preview of the selected image
        const featuredImageInput = document.getElementById('featured_image');
        const preview = document.createElement('div');
        preview.className = 'mt-2 p-4 border border-stone-300 rounded-lg bg-stone-50';
        preview.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="${imageUrl}" alt="Featured image preview" class="w-16 h-16 object-cover rounded">
                    <div>
                        <p class="text-sm font-medium text-stone-900">Selected Image</p>
                        <p class="text-xs text-stone-500">Click to remove</p>
                    </div>
                </div>
                <button type="button" onclick="removeFeaturedImagePreview()" class="text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
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
}

function removeFeaturedImagePreview() {
    const preview = document.querySelector('.featured-image-preview');
    if (preview) {
        preview.remove();
    }
    
    const featuredImageInput = document.getElementById('featured_image');
    delete featuredImageInput.dataset.selectedImageUrl;
}

// Form submission with featured image handling
document.getElementById('cmsForm').addEventListener('submit', function(e) {
    const featuredImageInput = document.getElementById('featured_image');
    const selectedImageUrl = featuredImageInput.dataset.selectedImageUrl;
    
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
@endsection