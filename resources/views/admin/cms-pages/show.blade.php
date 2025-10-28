@extends('admin.layouts.app')

@section('title', 'CMS Page Details')

@section('content')
<!-- Header Section -->
<div class="max-w-6xl mx-auto mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">{{ $cmsPage->title }}</h1>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">CMS Page Details</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ admin_route('cms-pages.edit', $cmsPage) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-transparent bg-gradient-to-r from-blue-600 to-purple-600 text-sm font-medium text-white shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl">
                <i data-lucide="edit" class="w-4 h-4"></i>
                Edit Page
            </a>
            <a href="/{{ $cmsPage->slug }}" target="_blank" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                View Live
            </a>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto">
    <!-- Bento Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Page Overview Card (Large) -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                            <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                    </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Page Overview</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Essential information about this page</p>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Page Title</label>
                                <div class="px-4 py-3 bg-stone-50 dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-strokedark">
                                    <span class="text-stone-900 dark:text-white font-medium">{{ $cmsPage->title }}</span>
                                </div>
                            </div>
                            
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
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Status</label>
                                <div class="px-4 py-3 bg-stone-50 dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-strokedark">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $cmsPage->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ $cmsPage->is_active ? 'Published' : 'Draft' }}
                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Created</label>
                                <div class="px-4 py-3 bg-stone-50 dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-strokedark">
                                    <span class="text-stone-900 dark:text-white">{{ $cmsPage->created_at->format('M d, Y H:i') }}</span>
                                </div>
                </div>

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

        <!-- Status & Actions Card (Small) -->
        <div class="lg:col-span-4">
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg">
                            <i data-lucide="activity" class="w-4 h-4 text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Status</h3>
                </div>
                </div>
                
                <div class="p-6 text-center">
                    <div class="mb-4">
                        <div class="mx-auto w-16 h-16 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center mb-3">
                            @if($cmsPage->is_active)
                                <i data-lucide="check" class="w-8 h-8 text-white"></i>
                            @else
                                <i data-lucide="clock" class="w-8 h-8 text-white"></i>
                            @endif
                        </div>
                        <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-1">
                            {{ $cmsPage->is_active ? 'Published' : 'Draft' }}
                        </h4>
                        <p class="text-sm text-stone-600 dark:text-gray-400">
                            {{ $cmsPage->is_active ? 'This page is live and visible to visitors' : 'This page is not yet published' }}
                        </p>
                </div>
                    
                    <div class="space-y-2">
                        <a href="{{ admin_route('cms-pages.edit', $cmsPage) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-transparent bg-gradient-to-r from-blue-600 to-purple-600 text-sm font-medium text-white shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                            Edit Page
                        </a>
                        <a href="/{{ $cmsPage->slug }}" target="_blank" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                            View Live
                        </a>
                </div>
                </div>
            </div>
        </div>

        <!-- Content Preview Card (Full Width) -->
        <div class="lg:col-span-12">
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                            <i data-lucide="eye" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Content Preview</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Preview of the page content</p>
                </div>
                
                <div class="p-8">
                    @if($cmsPage->excerpt)
                        <div class="mb-6 p-4 bg-stone-50 dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-strokedark">
                            <h4 class="text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Excerpt</h4>
                            <p class="text-stone-900 dark:text-white">{{ $cmsPage->excerpt }}</p>
                        </div>
                    @endif
                    
                    <div class="prose prose-lg max-w-none dark:prose-invert">
                        {!! $cmsPage->content !!}
                    </div>
            </div>
        </div>
    </div>

        <!-- SEO Information Card (Medium) -->
        <div class="lg:col-span-6">
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg">
                            <i data-lucide="search" class="w-4 h-4 text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-stone-900 dark:text-white">SEO Information</h3>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    @if($cmsPage->meta_title)
                                <div>
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Meta Title</label>
                            <div class="px-3 py-2 bg-stone-50 dark:bg-gray-800 rounded-lg border border-stone-200 dark:border-strokedark">
                                <span class="text-stone-900 dark:text-white text-sm">{{ $cmsPage->meta_title }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if($cmsPage->meta_description)
                        <div>
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Meta Description</label>
                            <div class="px-3 py-2 bg-stone-50 dark:bg-gray-800 rounded-lg border border-stone-200 dark:border-strokedark">
                                <span class="text-stone-900 dark:text-white text-sm">{{ $cmsPage->meta_description }}</span>
                            </div>
                            </div>
                        @endif

                    @if(!$cmsPage->meta_title && !$cmsPage->meta_description)
                        <div class="text-center py-8">
                            <i data-lucide="search" class="w-12 h-12 text-stone-400 mx-auto mb-3"></i>
                            <p class="text-stone-600 dark:text-gray-400">No SEO information configured</p>
                            <a href="{{ admin_route('cms-pages.edit', $cmsPage) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Add SEO details</a>
                        </div>
                    @endif
                                    </div>
                                </div>
                            </div>
        
        <!-- Page Settings Card (Medium) -->
        <div class="lg:col-span-6">
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-lg">
                            <i data-lucide="settings" class="w-4 h-4 text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Page Settings</h3>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-stone-700 dark:text-stone-300">Show in Navigation</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $cmsPage->show_in_navigation ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' }}">
                            {{ $cmsPage->show_in_navigation ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-stone-700 dark:text-stone-300">Show in Footer</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $cmsPage->show_in_footer ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' }}">
                            {{ $cmsPage->show_in_footer ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    @if($cmsPage->sort_order)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-stone-700 dark:text-stone-300">Sort Order</span>
                            <span class="text-sm font-semibold text-stone-900 dark:text-white">{{ $cmsPage->sort_order }}</span>
                        </div>
                    @endif
                    
                    @if($cmsPage->template)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-stone-700 dark:text-stone-300">Template</span>
                            <span class="text-sm font-semibold text-stone-900 dark:text-white">{{ ucfirst(str_replace('-', ' ', $cmsPage->template)) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection