@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-stone-900">Notifications</h1>
                <p class="mt-1 text-sm text-stone-600">Manage and view all system notifications</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="pt-6 pb-3">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            <!-- Total Notifications -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="bell" class="w-5 h-5 text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Total Notifications</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['total'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Unread Notifications -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="mail" class="w-5 h-5 text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Unread</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['unread'] ?? 0) }}</p>
            </div>
        </div>
    </div>

            <!-- Today -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="calendar" class="w-5 h-5 text-green-600"></i>
                        </div>
            </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Today</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['today'] ?? 0) }}</p>
            </div>
        </div>
    </div>

            <!-- This Week -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="trending-up" class="w-5 h-5 text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">This Week</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['this_week'] ?? 0) }}</p>
                    </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="pt-3 pb-6">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
            <form method="GET" action="{{ admin_route('notifications.index') }}" class="flex flex-wrap items-end gap-4 justify-between">
                <div class="flex-1 min-w-[200px]">
                    <label for="type" class="block text-sm font-medium text-stone-700 mb-2">Type</label>
                    <select id="type" name="type" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Types</option>
                        <option value="order" {{ request('type') === 'order' ? 'selected' : '' }}>Orders</option>
                        <option value="order_status" {{ request('type') === 'order_status' ? 'selected' : '' }}>Order Status</option>
                        <option value="inventory" {{ request('type') === 'inventory' ? 'selected' : '' }}>Inventory</option>
                        <option value="message" {{ request('type') === 'message' ? 'selected' : '' }}>Messages</option>
                        <option value="customer" {{ request('type') === 'customer' ? 'selected' : '' }}>Customers</option>
                        <option value="review" {{ request('type') === 'review' ? 'selected' : '' }}>Reviews</option>
                        <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>Refunds</option>
                        <option value="system" {{ request('type') === 'system' ? 'selected' : '' }}>System</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-stone-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Status</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
