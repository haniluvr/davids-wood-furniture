@extends('admin.layouts.app')

@section('title', 'Preview: ' . $cmsPage->title)

@section('content')
<!-- Header Section -->
<div class="max-w-6xl mx-auto mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                <i data-lucide="eye" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Preview: {{ $cmsPage->title }}</h1>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Preview how this page will appear to visitors</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ admin_route('cms-pages.edit', $cmsPage) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="edit" class="w-4 h-4"></i>
                Edit Page
            </a>
            <a href="{{ admin_route('cms-pages.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-transparent bg-gradient-to-r from-emerald-600 to-teal-600 text-sm font-medium text-white shadow-lg transition-all duration-200 hover:from-emerald-700 hover:to-teal-700 hover:shadow-xl">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Pages
            </a>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto">
    <!-- Page Preview Card -->
    <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
        <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                    <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Page Preview</h3>
            </div>
            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">This is how your page will appear to visitors</p>
        </div>
        
        <div class="p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-stone-900 dark:text-white mb-4">{{ $cmsPage->title }}</h1>
                
                @if($cmsPage->featured_image)
                    <div class="mb-6">
                        <img src="{{ Storage::url($cmsPage->featured_image) }}" 
                             alt="{{ $cmsPage->title }}" 
                             class="w-full h-64 object-cover rounded-xl border border-stone-200 dark:border-strokedark">
                    </div>
                @endif
                
                @if($cmsPage->excerpt)
                    <div class="text-lg text-stone-600 dark:text-gray-400 leading-relaxed">
                        {{ $cmsPage->excerpt }}
                    </div>
                @endif
            </div>
            
            <!-- Page Content -->
            <div class="prose prose-lg max-w-none dark:prose-invert">
                {!! $cmsPage->content !!}
            </div>
        </div>
    </div>

    <!-- Page Information Card -->
    <div class="mt-8 bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
        <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-gray-800 dark:to-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl">
                    <i data-lucide="info" class="w-5 h-5 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Page Information</h3>
            </div>
            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Details about this page</p>
        </div>
        
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">URL Slug</label>
                        <div class="px-4 py-3 bg-stone-50 dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-strokedark">
                            <span class="font-mono text-stone-900 dark:text-white">/{{ $cmsPage->slug }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Page Type</label>
                        <div class="px-4 py-3 bg-stone-50 dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-strokedark">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $cmsPage->type === 'page' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 
                                   ($cmsPage->type === 'blog' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' : 
                                   ($cmsPage->type === 'faq' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400')) }}">
                                {{ ucwords($cmsPage->type) }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Status</label>
                        <div class="px-4 py-3 bg-stone-50 dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-strokedark">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $cmsPage->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $cmsPage->is_active ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6">
                    @if($cmsPage->meta_title)
                        <div>
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Meta Title</label>
                            <div class="px-4 py-3 bg-stone-50 dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-strokedark">
                                <span class="text-stone-900 dark:text-white">{{ $cmsPage->meta_title }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if($cmsPage->meta_description)
                        <div>
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Meta Description</label>
                            <div class="px-4 py-3 bg-stone-50 dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-strokedark">
                                <span class="text-stone-900 dark:text-white">{{ $cmsPage->meta_description }}</span>
                            </div>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Last Updated</label>
                        <div class="px-4 py-3 bg-stone-50 dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-strokedark">
                            <span class="text-stone-900 dark:text-white">{{ $cmsPage->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
