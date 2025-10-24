@extends('admin.layouts.app')

@section('title', 'Messages')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">
            Messages
        </h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            Manage customer inquiries and support messages.
        </p>
    </div>

    <div class="flex items-center gap-3">
        <button id="bulk-respond-btn" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200" disabled>
            <i data-lucide="message-square" class="w-4 h-4"></i>
            Mark as Responded
        </button>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards Start -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-4 mb-8">
    <!-- New Messages -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-50 to-red-100/50 p-6 shadow-lg shadow-red-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-red-500/20 dark:from-red-900/20 dark:to-red-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-500 shadow-lg">
                    <i data-lucide="mail" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['new_messages']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">New Messages</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-red-500/10"></div>
    </div>

    <!-- Read Messages -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 shadow-lg shadow-blue-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 dark:from-blue-900/20 dark:to-blue-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 shadow-lg">
                    <i data-lucide="eye" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['read_messages']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Read Messages</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10"></div>
    </div>

    <!-- Responded Messages -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 shadow-lg shadow-emerald-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/20 dark:from-emerald-900/20 dark:to-emerald-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 shadow-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['responded_messages']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Responded</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10"></div>
    </div>

    <!-- Total Messages -->
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100/50 p-6 shadow-lg shadow-purple-500/10 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 dark:from-purple-900/20 dark:to-purple-800/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 shadow-lg">
                    <i data-lucide="message-square" class="w-6 h-6 text-white"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white">
                        {{ number_format($stats['total_messages']) }}
                    </h3>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Total Messages</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-500/10"></div>
    </div>
</div>
<!-- Stats Cards End -->

