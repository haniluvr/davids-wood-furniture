@extends('admin.layouts.app')

@section('title', 'Admin Users')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-stone-900">Admin Users</h1>
                <p class="mt-1 text-sm text-stone-600">Manage admin accounts and permissions</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ admin_route('users.create-admin') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Admin
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="pt-6 pb-3">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            <!-- Total Admins -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Total Admins</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['total_admins'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Admins -->
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
                        <p class="text-sm font-medium text-stone-500">Active Admins</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['active_admins'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Super Admins -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Super Admins</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ number_format($stats['super_admins'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Last Login -->
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-stone-500">Last Login</p>
                        <p class="text-2xl font-semibold text-stone-900">{{ $stats['last_login'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="pt-3 pb-6">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
            <form method="GET" action="{{ admin_route('users.admins') }}" class="flex flex-wrap items-end gap-4 justify-between">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-stone-700 mb-2">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Search admins..."
                           class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="role" class="block text-sm font-medium text-stone-700 mb-2">Role</label>
                    <select id="role" name="role" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Roles</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-stone-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 whitespace-nowrap">
                        Filter
                    </button>
                    <a href="{{ admin_route('users.admins') }}" class="inline-flex items-center justify-center rounded-lg border border-stone-300 px-4 py-2.5 text-sm hover:bg-stone-50">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Admin Users List -->
    <div class="pb-8">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200">
            @if($admins->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Admin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Last Login</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stone-200">
                            @foreach($admins as $admin)
                                <tr class="hover:bg-stone-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm">
                                                        {{ substr($admin->first_name, 0, 1) }}{{ substr($admin->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-stone-900">{{ $admin->first_name }} {{ $admin->last_name }}</div>
                                                <div class="text-sm text-stone-500">{{ $admin->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $admin->role === 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                                               ($admin->role === 'admin' ? 'bg-blue-100 text-blue-800' : 
                                               ($admin->role === 'manager' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        {{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y') : 'Never' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900">
                                        {{ $admin->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ admin_route('users.edit-admin', $admin) }}" class="text-emerald-600 hover:text-emerald-900 transition-colors duration-150" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            @if($admin->id !== auth('admin')->id())
                                                <button onclick="confirmDelete({{ $admin->id }})" class="text-red-600 hover:text-red-900 transition-colors duration-150" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($admins->hasPages())
                    @include('admin.partials.pagination', ['paginator' => $admins])
                @endif
            @else
                <div class="p-8 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-stone-100 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <p class="text-stone-500">No admin users found</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function confirmDelete(adminId) {
    if (confirm('Are you sure you want to delete this admin user? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/admins/${adminId}`;
        
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
