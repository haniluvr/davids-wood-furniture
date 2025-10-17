@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            Product Details
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('admin.dashboard') }}" class="font-medium">Dashboard</a></li>
                <li class="font-medium text-primary">/</li>
                <li><a href="{{ route('admin.products.index') }}" class="font-medium">Products</a></li>
                <li class="font-medium text-primary">/</li>
                <li class="font-medium text-primary">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>

    <!-- Product Header -->
    <div class="mb-6 rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
                @if($product->images && count($product->images) > 0)
                    <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" class="h-16 w-16 rounded-lg object-cover">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                        <i data-lucide="image" class="h-8 w-8 text-gray-400"></i>
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-black dark:text-white">{{ $product->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($product->featured)
                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                        Featured
                    </span>
                @endif
                <a
                    href="{{ route('admin.products.edit', $product) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-white hover:bg-opacity-90"
                >
                    <i data-lucide="edit" class="h-4 w-4"></i>
                    Edit Product
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Stock Quantity</p>
                    <p class="text-2xl font-bold text-black dark:text-white">{{ $product->stock_quantity }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                    <i data-lucide="package" class="h-6 w-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Regular Price</p>
                    <p class="text-2xl font-bold text-black dark:text-white">${{ number_format($product->price, 2) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                    <i data-lucide="dollar-sign" class="h-6 w-6 text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                    <p class="text-2xl font-bold text-black dark:text-white">{{ $product->orderItems()->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                    <i data-lucide="shopping-cart" class="h-6 w-6 text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Average Rating</p>
                    <p class="text-2xl font-bold text-black dark:text-white">{{ $product->reviews()->avg('rating') ? number_format($product->reviews()->avg('rating'), 1) : 'N/A' }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <i data-lucide="star" class="h-6 w-6 text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="rounded-xl border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
        <!-- Tab Navigation -->
        <div class="border-b border-stroke dark:border-strokedark">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button
                    onclick="switchTab('overview')"
                    class="tab-button active border-b-2 border-primary py-4 px-1 text-sm font-medium text-primary"
                    data-tab="overview"
                >
                    Overview
                </button>
                <button
                    onclick="switchTab('stock')"
                    class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                    data-tab="stock"
                >
                    Stock History
                </button>
                <button
                    onclick="switchTab('reviews')"
                    class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                    data-tab="reviews"
                >
                    Reviews ({{ $product->reviews()->count() }})
                </button>
                <button
                    onclick="switchTab('analytics')"
                    class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                    data-tab="analytics"
                >
                    Analytics
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Overview Tab -->
            <div id="overview-tab" class="tab-content">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Product Information -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Product Information</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Name:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">SKU:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->sku }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Category:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->category->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Barcode:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->barcode ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Weight:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->weight ? $product->weight . ' lbs' : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Dimensions:</span>
                                    <span class="font-medium text-black dark:text-white">{{ $product->dimensions ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Pricing</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Regular Price:</span>
                                    <span class="font-medium text-black dark:text-white">${{ number_format($product->price, 2) }}</span>
                                </div>
                                @if($product->cost_price)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Cost Price:</span>
                                        <span class="font-medium text-black dark:text-white">${{ number_format($product->cost_price, 2) }}</span>
                                    </div>
                                @endif
                                @if($product->sale_price)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Sale Price:</span>
                                        <span class="font-medium text-green-600">${{ number_format($product->sale_price, 2) }}</span>
                                    </div>
                                @endif
                                @if($product->cost_price)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Profit Margin:</span>
                                        <span class="font-medium text-green-600">{{ number_format((($product->price - $product->cost_price) / $product->price) * 100, 1) }}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Product Images</h3>
                        @if($product->images && count($product->images) > 0)
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($product->images as $image)
                                    <div class="group relative">
                                        <img src="{{ Storage::url($image) }}" alt="{{ $product->name }}" class="h-32 w-full rounded-lg object-cover">
                                        <div class="absolute inset-0 flex items-center justify-center rounded-lg bg-black bg-opacity-0 transition-all group-hover:bg-opacity-50">
                                            <button class="hidden text-white group-hover:block" onclick="openImageModal('{{ Storage::url($image) }}')">
                                                <i data-lucide="zoom-in" class="h-6 w-6"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex h-32 items-center justify-center rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                                <p class="text-gray-500 dark:text-gray-400">No images uploaded</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($product->description)
                    <div class="mt-6">
                        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Description</h3>
                        <div class="prose max-w-none text-gray-700 dark:text-gray-300">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Stock History Tab -->
            <div id="stock-tab" class="tab-content hidden">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black dark:text-white">Stock Movement History</h3>
                    <button class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-white hover:bg-opacity-90">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Adjust Stock
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-stroke dark:border-strokedark">
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Date</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Type</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Quantity</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Reason</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">User</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-stroke dark:border-strokedark">
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $product->created_at->format('M d, Y H:i') }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Initial Stock
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">+{{ $product->stock_quantity }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Product created</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">System</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div id="reviews-tab" class="tab-content hidden">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-black dark:text-white">Product Reviews</h3>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Average Rating:</span>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star" class="h-4 w-4 {{ $i <= ($product->reviews()->avg('rating') ?? 0) ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $product->reviews()->avg('rating') ? number_format($product->reviews()->avg('rating'), 1) : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($product->reviews()->count() > 0)
                    <div class="space-y-4">
                        @foreach($product->reviews()->with('user')->latest()->take(10)->get() as $review)
                            <div class="rounded-lg border border-stroke bg-gray-50 p-4 dark:border-strokedark dark:bg-gray-800">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i data-lucide="star" class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $review->user->first_name }} {{ $review->user->last_name }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->format('M d, Y') }}</span>
                                        </div>
                                        @if($review->title)
                                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $review->title }}</h4>
                                        @endif
                                        <p class="text-gray-700 dark:text-gray-300">{{ $review->review }}</p>
                                    </div>
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $review->is_approved ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                        {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="message-circle" class="mx-auto h-12 w-12 text-gray-400"></i>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">No reviews yet</p>
                    </div>
                @endif
            </div>

            <!-- Analytics Tab -->
            <div id="analytics-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="rounded-lg border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-gray-800">
                        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Sales Performance</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Units Sold:</span>
                                <span class="font-medium text-black dark:text-white">{{ $product->orderItems()->sum('quantity') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Revenue:</span>
                                <span class="font-medium text-black dark:text-white">${{ number_format($product->orderItems()->sum(DB::raw('quantity * price')), 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Average Order Value:</span>
                                <span class="font-medium text-black dark:text-white">${{ number_format($product->orderItems()->avg(DB::raw('quantity * price')), 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-gray-800">
                        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Inventory Status</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Current Stock:</span>
                                <span class="font-medium {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-red-600' : 'text-black dark:text-white' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Low Stock Threshold:</span>
                                <span class="font-medium text-black dark:text-white">{{ $product->low_stock_threshold }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Stock Status:</span>
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $product->stock_quantity <= $product->low_stock_threshold ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
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
<div id="image-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="relative max-w-4xl max-h-full p-4">
        <button onclick="closeImageModal()" class="absolute -right-4 -top-4 flex h-8 w-8 items-center justify-center rounded-full bg-white text-gray-600 hover:bg-gray-100">
            <i data-lucide="x" class="h-5 w-5"></i>
        </button>
        <img id="modal-image" src="" alt="" class="max-h-full max-w-full rounded-lg">
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
        button.classList.remove('active', 'border-primary', 'text-primary');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
    activeButton.classList.add('active', 'border-primary', 'text-primary');
    activeButton.classList.remove('border-transparent', 'text-gray-500');
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
</script>
@endpush
@endsection
