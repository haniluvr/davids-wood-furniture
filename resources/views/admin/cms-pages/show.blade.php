@extends('admin.layouts.app')

@section('title', 'CMS Page Details')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        {{ $page->title }}
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ admin_route('cms-pages.index') }}">CMS Pages /</a>
            </li>
            <li class="font-medium text-primary">{{ $page->title }}</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Page Info Card -->
    <div class="lg:col-span-1">
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex flex-col items-center text-center">
                <!-- Page Icon -->
                <div class="relative mb-4">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center shadow-lg">
                        <i data-lucide="file-text" class="w-8 h-8 text-white"></i>
                    </div>
                    <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-500 border-2 border-white dark:border-boxdark flex items-center justify-center">
                        <i data-lucide="check" class="w-3 h-3 text-white"></i>
                    </div>
                </div>

                <!-- Page Details -->
                <h3 class="text-lg font-bold text-black dark:text-white mb-2">
                    {{ $page->title }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm">{{ $page->slug }}</p>

                <!-- Status Badge -->
                <div class="mb-6">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                        @if($page->status === 'published') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                        @elseif($page->status === 'draft') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                        @if($page->status === 'published')
                            <i data-lucide="globe" class="w-3 h-3 mr-1"></i>
                            Published
                        @elseif($page->status === 'draft')
                            <i data-lucide="edit" class="w-3 h-3 mr-1"></i>
                            Draft
                        @else
                            <i data-lucide="archive" class="w-3 h-3 mr-1"></i>
                            Archived
                        @endif
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 w-full">
                    <a href="{{ admin_route('cms-pages.edit', $page) }}" class="flex-1 flex items-center justify-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Edit
                    </a>
                    <a href="/{{ $page->slug }}" target="_blank" class="flex-1 flex items-center justify-center gap-2 rounded-lg border border-stroke bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-300 dark:hover:bg-gray-800">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                        View
                    </a>
                </div>
            </div>
        </div>

        <!-- Page Stats -->
        <div class="mt-6 rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Page Information</h4>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Page Type</span>
                    <span class="font-semibold text-black dark:text-white">{{ ucfirst(str_replace('_', ' ', $page->page_type)) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Template</span>
                    <span class="font-semibold text-black dark:text-white">{{ ucfirst(str_replace('-', ' ', $page->template)) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Sort Order</span>
                    <span class="font-semibold text-black dark:text-white">{{ $page->sort_order }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Created</span>
                    <span class="font-semibold text-black dark:text-white">{{ $page->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Last Updated</span>
                    <span class="font-semibold text-black dark:text-white">{{ $page->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Navigation Settings -->
        <div class="mt-6 rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Navigation</h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Show in Navigation</span>
                    @if($page->show_in_navigation)
                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            <i data-lucide="check" class="w-3 h-3 mr-1"></i>
                            Yes
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                            <i data-lucide="x" class="w-3 h-3 mr-1"></i>
                            No
                        </span>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Show in Footer</span>
                    @if($page->show_in_footer)
                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            <i data-lucide="check" class="w-3 h-3 mr-1"></i>
                            Yes
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                            <i data-lucide="x" class="w-3 h-3 mr-1"></i>
                            No
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:col-span-2">
        <!-- Tabs -->
        <div class="mb-6" x-data="{ activeTab: 'content' }">
            <div class="border-b border-stroke dark:border-strokedark">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'content'" :class="activeTab === 'content' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Content
                    </button>
                    <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        SEO
                    </button>
                    <button @click="activeTab = 'preview'" :class="activeTab === 'preview' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Preview
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-6">
                <!-- Content Tab -->
                <div x-show="activeTab === 'content'" x-transition>
                    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                        <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Page Content</h4>
                        <div class="prose max-w-none dark:prose-invert">
                            {!! $page->content !!}
                        </div>
                    </div>
                </div>

                <!-- SEO Tab -->
                <div x-show="activeTab === 'seo'" x-transition>
                    <div class="space-y-6">
                        <!-- Meta Information -->
                        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Meta Information</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Meta Title</label>
                                    <p class="text-black dark:text-white">{{ $page->meta_title ?: 'Not set' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Meta Description</label>
                                    <p class="text-black dark:text-white">{{ $page->meta_description ?: 'Not set' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Meta Keywords</label>
                                    <p class="text-black dark:text-white">{{ $page->meta_keywords ?: 'Not set' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        @if($page->featured_image)
                            <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                                <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Featured Image</h4>
                                <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="h-48 w-auto rounded-lg border border-stroke dark:border-strokedark">
                            </div>
                        @endif

                        <!-- URL Information -->
                        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">URL Information</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Page URL</label>
                                    <div class="flex items-center gap-2">
                                        <input type="text" value="{{ url('/' . $page->slug) }}" readonly class="flex-1 rounded border-[1.5px] border-stroke bg-gray-50 px-3 py-2 text-sm text-gray-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400">
                                        <a href="/{{ $page->slug }}" target="_blank" class="flex items-center gap-1 rounded-lg border border-primary bg-primary px-3 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                            Visit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Tab -->
                <div x-show="activeTab === 'preview'" x-transition>
                    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-black dark:text-white">Page Preview</h4>
                            <a href="/{{ $page->slug }}" target="_blank" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                                Open in New Tab
                            </a>
                        </div>
                        <div class="border border-stroke dark:border-strokedark rounded-lg overflow-hidden">
                            <iframe src="/{{ $page->slug }}" class="w-full h-96" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
