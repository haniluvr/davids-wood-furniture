@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Notifications
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ route('admin.dashboard') }}">Dashboard /</a>
            </li>
            <li class="font-medium text-primary">Notifications</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
    <!-- Stats Cards -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">{{ $stats['total'] }}</h4>
                <p class="text-sm font-medium">Total Notifications</p>
            </div>
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-primary">
                <i data-lucide="bell" class="w-6 h-6 text-white"></i>
            </div>
        </div>
    </div>

    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">{{ $stats['unread'] }}</h4>
                <p class="text-sm font-medium">Unread</p>
            </div>
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-warning">
                <i data-lucide="mail" class="w-6 h-6 text-white"></i>
            </div>
        </div>
    </div>

    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">{{ $stats['today'] }}</h4>
                <p class="text-sm font-medium">Today</p>
            </div>
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-success">
                <i data-lucide="calendar" class="w-6 h-6 text-white"></i>
            </div>
        </div>
    </div>

    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">{{ $stats['this_week'] }}</h4>
                <p class="text-sm font-medium">This Week</p>
            </div>
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-info">
                <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Table -->
<div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
    <div class="flex items-center justify-between mb-6">
        <h4 class="text-lg font-semibold text-black dark:text-white">Recent Notifications</h4>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.notifications.templates') }}" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                <i data-lucide="settings" class="w-4 h-4"></i>
                Manage Templates
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-2 text-left dark:bg-meta-4">
                    <th class="min-w-[120px] py-4 px-4 font-medium text-black dark:text-white">Type</th>
                    <th class="min-w-[200px] py-4 px-4 font-medium text-black dark:text-white">Recipient</th>
                    <th class="min-w-[150px] py-4 px-4 font-medium text-black dark:text-white">Subject</th>
                    <th class="min-w-[100px] py-4 px-4 font-medium text-black dark:text-white">Status</th>
                    <th class="min-w-[120px] py-4 px-4 font-medium text-black dark:text-white">Sent At</th>
                    <th class="py-4 px-4 font-medium text-black dark:text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                <tr>
                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                        <div class="flex items-center gap-2">
                            @switch($notification->type)
                                @case('App\Notifications\OrderCreatedNotification')
                                    <i data-lucide="shopping-cart" class="w-4 h-4 text-primary"></i>
                                    <span class="text-sm">Order Created</span>
                                    @break
                                @case('App\Notifications\OrderStatusChangedNotification')
                                    <i data-lucide="truck" class="w-4 h-4 text-info"></i>
                                    <span class="text-sm">Order Update</span>
                                    @break
                                @case('App\Notifications\LowStockNotification')
                                    <i data-lucide="alert-triangle" class="w-4 h-4 text-warning"></i>
                                    <span class="text-sm">Low Stock</span>
                                    @break
                                @case('App\Notifications\NewReviewNotification')
                                    <i data-lucide="star" class="w-4 h-4 text-success"></i>
                                    <span class="text-sm">New Review</span>
                                    @break
                                @default
                                    <i data-lucide="bell" class="w-4 h-4 text-gray-500"></i>
                                    <span class="text-sm">General</span>
                            @endswitch
                        </div>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                        <div>
                            <p class="text-black dark:text-white">{{ $notification->user ? $notification->user->name : 'System' }}</p>
                            @if($notification->user)
                                <p class="text-sm text-gray-500">{{ $notification->user->email }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                        <p class="text-black dark:text-white">{{ Str::limit($notification->data['subject'] ?? 'No subject', 50) }}</p>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                        @if($notification->read_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Read
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Unread
                            </span>
                        @endif
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                        <p class="text-black dark:text-white">{{ $notification->created_at->format('M d, Y H:i') }}</p>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                        <div class="flex items-center gap-2">
                            <button class="text-primary hover:text-primary/80" title="View Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            @if(!$notification->read_at)
                                <button class="text-success hover:text-success/80" title="Mark as Read">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </button>
                            @endif
                            <button class="text-danger hover:text-danger/80" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="border-b border-[#eee] py-5 px-4 text-center dark:border-strokedark">
                        <p class="text-gray-500">No notifications found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
