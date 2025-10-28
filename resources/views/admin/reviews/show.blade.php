@extends('admin.layouts.app')

@section('title', 'Review Details')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Review Details
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ admin_route('reviews.index') }}">Reviews /</a>
            </li>
            <li class="font-medium text-primary">Review #{{ $review->id }}</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Review Info Card -->
    <div class="lg:col-span-1">
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex flex-col items-center text-center">
                <!-- Review Rating -->
                <div class="relative mb-4">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-xl">{{ $review->rating }}</span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-yellow-500 border-2 border-white dark:border-boxdark flex items-center justify-center">
                        <i data-lucide="star" class="w-3 h-3 text-white"></i>
                    </div>
                </div>

                <!-- Review Status -->
                <div class="mb-6">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                        @if($review->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                        @elseif($review->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                        @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                        @if($review->status === 'approved')
                            <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                            Approved
                        @elseif($review->status === 'pending')
                            <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                            Pending
                        @else
                            <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                            Rejected
                        @endif
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 w-full">
                    @if($review->status === 'pending')
                        <form action="{{ admin_route('reviews.approve', $review) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-lg border border-green-500 bg-green-500 px-4 py-2 text-white hover:bg-green-600 transition-colors duration-200">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                Approve
                            </button>
                        </form>
                        <form action="{{ admin_route('reviews.reject', $review) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-lg border border-red-500 bg-red-500 px-4 py-2 text-white hover:bg-red-600 transition-colors duration-200">
                                <i data-lucide="x" class="w-4 h-4"></i>
                                Reject
                            </button>
                        </form>
                    @else
                        <a href="{{ admin_route('reviews.edit', $review) }}" class="flex-1 flex items-center justify-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                            Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Review Stats -->
        <div class="mt-6 rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Review Information</h4>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Rating</span>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <i data-lucide="star" class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Helpful Votes</span>
                    <span class="font-semibold text-black dark:text-white">{{ $review->helpful_votes ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Not Helpful Votes</span>
                    <span class="font-semibold text-black dark:text-white">{{ $review->not_helpful_votes ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Created</span>
                    <span class="font-semibold text-black dark:text-white">{{ $review->created_at->format('M d, Y') }}</span>
                </div>
                @if($review->updated_at != $review->created_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Last Updated</span>
                        <span class="font-semibold text-black dark:text-white">{{ $review->updated_at->format('M d, Y') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer Info -->
        <div class="mt-6 rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Customer Information</h4>
            <div class="flex items-center gap-3 mb-4">
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">
                        {{ substr($review->user->first_name, 0, 1) }}{{ substr($review->user->last_name, 0, 1) }}
                    </span>
                </div>
                <div>
                    <h5 class="font-medium text-black dark:text-white">{{ $review->user->first_name }} {{ $review->user->last_name }}</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $review->user->email }}</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Member Since</span>
                    <span class="font-semibold text-black dark:text-white">{{ $review->user->created_at->format('M Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Reviews</span>
                    <span class="font-semibold text-black dark:text-white">{{ $review->user->reviews->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:col-span-2">
        <!-- Tabs -->
        <div class="mb-6" x-data="{ activeTab: 'review' }">
            <div class="border-b border-stroke dark:border-strokedark">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'review'" :class="activeTab === 'review' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Review Content
                    </button>
                    <button @click="activeTab = 'product'" :class="activeTab === 'product' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Product Details
                    </button>
                    <button @click="activeTab = 'response'" :class="activeTab === 'response' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Admin Response
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-6">
                <!-- Review Content Tab -->
                <div x-show="activeTab === 'review'" x-transition>
                    <div class="space-y-6">
                        <!-- Review Content -->
                        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Review Content</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Title</label>
                                    <p class="text-black dark:text-white font-medium">{{ $review->title ?: 'No title provided' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Review</label>
                                    <div class="mt-2 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $review->content }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Review Images -->
                        @if($review->images && count($review->images) > 0)
                            <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                                <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Review Images</h4>
                                <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
                                    @foreach($review->images as $image)
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Review image" class="w-full h-32 object-cover rounded-lg border border-stroke dark:border-strokedark">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Review Metadata -->
                        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Review Metadata</h4>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Verified Purchase</label>
                                    <p class="text-black dark:text-white">
                                        @if($review->verified_purchase)
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
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Anonymous</label>
                                    <p class="text-black dark:text-white">
                                        @if($review->is_anonymous)
                                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                <i data-lucide="eye-off" class="w-3 h-3 mr-1"></i>
                                                Yes
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                                                No
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details Tab -->
                <div x-show="activeTab === 'product'" x-transition>
                    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                        <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Product Details</h4>
                        <div class="flex items-start gap-4">
                            @if($review->product->images && count($review->product->images) > 0)
                                <img src="{{ asset('storage/' . $review->product->images[0]) }}" alt="{{ $review->product->name }}" class="w-20 h-20 object-cover rounded-lg border border-stroke dark:border-strokedark">
                            @else
                                <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-lg border border-stroke dark:border-strokedark flex items-center justify-center">
                                    <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h5 class="font-semibold text-black dark:text-white mb-2">{{ $review->product->name }}</h5>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">{{ $review->product->description }}</p>
                                <div class="flex items-center gap-4">
                                    <span class="text-lg font-bold text-primary">₱{{ number_format($review->product->price, 2) }}</span>
                                    <a href="{{ admin_route('products.show', $review->product) }}" class="text-primary hover:text-primary/80 transition-colors duration-200">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Response Tab -->
                <div x-show="activeTab === 'response'" x-transition>
                    <div class="space-y-6">
                        <!-- Current Response -->
                        @if($review->admin_response)
                            <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                                <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Current Admin Response</h4>
                                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $review->admin_response }}</p>
                                </div>
                                <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                                    <p>Responded by: {{ $review->respondedBy->first_name }} {{ $review->respondedBy->last_name }}</p>
                                    <p>Date: {{ $review->responded_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Add/Edit Response -->
                        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">
                                {{ $review->admin_response ? 'Update Admin Response' : 'Add Admin Response' }}
                            </h4>
                            <form action="{{ admin_route('reviews.respond', $review) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="admin_response" class="mb-2.5 block text-black dark:text-white">
                                        Response
                                    </label>
                                    <textarea
                                        id="admin_response"
                                        name="admin_response"
                                        rows="4"
                                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('admin_response') border-red-500 @enderror"
                                        placeholder="Enter your response to this review..."
                                    >{{ old('admin_response', $review->admin_response) }}</textarea>
                                    @error('admin_response')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex items-center justify-end gap-4">
                                    @if($review->admin_response)
                                        <button type="submit" name="action" value="update" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                                            <i data-lucide="save" class="w-4 h-4"></i>
                                            Update Response
                                        </button>
                                        <button type="submit" name="action" value="delete" class="flex items-center gap-2 rounded-lg border border-red-500 bg-red-500 px-4 py-2 text-white hover:bg-red-600 transition-colors duration-200">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            Delete Response
                                        </button>
                                    @else
                                        <button type="submit" name="action" value="create" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                                            <i data-lucide="message-square" class="w-4 h-4"></i>
                                            Add Response
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
