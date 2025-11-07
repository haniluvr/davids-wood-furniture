@extends('admin.layouts.app')

@section('title', 'RMA Details - ' . $returnRepair->rma_number)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="refresh-cw" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">RMA Details</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">{{ $returnRepair->rma_number }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ admin_route('orders.returns-repairs.edit', $returnRepair) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Edit
                </a>
                <a href="{{ admin_route('orders.returns-repairs.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to Returns & Repairs
                </a>
            </div>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="xl:col-span-2 space-y-8">
            <!-- RMA Status & Actions -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                            <i data-lucide="activity" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Status & Actions</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Current status and available actions</p>
                </div>
                <div class="p-8 space-y-6">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-medium text-stone-700 dark:text-stone-300">Current Status:</span>
                            <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium
                                @if($returnRepair->status === 'requested') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @elseif($returnRepair->status === 'approved') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                @elseif($returnRepair->status === 'in_progress') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                @elseif($returnRepair->status === 'received') bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300
                                @elseif($returnRepair->status === 'completed' || $returnRepair->status === 'refunded') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif($returnRepair->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $returnRepair->status)) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($returnRepair->status === 'requested')
                                <button onclick="approveRMA({{ $returnRepair->id }})" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                                    Approve
                                </button>
                                <button onclick="rejectRMA({{ $returnRepair->id }})" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                    Reject
                                </button>
                            @elseif($returnRepair->status === 'approved')
                                <button onclick="markReceived({{ $returnRepair->id }})" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                                    <i data-lucide="package" class="w-4 h-4 mr-2"></i>
                                    Mark as Received
                                </button>
                            @elseif($returnRepair->status === 'received' && $returnRepair->type === 'return')
                                <button onclick="processRefund({{ $returnRepair->id }})" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i>
                                    Process Refund
                                </button>
                            @elseif($returnRepair->status === 'received' || $returnRepair->status === 'in_progress')
                                <button onclick="markCompleted({{ $returnRepair->id }})" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                                    Mark as Completed
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request Details -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl">
                            <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Request Details</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Information about the return/repair request</p>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Request Type</label>
                            <p class="text-stone-900 dark:text-white font-medium">{{ ucfirst($returnRepair->type) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Reason</label>
                            <p class="text-stone-900 dark:text-white font-medium">{{ ucfirst(str_replace('_', ' ', $returnRepair->reason)) }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Description</label>
                        <p class="text-stone-900 dark:text-white">{{ $returnRepair->description }}</p>
                    </div>
                    @if($returnRepair->customer_notes)
                    <div>
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Customer Notes</label>
                        <p class="text-stone-900 dark:text-white">{{ $returnRepair->customer_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Products -->
            @if($returnRepair->order && $returnRepair->order->orderItems)
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-violet-50 to-fuchsia-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-violet-500 to-fuchsia-600 rounded-xl">
                            <i data-lucide="package" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Products</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Items included in this request</p>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        @foreach($returnRepair->order->orderItems as $item)
                        <div class="flex items-center gap-4 p-4 border border-stone-200 dark:border-strokedark rounded-lg">
                            @if($item->product && $item->product->images && is_array($item->product->images) && !empty($item->product->images) && isset($item->product->images[0]))
                                <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-stone-200 dark:bg-stone-700 rounded-lg flex items-center justify-center">
                                    <i data-lucide="package" class="w-8 h-8 text-stone-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-stone-900 dark:text-white">{{ $item->product->name ?? 'Product #' . $item->product_id }}</h4>
                                <p class="text-sm text-stone-600 dark:text-stone-400">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-stone-900 dark:text-white">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                                <p class="text-sm text-stone-600 dark:text-stone-400">₱{{ number_format($item->price, 2) }} each</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Photos -->
            @if($returnRepair->photos && count($returnRepair->photos) > 0)
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-cyan-50 to-teal-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl">
                            <i data-lucide="image" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Photos</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Photos submitted with this request</p>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($returnRepair->photos as $photo)
                        @php
                            // Use dynamic storage URL (S3 for production, local for local)
                            $photoUrl = function_exists('storage_url') ? storage_url($photo) : \Illuminate\Support\Facades\Storage::dynamic()->url($photo);
                        @endphp
                        <div class="relative group">
                            <img src="{{ $photoUrl }}" 
                                 alt="RMA Photo" 
                                 class="w-full h-48 object-cover rounded-lg border border-stone-200 dark:border-strokedark">
                            <a href="{{ $photoUrl }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity rounded-lg">
                                <i data-lucide="zoom-in" class="w-6 h-6 text-white opacity-0 group-hover:opacity-100"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Admin Notes -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-lime-50 to-green-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-lime-500 to-green-600 rounded-xl">
                            <i data-lucide="sticky-note" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Admin Notes</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Internal notes and comments</p>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Notes</label>
                            <p class="text-stone-900 dark:text-white whitespace-pre-wrap">{{ $returnRepair->admin_notes ?? 'No notes added yet.' }}</p>
                        </div>
                        <div class="flex justify-end">
                            <button onclick="showUpdateNotesModal()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                Update Notes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="xl:col-span-1 space-y-8">
            <!-- RMA Information -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl">
                            <i data-lucide="hash" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">RMA Information</h3>
                    </div>
                </div>
                <div class="p-8 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-400">RMA Number:</span>
                        <span class="text-stone-900 dark:text-white font-medium">{{ $returnRepair->rma_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-400">Created:</span>
                        <span class="text-stone-900 dark:text-white">{{ $returnRepair->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($returnRepair->approved_at)
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-400">Approved:</span>
                        <span class="text-stone-900 dark:text-white">{{ $returnRepair->approved_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($returnRepair->received_at)
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-400">Received:</span>
                        <span class="text-stone-900 dark:text-white">{{ $returnRepair->received_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($returnRepair->completed_at)
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-400">Completed:</span>
                        <span class="text-stone-900 dark:text-white">{{ $returnRepair->completed_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($returnRepair->processedBy)
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-400">Processed By:</span>
                        <span class="text-stone-900 dark:text-white">{{ $returnRepair->processedBy->name ?? 'N/A' }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            @if($returnRepair->order && $returnRepair->order->user)
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl">
                            <i data-lucide="user" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Customer</h3>
                    </div>
                </div>
                <div class="p-8 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center">
                            <span class="text-white font-medium text-sm">
                                {{ strtoupper(substr($returnRepair->order->user->first_name ?? '', 0, 1) . substr($returnRepair->order->user->last_name ?? '', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h4 class="font-medium text-stone-900 dark:text-white">{{ $returnRepair->order->user->first_name ?? 'Guest' }} {{ $returnRepair->order->user->last_name ?? '' }}</h4>
                            <p class="text-sm text-stone-600 dark:text-stone-400">{{ $returnRepair->order->user->email ?? 'No email' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Information -->
            @if($returnRepair->order)
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-violet-50 to-fuchsia-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-violet-500 to-fuchsia-600 rounded-xl">
                            <i data-lucide="receipt" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Order</h3>
                    </div>
                </div>
                <div class="p-8 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-400">Order Number:</span>
                        <a href="{{ admin_route('orders.show', $returnRepair->order) }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                            #{{ $returnRepair->order->order_number }}
                        </a>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-400">Order Date:</span>
                        <span class="text-stone-900 dark:text-white">{{ $returnRepair->order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-400">Total Amount:</span>
                        <span class="text-stone-900 dark:text-white font-semibold">₱{{ number_format($returnRepair->order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Refund Information -->
            @if($returnRepair->refund_amount || $returnRepair->refund_method)
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-cyan-50 to-teal-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl">
                            <i data-lucide="credit-card" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Refund</h3>
                    </div>
                </div>
                <div class="p-8 space-y-4">
                    @if($returnRepair->refund_amount)
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-400">Amount:</span>
                        <span class="text-stone-900 dark:text-white font-semibold">₱{{ number_format($returnRepair->refund_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($returnRepair->refund_method)
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-400">Method:</span>
                        <span class="text-stone-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $returnRepair->refund_method)) }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve RMA Confirmation Modal -->
<div id="approve-rma-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeApproveModal()"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Approve RMA Request</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400 mt-1">Are you sure you want to approve this RMA request?</p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeApproveModal()" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="button" onclick="confirmApproveRMA()" class="flex-1 rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-blue-700">
                    Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject RMA Modal -->
<div id="reject-rma-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeRejectModal()"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Reject RMA Request</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400 mt-1">Please provide a reason for rejection:</p>
            </div>
            <form id="reject-rma-form" onsubmit="confirmRejectRMA(event)">
                <div class="mb-4">
                    <textarea id="reject-reason" rows="4" required class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400" placeholder="Enter rejection reason..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 rounded-xl bg-red-600 px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-red-700">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mark as Received Confirmation Modal -->
<div id="mark-received-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeMarkReceivedModal()"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Mark as Received</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400 mt-1">Mark this RMA as received?</p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeMarkReceivedModal()" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="button" onclick="confirmMarkReceived()" class="flex-1 rounded-xl bg-cyan-600 px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-cyan-700">
                    Mark as Received
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Process Refund Modal -->
<div id="process-refund-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeProcessRefundModal()"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Process Refund</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400 mt-1">Enter refund details</p>
            </div>
            <form id="process-refund-form" onsubmit="confirmProcessRefund(event)">
                <div class="mb-4">
                    <label for="refund-amount" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Refund Amount (₱)</label>
                    <input type="number" id="refund-amount" step="0.01" min="0" required class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400" placeholder="0.00">
                </div>
                <div class="mb-4">
                    <label for="refund-method" class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">Refund Method</label>
                    <select id="refund-method" required class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                        <option value="">Select refund method...</option>
                        <option value="original_payment">Original Payment Method</option>
                        <option value="store_credit">Store Credit</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="check">Check</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeProcessRefundModal()" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 rounded-xl bg-green-600 px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-green-700">
                        Process Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mark as Completed Confirmation Modal -->
<div id="mark-completed-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeMarkCompletedModal()"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Mark as Completed</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400 mt-1">Mark this RMA as completed?</p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeMarkCompletedModal()" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="button" onclick="confirmMarkCompleted()" class="flex-1 rounded-xl bg-green-600 px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-green-700">
                    Mark as Completed
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Admin Notes Modal -->
<div id="update-notes-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeUpdateNotesModal()"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Update Admin Notes</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400 mt-1">Add or update admin notes for this RMA</p>
            </div>
            <form id="update-notes-form" onsubmit="confirmUpdateNotes(event)">
                <div class="mb-4">
                    <textarea id="admin-notes" rows="4" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400" placeholder="Enter admin notes...">{{ $returnRepair->admin_notes ?? '' }}</textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeUpdateNotesModal()" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-primary/90">
                        Update Notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentRMAId = null;

// Approve RMA Functions
function approveRMA(id) {
    currentRMAId = id;
    document.getElementById('approve-rma-modal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeApproveModal() {
    document.getElementById('approve-rma-modal').classList.add('hidden');
    currentRMAId = null;
}

function confirmApproveRMA() {
    if (!currentRMAId) return;
    
    const approveUrl = '{{ admin_route("orders.returns-repairs.approve", $returnRepair->id) }}'.replace('{{ $returnRepair->id }}', currentRMAId);
    fetch(approveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeApproveModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to approve RMA'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while approving the RMA');
    });
}

// Reject RMA Functions
function rejectRMA(id) {
    currentRMAId = id;
    document.getElementById('reject-reason').value = '';
    document.getElementById('reject-rma-modal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeRejectModal() {
    document.getElementById('reject-rma-modal').classList.add('hidden');
    currentRMAId = null;
}

function confirmRejectRMA(event) {
    event.preventDefault();
    if (!currentRMAId) return;
    
    const notes = document.getElementById('reject-reason').value.trim();
    if (!notes) {
        alert('Please provide a reason for rejection');
        return;
    }
    
    const rejectUrl = '{{ admin_route("orders.returns-repairs.reject", $returnRepair->id) }}'.replace('{{ $returnRepair->id }}', currentRMAId);
    fetch(rejectUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ admin_notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeRejectModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to reject RMA'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while rejecting the RMA');
    });
}

// Mark as Received Functions
function markReceived(id) {
    currentRMAId = id;
    document.getElementById('mark-received-modal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeMarkReceivedModal() {
    document.getElementById('mark-received-modal').classList.add('hidden');
    currentRMAId = null;
}

function confirmMarkReceived() {
    if (!currentRMAId) return;
    
    const receivedUrl = '{{ admin_route("orders.returns-repairs.received", $returnRepair->id) }}'.replace('{{ $returnRepair->id }}', currentRMAId);
    fetch(receivedUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeMarkReceivedModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to mark RMA as received'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while marking the RMA as received');
    });
}

// Process Refund Functions
function processRefund(id) {
    currentRMAId = id;
    document.getElementById('refund-amount').value = '';
    document.getElementById('refund-method').value = '';
    document.getElementById('process-refund-modal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeProcessRefundModal() {
    document.getElementById('process-refund-modal').classList.add('hidden');
    currentRMAId = null;
}

function confirmProcessRefund(event) {
    event.preventDefault();
    if (!currentRMAId) return;
    
    const amount = document.getElementById('refund-amount').value;
    const method = document.getElementById('refund-method').value;
    
    if (!amount || !method) {
        alert('Please fill in all required fields');
        return;
    }
    
    const refundUrl = '{{ admin_route("orders.returns-repairs.refund", $returnRepair->id) }}'.replace('{{ $returnRepair->id }}', currentRMAId);
    fetch(refundUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            refund_amount: amount,
            refund_method: method
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeProcessRefundModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to process refund'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the refund');
    });
}

// Mark as Completed Functions
function markCompleted(id) {
    currentRMAId = id;
    document.getElementById('mark-completed-modal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeMarkCompletedModal() {
    document.getElementById('mark-completed-modal').classList.add('hidden');
    currentRMAId = null;
}

function confirmMarkCompleted() {
    if (!currentRMAId) return;
    
    const completeUrl = '{{ admin_route("orders.returns-repairs.complete", $returnRepair->id) }}'.replace('{{ $returnRepair->id }}', currentRMAId);
    fetch(completeUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeMarkCompletedModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to mark RMA as completed'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while marking the RMA as completed');
    });
}

// Update Notes Functions
function showUpdateNotesModal() {
    document.getElementById('update-notes-modal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeUpdateNotesModal() {
    document.getElementById('update-notes-modal').classList.add('hidden');
}

function confirmUpdateNotes(event) {
    event.preventDefault();
    
    const notes = document.getElementById('admin-notes').value;
    
    fetch(`{{ admin_route('orders.returns-repairs.notes', $returnRepair->id) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ admin_notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeUpdateNotesModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update notes'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the notes');
    });
}

lucide.createIcons();
</script>
@endpush

