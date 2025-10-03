@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        User Management
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ route('admin.dashboard') }}">Dashboard /</a>
            </li>
            <li class="font-medium text-primary">Users</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards Start -->
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 mb-6">
    <!-- Total Users -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <i data-lucide="users" class="w-6 h-6 text-primary dark:text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['total_users']) }}
                </h4>
                <span class="text-sm font-medium">Total Users</span>
            </div>
        </div>
    </div>

    <!-- Active Users -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-3 dark:bg-meta-4">
            <i data-lucide="user-check" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['active_users']) }}
                </h4>
                <span class="text-sm font-medium">Active Users</span>
            </div>
        </div>
    </div>

    <!-- Inactive Users -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-6 dark:bg-meta-4">
            <i data-lucide="user-x" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['inactive_users']) }}
                </h4>
                <span class="text-sm font-medium">Inactive Users</span>
            </div>
        </div>
    </div>

    <!-- Recent Registrations -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-5 dark:bg-meta-4">
            <i data-lucide="user-plus" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['recent_registrations']) }}
                </h4>
                <span class="text-sm font-medium">New (30 days)</span>
            </div>
        </div>
    </div>
</div>
<!-- Stats Cards End -->

<!-- Users Table -->
<div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
    <div class="px-4 py-6 md:px-6 xl:px-7.5">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-xl font-semibold text-black dark:text-white">
                All Users
            </h4>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.admins') }}" class="inline-flex items-center justify-center rounded-md border border-primary px-4 py-2 text-center font-medium text-primary hover:bg-opacity-90">
                    <i data-lucide="shield" class="w-4 h-4 mr-2"></i>
                    Manage Admins
                </a>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white hover:bg-opacity-90">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Add User
                </a>
                <button onclick="openExportModal()" class="inline-flex items-center justify-center rounded-md border border-stroke px-4 py-2 text-center font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                    Export
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4 mb-6">
            <form method="GET" class="contents">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                </div>
                
                <div>
                    <select name="status" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        <option value="all">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                
                <div>
                    <select name="registration_method" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        <option value="all">All Methods</option>
                        <option value="email" {{ request('registration_method') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="google" {{ request('registration_method') === 'google' ? 'selected' : '' }}>Google</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-3 text-center font-medium text-white hover:bg-opacity-90">
                        <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-md border border-stroke px-4 py-3 text-center font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4">
                        <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-6 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
        <div class="col-span-2 flex items-center">
            <p class="font-medium">User</p>
        </div>
        <div class="col-span-1 hidden items-center sm:flex">
            <p class="font-medium">Email</p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="font-medium">Status</p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="font-medium">Orders</p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="font-medium">Joined</p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="font-medium">Method</p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="font-medium">Actions</p>
        </div>
    </div>

    @forelse($users as $user)
    <div class="grid grid-cols-6 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
        <div class="col-span-2 flex items-center">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                    <span class="text-gray-600 dark:text-gray-300 font-medium">
                        {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-black dark:text-white font-medium">
                        {{ $user->first_name }} {{ $user->last_name }}
                    </p>
                    <p class="text-xs text-gray-500 sm:hidden">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        <div class="col-span-1 hidden items-center sm:flex">
            <p class="text-sm text-black dark:text-white">{{ $user->email }}</p>
        </div>
        <div class="col-span-1 flex items-center">
            @if($user->is_suspended)
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                Suspended
            </span>
            @elseif($user->email_verified_at)
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                Active
            </span>
            @else
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                Inactive
            </span>
            @endif
        </div>
        <div class="col-span-1 flex items-center">
            <p class="text-sm text-black dark:text-white">{{ $user->orders_count }}</p>
        </div>
        <div class="col-span-1 flex items-center">
            <p class="text-sm text-black dark:text-white">{{ $user->created_at->format('M d, Y') }}</p>
        </div>
        <div class="col-span-1 flex items-center">
            @if($user->google_id)
            <div class="flex items-center gap-1">
                <i data-lucide="chrome" class="w-4 h-4 text-blue-500"></i>
                <span class="text-xs text-gray-500">Google</span>
            </div>
            @else
            <div class="flex items-center gap-1">
                <i data-lucide="mail" class="w-4 h-4 text-gray-500"></i>
                <span class="text-xs text-gray-500">Email</span>
            </div>
            @endif
        </div>
        <div class="col-span-1 flex items-center">
            <div class="flex items-center space-x-3.5" x-data="{ dropdownOpen: false }">
                <button @click="dropdownOpen = !dropdownOpen" class="hover:text-primary">
                    <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                </button>
                
                <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" class="absolute right-0 top-full z-40 w-48 space-y-1 rounded-sm border border-stroke bg-white p-1.5 shadow-default dark:border-strokedark dark:bg-boxdark" x-cloak>
                    <a href="{{ route('admin.users.show', $user) }}" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        View Details
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Edit User
                    </a>
                    
                    @if($user->is_suspended)
                    <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4 text-green-600">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                            Unsuspend
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4 text-yellow-600" onclick="return confirm('Are you sure you want to suspend this user?')">
                            <i data-lucide="user-x" class="w-4 h-4"></i>
                            Suspend
                        </button>
                    </form>
                    @endif
                    
                    @if(!$user->email_verified_at)
                    <form action="{{ route('admin.users.verify-email', $user) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4 text-blue-600">
                            <i data-lucide="mail-check" class="w-4 h-4"></i>
                            Verify Email
                        </button>
                    </form>
                    @endif
                    
                    @if($user->orders_count === 0)
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex w-full items-center gap-2 rounded-sm px-4 py-1.5 text-left text-sm hover:bg-gray dark:hover:bg-meta-4 text-red-600" onclick="return confirm('Are you sure you want to delete this user?')">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Delete User
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="px-4 py-8 text-center">
        <i data-lucide="users" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
        <p class="text-gray-500 dark:text-gray-400">No users found.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($users->hasPages())
<div class="mt-6">
    {{ $users->links() }}
</div>
@endif

<!-- Export Modal -->
<div id="exportModal" class="fixed inset-0 z-99999 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="w-full max-w-md rounded-lg bg-white p-6 dark:bg-boxdark">
        <h3 class="mb-4 text-lg font-medium text-black dark:text-white">Export Users</h3>
        
        <form action="{{ route('admin.users.export') }}" method="GET">
            <div class="mb-4">
                <label class="mb-2.5 block text-black dark:text-white">Export Format</label>
                <select name="format" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                    <option value="csv">CSV</option>
                    <option value="excel" disabled>Excel (Coming Soon)</option>
                    <option value="pdf" disabled>PDF (Coming Soon)</option>
                </select>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 rounded bg-primary px-4 py-2 text-white hover:bg-opacity-90">
                    <i data-lucide="download" class="w-4 h-4 mr-2 inline"></i>
                    Export
                </button>
                <button type="button" onclick="closeExportModal()" class="flex-1 rounded border border-stroke px-4 py-2 text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openExportModal() {
        document.getElementById('exportModal').classList.remove('hidden');
        document.getElementById('exportModal').classList.add('flex');
    }
    
    function closeExportModal() {
        document.getElementById('exportModal').classList.add('hidden');
        document.getElementById('exportModal').classList.remove('flex');
    }
    
    lucide.createIcons();
</script>
@endpush
