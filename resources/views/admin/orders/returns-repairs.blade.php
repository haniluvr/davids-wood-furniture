@extends('admin.layouts.app')

@section('title', 'Returns & Repairs')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">
            Returns & Repairs
        </h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Manage customer returns, exchanges, and repair requests.
        </p>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.returns-repairs.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="plus" class="w-4 h-4"></i>
            New RMA
        </a>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards Start -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-5 mb-8">
    <!-- Requested -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-yellow-50 to-yellow-100/50 p-6 shadow-lg shadow-yellow-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-yellow-500/20 dark:from-yellow-900/20 dark:to-yellow-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-500 shadow-lg">
                    <i data-lucide="clock" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['requested']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Requested</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-yellow-500/10"></div>
    </div>

    <!-- Approved -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 dark:from-blue-900/20 dark:to-blue-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['approved']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Approved</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10"></div>
    </div>

    <!-- Received -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100/50 p-6 shadow-lg shadow-purple-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 dark:from-purple-900/20 dark:to-purple-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 shadow-lg">
                    <i data-lucide="package" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['received']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Received</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
    </div>

    <!-- Completed -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-lg shadow-emerald-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/20 dark:from-emerald-900/20 dark:to-emerald-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg">
                    <i data-lucide="check" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['completed']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Completed</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10"></div>
    </div>

    <!-- Total Refunded -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-50 to-red-100/50 p-6 shadow-lg shadow-red-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-red-500/20 dark:from-red-900/20 dark:to-red-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-500 shadow-lg">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        ₱{{ number_format($stats['total_refunded'], 2) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Refunded</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-red-500/10"></div>
    </div>
</div>
<!-- Stats Cards End -->

<!-- Returns & Repairs Table -->
<div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-stone-900 dark:text-white">All Returns & Repairs</h3>
            <p class="text-sm text-stone-600 dark:text-gray-400">RMA requests and their current status</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            <button class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export
            </button>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-stone-200/50 dark:border-strokedark/50">
        <div class="grid grid-cols-6 rounded-t-xl bg-stone-50 dark:bg-stone-800/50">
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">RMA #</h5>
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Order</h5>
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Customer</h5>
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Type</h5>
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Status</h5>
            </div>
            <div class="p-4">
                <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Actions</h5>
            </div>
        </div>

        @forelse($returnsRepairs as $returnRepair)
        <div class="grid grid-cols-6 border-b border-stone-200/50 dark:border-strokedark/50 transition-colors duration-200 hover:bg-stone-50/50 dark:hover:bg-stone-800/20">
            <div class="flex items-center gap-3 p-4">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg">
                        <span class="text-white font-semibold text-sm">R</span>
                    </div>
                </div>
                <div class="flex flex-col">
                    <p class="font-semibold text-stone-900 dark:text-white">{{ $returnRepair->rma_number }}</p>
                    <p class="text-xs text-stone-500 dark:text-gray-400">{{ $returnRepair->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="flex items-center p-4">
                <div class="flex flex-col">
                    <p class="font-semibold text-stone-900 dark:text-white">{{ $returnRepair->order->order_number }}</p>
                    <p class="text-xs text-stone-500 dark:text-gray-400">{{ count($returnRepair->products) }} items</p>
                </div>
            </div>

            <div class="flex items-center p-4">
                <div class="flex flex-col">
                    <p class="font-semibold text-stone-900 dark:text-white">{{ $returnRepair->order->user->first_name ?? 'Guest' }} {{ $returnRepair->order->user->last_name ?? '' }}</p>
                    <p class="text-xs text-stone-500 dark:text-gray-400">{{ $returnRepair->order->user->email ?? 'No email' }}</p>
                </div>
            </div>

            <div class="flex items-center p-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                    {{ $returnRepair->type === 'return' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}
                    {{ $returnRepair->type === 'repair' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                    {{ $returnRepair->type === 'exchange' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' : '' }}">
                    {{ ucfirst($returnRepair->type) }}
                </span>
            </div>

            <div class="flex items-center p-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                    {{ $returnRepair->status === 'requested' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                    {{ $returnRepair->status === 'approved' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                    {{ $returnRepair->status === 'received' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' : '' }}
                    {{ $returnRepair->status === 'processing' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400' : '' }}
                    {{ $returnRepair->status === 'repaired' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                    {{ $returnRepair->status === 'refunded' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                    {{ $returnRepair->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                    {{ $returnRepair->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}">
                    {{ ucfirst($returnRepair->status) }}
                </span>
            </div>

            <div class="flex items-center gap-2 p-4">
                <a href="{{ route('admin.orders.returns-repairs.show', $returnRepair) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-emerald-100 hover:text-emerald-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-400" title="View Details">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </a>
                @if($returnRepair->status === 'requested')
                <button onclick="approveReturn({{ $returnRepair->id }})" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-green-100 hover:text-green-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-green-900/20 dark:hover:text-green-400" title="Approve">
                    <i data-lucide="check" class="w-4 h-4"></i>
                </button>
                <button onclick="rejectReturn({{ $returnRepair->id }})" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-red-100 hover:text-red-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-red-900/20 dark:hover:text-red-400" title="Reject">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="p-8 text-center">
            <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4 dark:bg-stone-800">
                <i data-lucide="package" class="w-6 h-6 text-stone-400"></i>
            </div>
            <p class="text-stone-500 dark:text-gray-400">No returns or repairs found</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($returnsRepairs->hasPages())
    <div class="mt-6">
        {{ $returnsRepairs->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function approveReturn(returnId) {
    if (confirm('Are you sure you want to approve this return/repair request?')) {
        fetch(`/admin/orders/returns-repairs/${returnId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                admin_notes: ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the request.');
        });
    }
}

function rejectReturn(returnId) {
    const notes = prompt('Please provide a reason for rejection:');
    if (notes !== null) {
        fetch(`/admin/orders/returns-repairs/${returnId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                admin_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the request.');
        });
    }
}
</script>
@endpush
@endsection
