@extends('admin.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-stone-900">Messages</h1>
                <p class="mt-1 text-sm text-stone-600">Manage customer inquiries and support messages</p>
            </div>
            <div class="flex items-center gap-3">
                <button id="bulk-respond-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" disabled>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Mark as Responded
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="py-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            <!-- New Messages -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">New Messages</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['new_messages']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Read Messages -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Read Messages</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['read_messages']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Responded Messages -->
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
                        <p class="text-sm font-medium text-stone-500">Responded</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['responded_messages']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Messages -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Total Messages</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['total_messages']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="pb-6">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
            <div class="border-b border-stone-200">
                <nav class="-mb-px flex space-x-8">
                    <button class="status-tab {{ !request('status') || request('status') === 'all' ? 'border-b-2 border-emerald-600 py-2 px-1 text-sm font-medium text-emerald-600' : 'border-b-2 border-transparent py-2 px-1 text-sm font-medium text-stone-500 hover:text-stone-700 hover:border-stone-300' }}" data-status="all">
                        All Messages
                        <span class="ml-2 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs text-emerald-800">{{ $stats['total_messages'] }}</span>
                    </button>
                    <button class="status-tab {{ request('status') === 'new' ? 'border-b-2 border-emerald-600 py-2 px-1 text-sm font-medium text-emerald-600' : 'border-b-2 border-transparent py-2 px-1 text-sm font-medium text-stone-500 hover:text-stone-700 hover:border-stone-300' }}" data-status="new">
                        New
                        <span class="ml-2 rounded-full bg-red-100 px-2.5 py-0.5 text-xs text-red-800">{{ $stats['new_messages'] }}</span>
                    </button>
                    <button class="status-tab {{ request('status') === 'read' ? 'border-b-2 border-emerald-600 py-2 px-1 text-sm font-medium text-emerald-600' : 'border-b-2 border-transparent py-2 px-1 text-sm font-medium text-stone-500 hover:text-stone-700 hover:border-stone-300' }}" data-status="read">
                        Read
                        <span class="ml-2 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs text-blue-800">{{ $stats['read_messages'] }}</span>
                    </button>
                    <button class="status-tab {{ request('status') === 'responded' ? 'border-b-2 border-emerald-600 py-2 px-1 text-sm font-medium text-emerald-600' : 'border-b-2 border-transparent py-2 px-1 text-sm font-medium text-stone-500 hover:text-stone-700 hover:border-stone-300' }}" data-status="responded">
                        Responded
                        <span class="ml-2 rounded-full bg-green-100 px-2.5 py-0.5 text-xs text-green-800">{{ $stats['responded_messages'] }}</span>
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <!-- Messages List -->
    <div class="pb-8">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200">
            @if($messages->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all-messages" class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">From</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stone-200">
                            @foreach($messages as $message)
                                <tr class="hover:bg-stone-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="message-checkbox rounded border-stone-300 text-emerald-600 focus:ring-emerald-500" value="{{ $message->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                                    <span class="text-emerald-600 font-medium text-sm">
                                                        {{ substr($message->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-stone-900">{{ $message->name }}</div>
                                                <div class="text-sm text-stone-500">{{ $message->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-stone-900">{{ Str::limit($message->message, 50) }}</div>
                                        @if($message->tags)
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach(array_slice($message->tags, 0, 2) as $tag)
                                            <span class="inline-flex items-center rounded-full bg-stone-100 px-2 py-0.5 text-xs font-medium text-stone-800">
                                                {{ $tag }}
                                            </span>
                                            @endforeach
                                            @if(count($message->tags) > 2)
                                            <span class="text-xs text-stone-500">+{{ count($message->tags) - 2 }}</span>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($message->status === 'new') bg-red-100 text-red-800
                                            @elseif($message->status === 'read') bg-blue-100 text-blue-800
                                            @elseif($message->status === 'responded') bg-green-100 text-green-800
                                            @else bg-stone-100 text-stone-800
                                            @endif">
                                            {{ ucfirst($message->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-stone-900">{{ $message->created_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-stone-500">{{ $message->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2" x-data="{ dropdownOpen: false }">
                                            <button @click="dropdownOpen = !dropdownOpen" class="text-stone-600 hover:text-stone-900 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                </svg>
                                            </button>
                                            
                                            <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" class="absolute right-0 top-full z-40 w-48 space-y-1 rounded-lg border border-stone-200 bg-white p-1.5 shadow-lg" x-cloak>
                                                <a href="{{ admin_route('messages.show', $message) }}" class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-left text-sm text-stone-700 hover:bg-stone-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View Message
                                                </a>
                                                @if($message->status !== 'responded')
                                                <button onclick="markAsResponded({{ $message->id }})" class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-left text-sm text-green-600 hover:bg-green-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Mark as Responded
                                                </button>
                                                @endif
                                                <button onclick="deleteMessage({{ $message->id }})" class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete Message
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($messages->hasPages())
                    @include('admin.partials.pagination', ['paginator' => $messages])
                @endif
            @else
                <div class="p-8 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <p class="text-stone-500">No messages found</p>
                </div>
            @endif
        </div>
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
