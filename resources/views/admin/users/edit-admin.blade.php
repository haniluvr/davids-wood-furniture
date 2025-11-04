@extends('admin.layouts.app')

@section('title', 'Edit Admin')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex justify-between items-center py-6">
            <div>
                <h1 class="text-2xl font-bold text-stone-900">Edit Admin</h1>
                <p class="mt-1 text-sm text-stone-600">Update admin user information</p>
            </div>
            <a href="{{ admin_route('users.admins') }}" class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 hover:bg-stone-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Admins
            </a>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <form action="{{ admin_route('users.update-admin', $admin) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-stone-700 mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $admin->first_name) }}" required
                                   class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('first_name') border-red-300 @enderror">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-stone-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $admin->last_name) }}" required
                                   class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('last_name') border-red-300 @enderror">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-stone-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" required
                               class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-300 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-stone-700 mb-2">Role <span class="text-red-500">*</span></label>
                        <select id="role" name="role" required
                                class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('role') border-red-300 @enderror">
                            <option value="">Select Role</option>
                            <option value="super_admin" {{ old('role', $admin->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ old('role', $admin->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="sales_support_manager" {{ old('role', $admin->role) == 'sales_support_manager' ? 'selected' : '' }}>Sales & Customer Support Manager</option>
                            <option value="inventory_fulfillment_manager" {{ old('role', $admin->role) == 'inventory_fulfillment_manager' ? 'selected' : '' }}>Inventory & Fulfillment Manager</option>
                            <option value="product_content_manager" {{ old('role', $admin->role) == 'product_content_manager' ? 'selected' : '' }}>Product & Content Manager</option>
                            <option value="finance_reporting_analyst" {{ old('role', $admin->role) == 'finance_reporting_analyst' ? 'selected' : '' }}>Finance & Reporting Analyst</option>
                            <option value="staff" {{ old('role', $admin->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="viewer" {{ old('role', $admin->role) == 'viewer' ? 'selected' : '' }}>Viewer</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-stone-700 mb-2">Password (leave blank to keep current)</label>
                        <input type="password" id="password" name="password"
                               class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-300 @enderror"
                               placeholder="Enter new password">
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-stone-500">Minimum 8 characters. Leave blank if you don't want to change the password.</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-stone-700 mb-2">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-4">
                    <a href="{{ admin_route('users.admins') }}" class="px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 hover:bg-stone-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Update Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