<!-- Messages Management -->
<div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-bold text-stone-900 dark:text-white">Message Inbox</h3>
            <p class="text-sm text-stone-600 dark:text-gray-400">Unified inbox for all customer messages</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="mb-6">
        <div class="border-b border-stone-200 dark:border-strokedark">
            <nav class="-mb-px flex space-x-8">
                <button class="status-tab {{ !request('status') || request('status') === 'all' ? 'border-b-2 border-primary py-2 px-1 text-sm font-medium text-primary' : 'border-b-2 border-transparent py-2 px-1 text-sm font-medium text-stone-500 hover:text-stone-700 hover:border-stone-300 dark:text-gray-400 dark:hover:text-gray-300' }}" data-status="all">
                    All Messages
                    <span class="ml-2 rounded-full bg-primary px-2.5 py-0.5 text-xs text-white">{{ $stats['total_messages'] }}</span>
                </button>
                <button class="status-tab {{ request('status') === 'new' ? 'border-b-2 border-primary py-2 px-1 text-sm font-medium text-primary' : 'border-b-2 border-transparent py-2 px-1 text-sm font-medium text-stone-500 hover:text-stone-700 hover:border-stone-300 dark:text-gray-400 dark:hover:text-gray-300' }}" data-status="new">
                    New
                    <span class="ml-2 rounded-full bg-red-100 px-2.5 py-0.5 text-xs text-red-800 dark:bg-red-900/30 dark:text-red-400">{{ $stats['new_messages'] }}</span>
                </button>
                <button class="status-tab {{ request('status') === 'read' ? 'border-b-2 border-primary py-2 px-1 text-sm font-medium text-primary' : 'border-b-2 border-transparent py-2 px-1 text-sm font-medium text-stone-500 hover:text-stone-700 hover:border-stone-300 dark:text-gray-400 dark:hover:text-gray-300' }}" data-status="read">
                    Read
                    <span class="ml-2 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">{{ $stats['read_messages'] }}</span>
                </button>
                <button class="status-tab {{ request('status') === 'responded' ? 'border-b-2 border-primary py-2 px-1 text-sm font-medium text-primary' : 'border-b-2 border-transparent py-2 px-1 text-sm font-medium text-stone-500 hover:text-stone-700 hover:border-stone-300 dark:text-gray-400 dark:hover:text-gray-300' }}" data-status="responded">
                    Responded
                    <span class="ml-2 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">{{ $stats['responded_messages'] }}</span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Messages List -->
    <div id="messages-container">
        <div class="overflow-hidden rounded-xl border border-stone-200/50 dark:border-strokedark/50">
            <div class="grid grid-cols-6 rounded-t-xl bg-stone-50 dark:bg-stone-800/50">
                <div class="p-4">
                    <input type="checkbox" id="select-all-messages" class="rounded border-stone-300 text-primary focus:ring-primary">
                </div>
                <div class="p-4">
                    <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">From</h5>
                </div>
                <div class="p-4">
                    <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Subject</h5>
                </div>
                <div class="p-4">
                    <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Status</h5>
                </div>
                <div class="p-4">
                    <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Date</h5>
                </div>
                <div class="p-4">
                    <h5 class="text-sm font-semibold text-stone-700 dark:text-stone-300">Actions</h5>
                </div>
            </div>

            @forelse($messages as $message)
            <div class="grid grid-cols-6 border-b border-stone-200/50 dark:border-strokedark/50 transition-colors duration-200 hover:bg-stone-50/50 dark:hover:bg-stone-800/20">
                <div class="flex items-center p-4">
                    <input type="checkbox" class="message-checkbox rounded border-stone-300 text-primary focus:ring-primary" value="{{ $message->id }}">
                </div>
                <div class="flex items-center gap-3 p-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                            <span class="text-white font-semibold text-sm">{{ substr($message->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <p class="font-semibold text-stone-900 dark:text-white">{{ $message->name }}</p>
                        <p class="text-xs text-stone-500 dark:text-gray-400">{{ $message->email }}</p>
                    </div>
                </div>

                <div class="flex items-center p-4">
                    <div class="flex flex-col">
                        <p class="font-semibold text-stone-900 dark:text-white truncate">{{ Str::limit($message->message, 50) }}</p>
                        @if($message->tags)
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach(array_slice($message->tags, 0, 2) as $tag)
                            <span class="inline-flex items-center rounded-full bg-stone-100 px-2 py-0.5 text-xs font-medium text-stone-800 dark:bg-stone-700 dark:text-stone-200">
                                {{ $tag }}
                            </span>
                            @endforeach
                            @if(count($message->tags) > 2)
                            <span class="text-xs text-stone-500">+{{ count($message->tags) - 2 }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center p-4">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        @if($message->status === 'new') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                        @elseif($message->status === 'read') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                        @elseif($message->status === 'responded') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                        @else bg-stone-100 text-stone-800 dark:bg-stone-700 dark:text-stone-200
                        @endif">
                        {{ ucfirst($message->status) }}
                    </span>
                </div>

                <div class="flex items-center p-4">
                    <div class="flex flex-col">
                        <p class="text-sm text-stone-900 dark:text-white">{{ $message->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-stone-500 dark:text-gray-400">{{ $message->created_at->format('h:i A') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 p-4">
                    <a href="{{ admin_route('messages.show', $message) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-emerald-100 hover:text-emerald-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-400" title="View Message">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </a>
                    @if($message->status !== 'responded')
                    <button onclick="markAsResponded({{ $message->id }})" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-green-100 hover:text-green-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-green-900/20 dark:hover:text-green-400" title="Mark as Responded">
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </button>
                    @endif
                    <button onclick="deleteMessage({{ $message->id }})" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-stone-100 text-stone-600 transition-all duration-200 hover:bg-red-100 hover:text-red-600 dark:bg-stone-800 dark:text-stone-400 dark:hover:bg-red-900/20 dark:hover:text-red-400" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4 dark:bg-stone-800">
                    <i data-lucide="message-square" class="w-6 h-6 text-stone-400"></i>
                </div>
                <p class="text-stone-500 dark:text-gray-400">No messages found</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($messages->hasPages())
        <div class="mt-6">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-messages');
    const messageCheckboxes = document.querySelectorAll('.message-checkbox');
    const bulkRespondBtn = document.getElementById('bulk-respond-btn');
    const statusTabs = document.querySelectorAll('.status-tab');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        messageCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkRespondButton();
    });

    // Individual checkbox change
    messageCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkRespondButton();
            updateSelectAllState();
        });
    });

    // Status tab functionality
    statusTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const status = this.dataset.status;
            loadMessagesByStatus(status);
            updateActiveTab(this);
        });
    });

    function updateBulkRespondButton() {
        const checkedBoxes = document.querySelectorAll('.message-checkbox:checked');
        bulkRespondBtn.disabled = checkedBoxes.length === 0;
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.message-checkbox:checked');
        const totalBoxes = messageCheckboxes.length;
        selectAllCheckbox.checked = checkedBoxes.length === totalBoxes;
        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
    }

    function updateActiveTab(activeTab) {
        statusTabs.forEach(tab => {
            tab.classList.remove('border-primary', 'text-primary');
            tab.classList.add('border-transparent', 'text-stone-500', 'hover:text-stone-700', 'hover:border-stone-300');
        });
        activeTab.classList.remove('border-transparent', 'text-stone-500', 'hover:text-stone-700', 'hover:border-stone-300');
        activeTab.classList.add('border-primary', 'text-primary');
    }

    function loadMessagesByStatus(status) {
        // This would typically make an AJAX request to load messages by status
        // For now, we'll just reload the page with the status parameter
        const url = new URL(window.location);
        if (status === 'all') {
            url.searchParams.delete('status');
        } else {
            url.searchParams.set('status', status);
        }
        window.location.href = url.toString();
    }

    // Bulk respond functionality
    bulkRespondBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.message-checkbox:checked');
        const messageIds = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (confirm(`Are you sure you want to mark ${messageIds.length} messages as responded?`)) {
            bulkUpdateStatus(messageIds, 'responded');
        }
    });
});

function markAsResponded(messageId) {
    if (confirm('Are you sure you want to mark this message as responded?')) {
        fetch(`/admin/messages/${messageId}/respond`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                response_notes: ''
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

function deleteMessage(messageId) {
    if (confirm('Are you sure you want to delete this message?')) {
        fetch(`/admin/messages/${messageId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
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

function bulkUpdateStatus(messageIds, status) {
    fetch('/admin/messages/bulk-update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            message_ids: messageIds,
            status: status
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
</script>
@endpush