</div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 whitespace-nowrap">
                        Filter
                    </button>
                    <a href="{{ admin_route('notifications.index') }}" class="inline-flex items-center justify-center rounded-lg border border-stone-300 px-4 py-2.5 text-sm hover:bg-stone-50">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </a>
                    <button type="button" onclick="markAllAsRead()" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 whitespace-nowrap">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        Mark All Read
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="pb-8">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200">
            @if($notifications->count() > 0)
    <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Notification</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
                        <tbody class="bg-white divide-y divide-stone-200">
                            @foreach($notifications as $notification)
                                <tr class="hover:bg-stone-50 transition-colors duration-150 {{ $notification->status !== 'read' ? 'bg-blue-50/30' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @switch($notification->type)
                                                    @case('order')
                                                        <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                                                            <i data-lucide="shopping-cart" class="h-5 w-5 text-green-600"></i>
                                                        </div>
                                                        @break
                                                    @case('order_status')
                                                        <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                                            <i data-lucide="truck" class="h-5 w-5 text-blue-600"></i>
                                                        </div>
                                                        @break
                                                    @case('inventory')
                                                        <div class="h-10 w-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                                                            <i data-lucide="alert-triangle" class="h-5 w-5 text-yellow-600"></i>
                                                        </div>
                                                        @break
                                                    @case('message')
                                                        <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                                            <i data-lucide="message-circle" class="h-5 w-5 text-purple-600"></i>
                                                        </div>
                                                        @break
                                                    @case('customer')
                                                        <div class="h-10 w-10 rounded-lg bg-pink-100 flex items-center justify-center">
                                                            <i data-lucide="user-plus" class="h-5 w-5 text-pink-600"></i>
                                                        </div>
                                                        @break
                                                    @case('review')
                                                        <div class="h-10 w-10 rounded-lg bg-orange-100 flex items-center justify-center">
                                                            <i data-lucide="star" class="h-5 w-5 text-orange-600"></i>
                                                        </div>
                                                        @break
                                                    @case('refund')
                                                        <div class="h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center">
                                                            <i data-lucide="refresh-cw" class="h-5 w-5 text-red-600"></i>
                                                        </div>
                                                        @break
                                                    @default
                                                        <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                            <i data-lucide="bell" class="h-5 w-5 text-gray-600"></i>
                                                        </div>
                                                @endswitch
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-stone-900">
                            @switch($notification->type)
                                                        @case('order')
                                                            Order
                                                            @break
                                                        @case('order_status')
                                                            Order Status
                                                            @break
                                                        @case('inventory')
                                                            Inventory
                                    @break
                                                        @case('message')
                                                            Message
                                    @break
                                                        @case('customer')
                                                            Customer
                                    @break
                                                        @case('review')
                                                            Review
                                    @break
                                                        @case('refund')
                                                            Refund
                                    @break
                                @default
                                                            System
                            @endswitch
                        </div>
                                            </div>
                        </div>
                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-stone-900">{{ $notification->title }}</div>
                                        <div class="text-sm text-stone-500 mt-1">{{ Str::limit($notification->message, 100) }}</div>
                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($notification->status === 'read')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Read
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Unread
                            </span>
                        @endif
                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        {{ $notification->created_at->format('M d, Y') }}
                                        <div class="text-xs text-stone-500">{{ $notification->created_at->format('H:i') }}</div>
                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            @if($notification->status !== 'read')
                                                <button onclick="markAsRead({{ $notification->id }})" class="text-emerald-600 hover:text-emerald-900 transition-colors duration-150" title="Mark as Read">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </button>
                            @endif
                                            @if($notification->data)
                                                @if(isset($notification->data['order_id']))
                                                    <a href="{{ admin_route('orders.show', $notification->data['order_id']) }}" class="text-emerald-600 hover:text-emerald-900 transition-colors duration-150" title="View Order">
                                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                                    </a>
                                                @elseif(isset($notification->data['message_id']))
                                                    <a href="{{ admin_route('messages.show', $notification->data['message_id']) }}" class="text-emerald-600 hover:text-emerald-900 transition-colors duration-150" title="View Message">
                                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                                    </a>
                                                @elseif(isset($notification->data['product_id']))
                                                    <a href="{{ admin_route('products.show', $notification->data['product_id']) }}" class="text-emerald-600 hover:text-emerald-900 transition-colors duration-150" title="View Product">
                                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                                    </a>
                                                @endif
                                            @endif
                        </div>
                    </td>
                </tr>
                            @endforeach
            </tbody>
        </table>
    </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                    @include('admin.partials.pagination', ['paginator' => $notifications])
                @endif
            @else
                <div class="p-8 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4">
                        <i data-lucide="bell-off" class="h-6 w-6 text-stone-400"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-stone-900">No notifications</h3>
                    <p class="mt-1 text-sm text-stone-500">You're all caught up! No notifications to display.</p>
                </div>
    @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-refresh notifications page when new notifications are added
let refreshInterval;
let lastNotificationCount = {{ $notifications->total() }};

function checkForNewNotifications() {
    fetch('{{ admin_route("notifications.api") }}?limit=1', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.unread_count > lastNotificationCount) {
            // New notifications detected, reload page
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error checking for new notifications:', error);
    });
}

// Refresh every 10 seconds
refreshInterval = setInterval(checkForNewNotifications, 10000);

// Also refresh when tab becomes visible
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        checkForNewNotifications();
    }
});

// Listen for real-time notification events
if (typeof window.Echo !== 'undefined') {
    window.Echo.private('admin.notifications')
        .listen('.order.created', (e) => {
            console.log('Order created event received on notifications page');
            setTimeout(() => {
                location.reload();
            }, 1000); // Wait 1 second for notification to be saved to DB
        })
        .listen('.system.notification', (e) => {
            console.log('System notification event received on notifications page');
            setTimeout(() => {
                location.reload();
            }, 1000);
        });
}

function markAsRead(notificationId) {
    fetch(`{{ admin_route("notifications.mark-read", ["id" => ":id"]) }}`.replace(':id', notificationId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to mark notification as read'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while marking the notification as read.');
    });
}

function markAllAsRead() {
    if (!confirm('Are you sure you want to mark all notifications as read?')) {
        return;
    }
    
    fetch('{{ admin_route("notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to mark all notifications as read'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while marking all notifications as read.');
    });
}
</script>
@endpush
@endsection
