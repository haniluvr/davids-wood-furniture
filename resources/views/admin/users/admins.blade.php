@extends('admin.layouts.app')

@section('title', 'Admin Users')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Admin Users
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ admin_route('users.index') }}">Users /</a>
            </li>
            <li class="font-medium text-primary">Admins</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards Start -->
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 mb-6">
    <!-- Total Admins -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <i data-lucide="shield" class="w-6 h-6 text-primary dark:text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['total_admins'] ?? 0) }}
                </h4>
                <span class="text-sm font-medium">Total Admins</span>
            </div>
        </div>
    </div>

    <!-- Active Admins -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-3 dark:bg-meta-4">
            <i data-lucide="user-check" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['active_admins'] ?? 0) }}
                </h4>
                <span class="text-sm font-medium">Active Admins</span>
            </div>
        </div>
    </div>

    <!-- Super Admins -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-4 dark:bg-meta-4">
            <i data-lucide="crown" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($stats['super_admins'] ?? 0) }}
                </h4>
                <span class="text-sm font-medium">Super Admins</span>
            </div>
        </div>
    </div>

    <!-- Last Login -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-5 dark:bg-meta-4">
            <i data-lucide="clock" class="w-6 h-6 text-white"></i>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ $stats['last_login'] ?? 'N/A' }}
                </h4>
                <span class="text-sm font-medium">Last Login</span>
            </div>
        </div>
    </div>
</div>
<!-- Stats Cards End -->

<!-- Admin Users Table Start -->
<div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <h4 class="text-xl font-semibold text-black dark:text-white">
            Admin Users
        </h4>
        <a href="{{ admin_route('users.create-admin') }}" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Add Admin
        </a>
    </div>

    <!-- Filters -->
    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-4">
        <div>
            <label for="search" class="mb-2.5 block text-black dark:text-white">Search</label>
            <input
                type="text"
                id="search"
                placeholder="Search admins..."
                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
            />
        </div>
        <div>
            <label for="role" class="mb-2.5 block text-black dark:text-white">Role</label>
            <select
                id="role"
                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
            >
                <option value="">All Roles</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="manager">Manager</option>
                <option value="staff">Staff</option>
                <option value="viewer">Viewer</option>
            </select>
        </div>
        <div>
            <label for="status" class="mb-2.5 block text-black dark:text-white">Status</label>
            <select
                id="status"
                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
            >
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="w-full flex items-center justify-center gap-2 rounded-lg border border-primary bg-primary px-4 py-3 text-white hover:bg-primary/90 transition-colors duration-200">
                <i data-lucide="search" class="w-4 h-4"></i>
                Filter
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="mt-6 overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-stroke dark:border-strokedark">
                    <th class="text-left py-3 px-4 font-medium text-black dark:text-white">
                        <input type="checkbox" class="rounded border-stroke dark:border-strokedark" />
                    </th>
                    <th class="text-left py-3 px-4 font-medium text-black dark:text-white">Admin</th>
                    <th class="text-left py-3 px-4 font-medium text-black dark:text-white">Role</th>
                    <th class="text-left py-3 px-4 font-medium text-black dark:text-white">Status</th>
                    <th class="text-left py-3 px-4 font-medium text-black dark:text-white">Last Login</th>
                    <th class="text-left py-3 px-4 font-medium text-black dark:text-white">Created</th>
                    <th class="text-left py-3 px-4 font-medium text-black dark:text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr class="border-b border-stroke/50 dark:border-strokedark/50 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="py-3 px-4">
                            <input type="checkbox" class="rounded border-stroke dark:border-strokedark" />
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">
                                        {{ substr($admin->first_name, 0, 1) }}{{ substr($admin->last_name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h5 class="font-medium text-black dark:text-white">{{ $admin->first_name }} {{ $admin->last_name }}</h5>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $admin->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($admin->role === 'super_admin') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                @elseif($admin->role === 'admin') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                @elseif($admin->role === 'manager') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @elseif($admin->role === 'staff') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($admin->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @elseif($admin->status === 'inactive') bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                @elseif($admin->status === 'suspended') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                {{ ucfirst($admin->status ?? 'active') }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">
                            {{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y g:i A') : 'Never' }}
                        </td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">
                            {{ $admin->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ admin_route('users.edit-admin', $admin) }}" class="text-primary hover:text-primary/80 transition-colors duration-200" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                @if($admin->id !== auth('admin')->id())
                                    <button class="text-red-500 hover:text-red-700 transition-colors duration-200" title="Delete" onclick="confirmDelete({{ $admin->id }})">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center">
                            <div class="flex flex-col items-center">
                                <i data-lucide="users" class="w-12 h-12 text-gray-400 mb-4"></i>
                                <p class="text-gray-600 dark:text-gray-400">No admin users found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($admins->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Showing {{ $admins->firstItem() }} to {{ $admins->lastItem() }} of {{ $admins->total() }} results
            </div>
            <div class="flex items-center gap-2">
                {{ $admins->links() }}
            </div>
        </div>
    @endif
</div>
<!-- Admin Users Table End -->

<!-- Bulk Actions -->
<div class="mt-6 rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark" x-data="{ showBulkActions: false }">
    <div class="flex items-center justify-between">
        <h4 class="text-lg font-semibold text-black dark:text-white">Bulk Actions</h4>
        <button @click="showBulkActions = !showBulkActions" class="flex items-center gap-2 rounded-lg border border-stroke bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-300 dark:hover:bg-gray-800">
            <i data-lucide="settings" class="w-4 h-4"></i>
            Bulk Actions
        </button>
    </div>
    
    <div x-show="showBulkActions" x-transition class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
        <button class="flex items-center justify-center gap-2 rounded-lg border border-green-500 bg-green-500 px-4 py-2 text-white hover:bg-green-600 transition-colors duration-200">
            <i data-lucide="user-check" class="w-4 h-4"></i>
            Activate Selected
        </button>
        <button class="flex items-center justify-center gap-2 rounded-lg border border-yellow-500 bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600 transition-colors duration-200">
            <i data-lucide="user-x" class="w-4 h-4"></i>
            Deactivate Selected
        </button>
        <button class="flex items-center justify-center gap-2 rounded-lg border border-red-500 bg-red-500 px-4 py-2 text-white hover:bg-red-600 transition-colors duration-200">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
            Delete Selected
        </button>
    </div>
</div>

<script>
function confirmDelete(adminId) {
    if (confirm('Are you sure you want to delete this admin user? This action cannot be undone.')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/admins/${adminId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
