@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="package" class="w-6 h-6 text-white"></i>
                    </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Product Details - {{ $product->name }}</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">View and manage product information</p>
                </div>
            </div>
            <a href="{{ admin_route('products.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Products
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Stock Quantity</p>
                        <p class="text-3xl font-bold text-black dark:text-white mt-2">{{ $product->stock_quantity }}</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg">
                        <i data-lucide="package" class="h-7 w-7 text-white"></i>
                </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Regular Price</p>
                        <p class="text-3xl font-bold text-black dark:text-white mt-2">₱{{ number_format($product->price, 2) }}</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-green-600 shadow-lg">
                        <i data-lucide="dollar-sign" class="h-7 w-7 text-white"></i>
                </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                        <p class="text-3xl font-bold text-black dark:text-white mt-2">{{ $product->orderItems()->count() }}</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 shadow-lg">
                        <i data-lucide="shopping-cart" class="h-7 w-7 text-white"></i>
                </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Average Rating</p>
                        <p class="text-3xl font-bold text-black dark:text-white mt-2">{{ $product->reviews()->avg('rating') ? number_format($product->reviews()->avg('rating'), 1) : 'N/A' }}</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 shadow-lg">
                        <i data-lucide="star" class="h-7 w-7 text-white"></i>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content with Tabs -->
    <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
        <!-- Tab Navigation -->
        <div class="border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-stone-50 to-gray-50 dark:from-gray-800 dark:to-gray-700">
            <nav class="flex space-x-8 px-8" aria-label="Tabs">
                <button
                    onclick="switchTab('overview')"
                    class="tab-button active border-b-2 border-primary py-6 px-1 text-sm font-semibold text-primary transition-all duration-200"
                    data-tab="overview"
                >
                    Overview
                </button>
                <button
                    onclick="switchTab('stock')"
                    class="tab-button border-b-2 border-transparent py-6 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200"
                    data-tab="stock"
                >
                    Stock History
                </button>
                <button
                    onclick="switchTab('reviews')"
                    class="tab-button border-b-2 border-transparent py-6 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200"
                    data-tab="reviews"
                >
                    Reviews ({{ $product->reviews()->count() }})
                </button>
                <button
                    onclick="switchTab('analytics')"
                    class="tab-button border-b-2 border-transparent py-6 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200"
                    data-tab="analytics"
                >
                    Analytics
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-8">
            <!-- Overview Tab -->
            <div id="overview-tab" class="tab-content">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <!-- Product Information Card -->
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                    <i data-lucide="info" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Product Information</h3>
                            </div>
                            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Basic product details and specifications</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Name:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">SKU:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->sku }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Category:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->category->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Barcode:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->barcode ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Weight:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->weight ? $product->weight . ' lbs' : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Dimensions:</span>
                                <span class="font-semibold text-black dark:text-white">{{ $product->dimensions ?? 'N/A' }}</span>
                            </div>
                            @if($product->room_category && count($product->room_category) > 0)
                            <div class="flex flex-col gap-2 py-2 border-t border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Room Categories:</span>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $roomData = [
                                            'bedroom' => ['name' => 'Bedroom', 'icon' => 'bed-double'],
                                            'living-room' => ['name' => 'Living Room', 'icon' => 'sofa'],
                                            'dining-room' => ['name' => 'Dining Room', 'icon' => 'utensils'],
                                            'bathroom' => ['name' => 'Bathroom', 'icon' => 'bath'],
                                            'office' => ['name' => 'Office', 'icon' => 'lamp-desk'],
                                            'garden-and-balcony' => ['name' => 'Garden & Balcony', 'icon' => 'flower'],
                                        ];
                                    @endphp
                                    @foreach($product->room_category as $roomSlug)
                                        @if(isset($roomData[$roomSlug]))
                                            <span class="inline-flex items-center rounded-full bg-primary/10 text-primary px-3 py-1 text-sm font-medium">
                                                <i data-lucide="{{ $roomData[$roomSlug]['icon'] }}" class="w-4 h-4 mr-1.5"></i>
                                                {{ $roomData[$roomSlug]['name'] }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <div class="flex items-center gap-3 pt-4 border-t border-stone-200 dark:border-strokedark">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($product->featured)
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        Featured
                                    </span>
                                @endif
                                <a href="{{ admin_route('products.edit', $product) }}" class="ml-auto inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-blue-600 px-4 py-2 text-center font-medium text-white hover:from-emerald-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                    Edit Product
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Card -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                                    <i data-lucide="dollar-sign" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Pricing</h3>
                            </div>
                            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Product pricing and profit information</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Regular Price:</span>
                                <span class="font-semibold text-black dark:text-white text-lg">₱{{ number_format($product->price, 2) }}</span>
                            </div>
                            @if($product->cost_price)
                                <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">Cost Price:</span>
                                    <span class="font-semibold text-black dark:text-white">₱{{ number_format($product->cost_price, 2) }}</span>
                                </div>
                            @endif
                            @if($product->sale_price)
                                <div class="flex justify-between items-center py-2 border-b border-stone-100 dark:border-stone-700">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">Sale Price:</span>
                                    <span class="font-semibold text-green-600 dark:text-green-400 text-lg">₱{{ number_format($product->sale_price, 2) }}</span>
                                </div>
                            @endif
                            @if($product->cost_price)
                                <div class="flex justify-between items-center py-2 pt-4 border-t border-stone-200 dark:border-strokedark">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">Profit Margin:</span>
                                    <span class="font-semibold text-green-600 dark:text-green-400 text-lg">{{ number_format((($product->price - $product->cost_price) / $product->price) * 100, 1) }}%</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description Card -->
                    @if($product->description)
                        <div class="bg-gradient-to-br from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden xl:col-span-2">
                            <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl">
                                        <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Description</h3>
                                </div>
                                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Product description and details</p>
                            </div>
                            <div class="p-6">
                                <div class="prose max-w-none text-gray-700 dark:text-gray-300">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Product Images Card -->
                    <div class="bg-gradient-to-br from-cyan-50 to-teal-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden xl:col-span-2">
                        <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl">
                                    <i data-lucide="image" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Product Images</h3>
                            </div>
                            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Visual representation of the product</p>
                        </div>
                        <div class="p-6">
                        @if($product->images && count($product->images) > 0)
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach($product->images as $index => $image)
                                        <div class="group relative rounded-xl overflow-hidden border border-stone-200 dark:border-strokedark hover:shadow-lg transition-all duration-200">
                                            <img src="{{ Storage::url($image) }}" alt="{{ $product->name }} - Image {{ $index + 1 }}" class="h-48 w-full object-cover">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 transition-all group-hover:bg-opacity-50">
                                                <button class="hidden text-white group-hover:block transform hover:scale-110 transition-transform" onclick="openImageModal('{{ Storage::url($image) }}')">
                                                    <i data-lucide="zoom-in" class="h-8 w-8"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                                <div class="flex h-48 items-center justify-center rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                                    <div class="text-center">
                                        <i data-lucide="image" class="h-12 w-12 text-gray-400 mx-auto mb-2"></i>
                                <p class="text-gray-500 dark:text-gray-400">No images uploaded</p>
                                    </div>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock History Tab -->
            <div id="stock-tab" class="tab-content hidden">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold text-black dark:text-white">Stock Movement History</h3>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Track all stock adjustments and movements</p>
                    </div>
                    <button class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-blue-600 px-4 py-2.5 text-white hover:from-emerald-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Adjust Stock
                    </button>
                </div>

                <div class="bg-white dark:bg-boxdark rounded-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                            <thead class="bg-gradient-to-r from-stone-50 to-gray-50 dark:from-gray-800 dark:to-gray-700">
                                <tr>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Date</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Type</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Quantity</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Reason</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">User</th>
                            </tr>
                        </thead>
                            <tbody class="divide-y divide-stone-200 dark:divide-strokedark">
                                <tr class="hover:bg-stone-50 dark:hover:bg-gray-800 transition-colors">
                                    <td class="py-4 px-6 text-sm text-gray-900 dark:text-white">{{ $product->created_at->format('M d, Y H:i') }}</td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Initial Stock
                                    </span>
                                </td>
                                    <td class="py-4 px-6 text-sm font-semibold text-green-600 dark:text-green-400">+{{ $product->stock_quantity }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-400">Product created</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-400">System</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div id="reviews-tab" class="tab-content hidden">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold text-black dark:text-white">Product Reviews</h3>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Customer feedback and ratings</p>
                    </div>
                    <div class="flex items-center gap-4 bg-gradient-to-br from-yellow-50 to-amber-50 dark:from-gray-800 dark:to-gray-700 rounded-xl px-6 py-4 border border-stone-200 dark:border-strokedark">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Average Rating:</span>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star" class="h-5 w-5 {{ $i <= floor($product->reviews()->avg('rating') ?? 0) ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <span class="ml-2 text-lg font-bold text-gray-900 dark:text-white">
                                {{ $product->reviews()->avg('rating') ? number_format($product->reviews()->avg('rating'), 1) : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($product->reviews()->count() > 0)
                    <div class="space-y-4">
                        @foreach($product->reviews()->with('user')->latest()->take(10)->get() as $review)
                            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-stone-200 dark:border-strokedark p-6 hover:shadow-lg transition-all duration-200">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="flex items-center gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i data-lucide="star" class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $review->user->first_name }} {{ $review->user->last_name }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->format('M d, Y') }}</span>
                                        </div>
                                        @if($review->title)
                                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $review->title }}</h4>
                                        @endif
                                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $review->review }}</p>
                                    </div>
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $review->is_approved ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                        {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-stone-50 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-stone-200 dark:border-strokedark">
                        <i data-lucide="message-circle" class="mx-auto h-16 w-16 text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">No reviews yet</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">This product hasn't received any reviews</p>
                    </div>
                @endif
            </div>

            <!-- Analytics Tab -->
            <div id="analytics-tab" class="tab-content hidden">
                <div class="mb-6">
                    <h3 class="text-2xl font-semibold text-black dark:text-white">Product Analytics</h3>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Sales performance and inventory insights</p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Sales Performance Card -->
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                    <i data-lucide="trending-up" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Sales Performance</h3>
                            </div>
                            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Product sales metrics and revenue</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Total Units Sold:</span>
                                <span class="font-bold text-black dark:text-white text-lg">{{ $product->orderItems()->sum('quantity') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Total Revenue:</span>
                                <span class="font-bold text-green-600 dark:text-green-400 text-lg">₱{{ number_format($product->orderItems()->sum(DB::raw('quantity * unit_price')), 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Average Order Value:</span>
                                <span class="font-bold text-black dark:text-white text-lg">₱{{ $product->orderItems()->count() > 0 ? number_format($product->orderItems()->sum(DB::raw('quantity * unit_price')) / $product->orderItems()->count(), 2) : '0.00' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Status Card -->
                    <div class="bg-gradient-to-br from-amber-50 to-yellow-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-stone-200 dark:border-strokedark overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-200 dark:border-strokedark">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl">
                                    <i data-lucide="warehouse" class="w-5 h-5 text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Inventory Status</h3>
                            </div>
                            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Current stock levels and alerts</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Current Stock:</span>
                                <span class="font-bold text-lg {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-red-600 dark:text-red-400' : 'text-black dark:text-white' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-stone-100 dark:border-stone-700">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Low Stock Threshold:</span>
                                <span class="font-bold text-black dark:text-white">{{ $product->low_stock_threshold }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Stock Status:</span>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $product->stock_quantity <= $product->low_stock_threshold ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                    {{ $product->stock_quantity <= $product->low_stock_threshold ? 'Low Stock' : 'In Stock' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-75 z-50" style="z-index: 2147483647 !important;">
    <div class="relative max-w-4xl max-h-full p-4">
        <button onclick="closeImageModal()" class="absolute -right-4 -top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white text-gray-600 hover:bg-gray-100 shadow-lg transition-all">
            <i data-lucide="x" class="h-6 w-6"></i>
        </button>
        <img id="modal-image" src="" alt="" class="max-h-[90vh] max-w-full rounded-xl shadow-2xl">
    </div>
</div>

@push('scripts')
<script>
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-primary', 'text-primary', 'font-semibold');
        button.classList.add('border-transparent', 'text-gray-500', 'font-medium');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
    if (activeButton) {
        activeButton.classList.add('active', 'border-primary', 'text-primary', 'font-semibold');
        activeButton.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
    }
    
    // Refresh Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function openImageModal(imageSrc) {
    document.getElementById('modal-image').src = imageSrc;
    document.getElementById('image-modal').classList.remove('hidden');
    document.getElementById('image-modal').classList.add('flex');
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
    document.getElementById('image-modal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('image-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
@endpush
@endsection
