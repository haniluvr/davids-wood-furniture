@extends('admin.layouts.app')

@section('title', 'Product Reviews')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-stone-900">Product Reviews</h1>
                <p class="mt-1 text-sm text-stone-600">Manage and moderate product reviews</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ admin_route('reviews.export') }}" class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Reviews
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Reviews -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Total Reviews</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ $reviews->total() }}</p>
                    </div>
                </div>
            </div>

            <!-- Average Rating -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Average Rating</p>
                        <div class="flex items-center">
                            <p class="text-2xl font-semibold text-stone-900">{{ number_format($averageRating, 1) }}</p>
                            <div class="ml-2 flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Reviews -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Pending Reviews</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ $pendingCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Approved Reviews -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Approved Reviews</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ $approvedCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6 mb-6">
            <form method="GET" action="{{ admin_route('reviews.index') }}" class="flex flex-wrap items-end gap-4 justify-between">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-stone-700 mb-2">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Search by product or customer..."
                           class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-stone-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="rating" class="block text-sm font-medium text-stone-700 mb-2">Rating</label>
                    <select id="rating" name="rating" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Ratings</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date_range" class="block text-sm font-medium text-stone-700 mb-2">Date Range</label>
                    <select id="date_range" name="date_range" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 whitespace-nowrap">
                        Filter
                    </button>
                    <a href="{{ admin_route('reviews.index') }}" class="inline-flex items-center justify-center rounded-lg border border-stone-300 px-4 py-2.5 text-sm hover:bg-stone-50">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </a>
                </div>
            </form>
        </div>

    <!-- Reviews Table -->
        <div class="bg-white rounded-xl shadow-sm border border-stone-200">
            @if($reviews->count() > 0)
                <!-- Bulk Actions -->
                <div class="px-6 py-4 border-b border-stone-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <input type="checkbox" id="selectAll" class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm text-stone-600">Select All</span>
                        </div>
                        @php
                            $admin = auth()->guard('admin')->user();
                            $isSuperAdmin = $admin && $admin->isSuperAdmin();
                            $canModerate = $admin && ($isSuperAdmin || $admin->hasPermission('reviews.moderate'));
                            $canDelete = $admin && ($isSuperAdmin || $admin->hasPermission('reviews.delete'));
                        @endphp
                        @if($canModerate || $canDelete)
                        <div class="flex items-center space-x-2">
                            @if($canModerate)
                            <button onclick="bulkAction('approve')" class="inline-flex items-center px-3 py-1 border border-green-300 rounded text-sm text-green-700 bg-green-50 hover:bg-green-100">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                </svg>
                                Approve Selected
                            </button>
                            <button onclick="bulkAction('reject')" class="inline-flex items-center px-3 py-1 border border-red-300 rounded text-sm text-red-700 bg-red-50 hover:bg-red-100">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject Selected
                            </button>
                            @endif
                            @if($canDelete)
                            <button onclick="bulkAction('delete')" class="inline-flex items-center px-3 py-1 border border-red-300 rounded text-sm text-red-700 bg-red-50 hover:bg-red-100">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Selected
                            </button>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAllHeader" class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500">
                                    </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Review</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                        <tbody class="bg-white divide-y divide-stone-200">
                            @foreach($reviews as $review)
                                <tr class="hover:bg-stone-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="selected_reviews[]" value="{{ $review->id }}" class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($review->product->images && count($review->product->images) > 0)
                                                    <img class="h-10 w-10 rounded-lg object-cover" src="{{ $review->product->images[0]['url'] ?? asset('images/placeholder.jpg') }}" alt="{{ $review->product->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-lg bg-stone-100 flex items-center justify-center">
                                                        <svg class="h-5 w-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-stone-900">{{ $review->product->name }}</div>
                                                <div class="text-sm text-stone-500">SKU: {{ $review->product->sku }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-emerald-600">
                                                        {{ substr($review->user->first_name, 0, 1) }}{{ substr($review->user->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-stone-900">{{ $review->user->first_name }} {{ $review->user->last_name }}</div>
                                                <div class="text-sm text-stone-500">{{ $review->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-stone-600">{{ $review->rating }}/5</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-stone-900">
                                            @if($review->title)
                                                <div class="font-medium">{{ $review->title }}</div>
                                            @endif
                                            <div class="mt-1 text-stone-600">{{ Str::limit($review->review, 100) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $review->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                        </span>
                                        @if($review->is_verified_purchase)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Verified
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        {{ $review->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ admin_route('reviews.show', $review) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-150" title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            @php
                                                $admin = auth()->guard('admin')->user();
                                                $isSuperAdmin = $admin && $admin->isSuperAdmin();
                                                $canModerate = $admin && ($isSuperAdmin || $admin->hasPermission('reviews.moderate'));
                                                $canDelete = $admin && ($isSuperAdmin || $admin->hasPermission('reviews.delete'));
                                            @endphp
                                            @if($canModerate)
                                                @if(!$review->is_approved)
                                                    <button onclick="approveReview({{ $review->id }})" class="text-green-600 hover:text-green-900 transition-colors duration-150" title="Approve">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                                @if($review->is_approved)
                                                    <button onclick="rejectReview({{ $review->id }})" class="text-red-600 hover:text-red-900 transition-colors duration-150" title="Reject">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            @endif
                                            @if($canDelete)
                                                <button onclick="deleteReview({{ $review->id }})" class="text-red-600 hover:text-red-900 transition-colors duration-150" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                <!-- Pagination -->
                @if($reviews->hasPages())
                    @include('admin.partials.pagination', ['paginator' => $reviews])
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-stone-900">No reviews found</h3>
                    <p class="mt-1 text-sm text-stone-500">No reviews match your current filters.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllHeaderCheckbox = document.getElementById('selectAllHeader');
    const reviewCheckboxes = document.querySelectorAll('input[name="selected_reviews[]"]');

    function updateSelectAll() {
        const checkedCount = document.querySelectorAll('input[name="selected_reviews[]"]:checked').length;
        const totalCount = reviewCheckboxes.length;
        
        selectAllCheckbox.checked = checkedCount === totalCount;
        selectAllHeaderCheckbox.checked = checkedCount === totalCount;
    }

    selectAllCheckbox.addEventListener('change', function() {
        reviewCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectAll();
    });

    selectAllHeaderCheckbox.addEventListener('change', function() {
        reviewCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectAll();
    });

    reviewCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAll);
    });
});

@php
    $admin = auth()->guard('admin')->user();
    $isSuperAdmin = $admin && $admin->isSuperAdmin();
    $canModerate = $admin && ($isSuperAdmin || $admin->hasPermission('reviews.moderate'));
    $canDelete = $admin && ($isSuperAdmin || $admin->hasPermission('reviews.delete'));
@endphp

function approveReview(reviewId) {
    @if(!$canModerate)
        showPermissionDenied('reviews.moderate');
        return;
    @endif
    
    if (confirm('Are you sure you want to approve this review?')) {
        fetch(`{{ admin_route('reviews.approve', '') }}/${reviewId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.status === 403) {
                return response.json().then(data => {
                    showPermissionDenied(data.permission || 'reviews.moderate');
                    throw new Error('Permission denied');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error approving review');
            }
        })
        .catch(error => {
            if (error.message !== 'Permission denied') {
                console.error('Error:', error);
                alert('Error approving review');
            }
        });
    }
}

function rejectReview(reviewId) {
    @if(!$canModerate)
        showPermissionDenied('reviews.moderate');
        return;
    @endif
    
    if (confirm('Are you sure you want to reject this review?')) {
        fetch(`{{ admin_route('reviews.reject', '') }}/${reviewId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.status === 403) {
                return response.json().then(data => {
                    showPermissionDenied(data.permission || 'reviews.moderate');
                    throw new Error('Permission denied');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error rejecting review');
            }
        })
        .catch(error => {
            if (error.message !== 'Permission denied') {
                console.error('Error:', error);
                alert('Error rejecting review');
            }
        });
    }
}

function deleteReview(reviewId) {
    @if(!$canDelete)
        showPermissionDenied('reviews.delete');
        return;
    @endif
    
    if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
        fetch(`{{ admin_route('reviews.index') }}/${reviewId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.status === 403) {
                return response.json().then(data => {
                    showPermissionDenied(data.permission || 'reviews.delete');
                    throw new Error('Permission denied');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting review');
            }
        })
        .catch(error => {
            if (error.message !== 'Permission denied') {
                console.error('Error:', error);
                alert('Error deleting review');
            }
        });
    }
}

function bulkAction(action) {
    // Check permissions before showing confirmation
    @if($canModerate && $canDelete)
    // User has both permissions, allow all actions
    @elseif($canModerate && !$canDelete)
    if (action === 'delete') {
        showPermissionDenied('reviews.delete');
        return;
    }
    @elseif(!$canModerate && $canDelete)
    if (action === 'approve' || action === 'reject') {
        showPermissionDenied('reviews.moderate');
        return;
    }
    @else
    // No permissions
    if (action === 'approve' || action === 'reject') {
        showPermissionDenied('reviews.moderate');
    } else if (action === 'delete') {
        showPermissionDenied('reviews.delete');
    }
    return;
    @endif
    
    const selectedReviews = document.querySelectorAll('input[name="selected_reviews[]"]:checked');
    
    if (selectedReviews.length === 0) {
        alert('Please select at least one review.');
        return;
    }
    
    const actionText = action === 'approve' ? 'approve' : action === 'reject' ? 'reject' : 'delete';
    const confirmText = `Are you sure you want to ${actionText} ${selectedReviews.length} review(s)?`;
    
    if (confirm(confirmText)) {
        const reviewIds = Array.from(selectedReviews).map(checkbox => checkbox.value);
        
        fetch(`{{ admin_route('reviews.bulk-approve') }}`.replace('bulk-approve', `bulk-${action}`), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                review_ids: reviewIds
            })
        })
        .then(response => {
            if (response.status === 403) {
                return response.json().then(data => {
                    showPermissionDenied(data.permission || `reviews.${action === 'delete' ? 'delete' : 'moderate'}`);
                    throw new Error('Permission denied');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(`Error ${actionText}ing reviews`);
            }
        })
        .catch(error => {
            if (error.message !== 'Permission denied') {
                console.error('Error:', error);
                alert(`Error ${actionText}ing reviews`);
            }
        });
    }
}

function showPermissionDenied(permission) {
    // Create a simple modal overlay
    const modal = document.createElement('div');
    modal.id = 'permission-denied-modal-temp';
    modal.className = 'fixed inset-0 z-99999 flex items-center justify-center bg-black bg-opacity-50';
    modal.innerHTML = `
        <div class="relative bg-white dark:bg-boxdark rounded-2xl shadow-2xl max-w-md w-full mx-4">
            <div class="flex items-center justify-between p-6 border-b border-stroke dark:border-strokedark">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 dark:bg-red-900/30">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Access Denied</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Permission Required</p>
                    </div>
                </div>
                <button onclick="this.closest('#permission-denied-modal-temp').remove()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    You do not have permission to perform this action.
                </p>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 mb-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Required Permission:</p>
                    <p class="text-sm font-mono text-gray-900 dark:text-white">${permission}</p>
                </div>
                <div class="flex justify-end">
                    <button onclick="this.closest('#permission-denied-modal-temp').remove()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Close on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
    
    // Close on Escape key
    const handleEscape = function(e) {
        if (e.key === 'Escape') {
            modal.remove();
            document.removeEventListener('keydown', handleEscape);
        }
    };
    document.addEventListener('keydown', handleEscape);
    
    document.body.appendChild(modal);
}
</script>
@endsection