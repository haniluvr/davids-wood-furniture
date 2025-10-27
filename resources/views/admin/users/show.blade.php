@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        User Details
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ admin_route('users.index') }}">Users /</a>
            </li>
            <li class="font-medium text-primary">{{ $user->first_name }} {{ $user->last_name }}</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- User Info Card -->
    <div class="lg:col-span-1">
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex flex-col items-center text-center">
                <!-- Avatar -->
                <div class="relative mb-4">
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-2xl">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-500 border-2 border-white dark:border-boxdark flex items-center justify-center">
                        <i data-lucide="check" class="w-3 h-3 text-white"></i>
                    </div>
                </div>

                <!-- User Details -->
                <h3 class="text-xl font-bold text-black dark:text-white mb-2">
                    {{ $user->first_name }} {{ $user->last_name }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $user->email }}</p>

                <!-- Status Badge -->
                <div class="mb-6">
                    @if($user->email_verified_at)
                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                            <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                            Unverified
                        </span>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 w-full">
                    <a href="{{ admin_route('users.edit', $user) }}" class="flex-1 flex items-center justify-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Edit
                    </a>
                    <button class="flex-1 flex items-center justify-center gap-2 rounded-lg border border-stroke bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-300 dark:hover:bg-gray-800">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        Email
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="mt-6 rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Quick Stats</h4>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Orders</span>
                    <span class="font-semibold text-black dark:text-white">{{ $user->orders->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Spent</span>
                    <span class="font-semibold text-black dark:text-white">₱{{ number_format($user->orders->where('status', '!=', 'cancelled')->sum('total_amount'), 2) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Member Since</span>
                    <span class="font-semibold text-black dark:text-white">{{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:col-span-2">
        <!-- Tabs -->
        <div class="mb-6" x-data="{ activeTab: 'overview' }">
            <div class="border-b border-stroke dark:border-strokedark">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Overview
                    </button>
                    <button @click="activeTab = 'orders'" :class="activeTab === 'orders' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Orders ({{ $user->orders->count() }})
                    </button>
                    <button @click="activeTab = 'addresses'" :class="activeTab === 'addresses' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Addresses
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-6">
                <!-- Overview Tab -->
                <div x-show="activeTab === 'overview'" x-transition>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Personal Information -->
                        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Personal Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Full Name</label>
                                    <p class="text-black dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</label>
                                    <p class="text-black dark:text-white">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Phone</label>
                                    <p class="text-black dark:text-white">{{ $user->phone ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Date of Birth</label>
                                    <p class="text-black dark:text-white">{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Account Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Member Since</label>
                                    <p class="text-black dark:text-white">{{ $user->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Last Login</label>
                                    <p class="text-black dark:text-white">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y g:i A') : 'Never' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Email Verified</label>
                                    <p class="text-black dark:text-white">{{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</label>
                                    <p class="text-black dark:text-white">{{ ucfirst($user->status ?? 'active') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Tab -->
                <div x-show="activeTab === 'orders'" x-transition>
                    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                        <div class="px-7.5 py-6">
                            <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Order History</h4>
                            @if($user->orders->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="border-b border-stroke dark:border-strokedark">
                                                <th class="text-left py-3 px-4 font-medium text-black dark:text-white">Order #</th>
                                                <th class="text-left py-3 px-4 font-medium text-black dark:text-white">Date</th>
                                                <th class="text-left py-3 px-4 font-medium text-black dark:text-white">Status</th>
                                                <th class="text-left py-3 px-4 font-medium text-black dark:text-white">Total</th>
                                                <th class="text-left py-3 px-4 font-medium text-black dark:text-white">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->orders->take(10) as $order)
                                                <tr class="border-b border-stroke/50 dark:border-strokedark/50 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                                    <td class="py-3 px-4 text-black dark:text-white">#{{ $order->id }}</td>
                                                    <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td class="py-3 px-4">
                                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                            @if($order->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                            @elseif($order->status === 'shipped') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                            @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3 px-4 text-black dark:text-white">₱{{ number_format($order->total_amount, 2) }}</td>
                                                    <td class="py-3 px-4">
                                                        <a href="{{ admin_route('orders.show', $order) }}" class="text-primary hover:text-primary/80 transition-colors duration-200">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($user->orders->count() > 10)
                                    <div class="mt-4 text-center">
                                        <a href="{{ admin_route('orders.index', ['user_id' => $user->id]) }}" class="text-primary hover:text-primary/80 transition-colors duration-200">
                                            View all {{ $user->orders->count() }} orders
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <i data-lucide="shopping-cart" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                                    <p class="text-gray-600 dark:text-gray-400">No orders found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Addresses Tab -->
                <div x-show="activeTab === 'addresses'" x-transition>
                    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-black dark:text-white">Saved Addresses</h4>
                            <button class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Add Address
                            </button>
                        </div>
                        <div class="text-center py-8">
                            <i data-lucide="map-pin" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-400">No saved addresses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
