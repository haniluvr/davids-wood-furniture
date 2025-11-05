@extends('admin.layouts.app')

@section('title', 'Create Admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                    <i data-lucide="user-plus" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Create New Admin</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Add a new admin user to your system</p>
                </div>
            </div>
            <a href="{{ admin_route('users.admins') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Admins
            </a>
        </div>
    </div>

    <form action="{{ admin_route('users.store-admin') }}" method="POST" class="space-y-8" x-data="departmentPositions">
        @csrf

        <!-- Personal Information -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                        <i data-lucide="user" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Personal Information</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Enter the essential details about the admin user</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="first_name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="{{ old('first_name') }}"
                            placeholder="Enter first name"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('first_name') border-red-300 @enderror"
                            required
                        />
                        @error('first_name')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="last_name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="{{ old('last_name') }}"
                            placeholder="Enter last name"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('last_name') border-red-300 @enderror"
                            required
                        />
                        @error('last_name')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email_username" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Login Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="email_username"
                                value="{{ old('email') ? preg_replace('/@dwatelier\.co$/', '', old('email')) : '' }}"
                                placeholder="Enter username (e.g., datelier)"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 pr-32 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('email') border-red-300 @enderror"
                                required
                                autocomplete="off"
                            />
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-sm text-stone-500 dark:text-gray-400 pointer-events-none">@dwatelier.co</span>
                        </div>
                        <input type="hidden" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-stone-500 dark:text-gray-400">This email will be used for login. @dwatelier.co will be automatically appended.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="personal_email" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Personal Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="personal_email"
                            name="personal_email"
                            value="{{ old('personal_email') }}"
                            placeholder="Enter personal email address"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('personal_email') border-red-300 @enderror"
                            required
                        />
                        @error('personal_email')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-stone-500 dark:text-gray-400">Welcome email and password setup link will be sent to this address.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="role"
                            name="role"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('role') border-red-300 @enderror"
                            required
                        >
                            <option value="">Select Role</option>
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="sales_support_manager" {{ old('role') == 'sales_support_manager' ? 'selected' : '' }}>Sales & Customer Support Manager</option>
                            <option value="inventory_fulfillment_manager" {{ old('role') == 'inventory_fulfillment_manager' ? 'selected' : '' }}>Inventory & Fulfillment Manager</option>
                            <option value="product_content_manager" {{ old('role') == 'product_content_manager' ? 'selected' : '' }}>Product & Content Manager</option>
                            <option value="finance_reporting_analyst" {{ old('role') == 'finance_reporting_analyst' ? 'selected' : '' }}>Finance & Reporting Analyst</option>
                            <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>Viewer</option>
                        </select>
                        @error('role')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="department" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Department
                        </label>
                        <select
                            id="department"
                            name="department"
                            x-model="selectedDepartment"
                            @change="updatePositions()"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('department') border-red-300 @enderror"
                        >
                            <option value="">Select Department</option>
                            <option value="Sales & Customer Support" {{ old('department') == 'Sales & Customer Support' ? 'selected' : '' }}>Sales & Customer Support</option>
                            <option value="Inventory & Fulfillment" {{ old('department') == 'Inventory & Fulfillment' ? 'selected' : '' }}>Inventory & Fulfillment</option>
                            <option value="Product & Content" {{ old('department') == 'Product & Content' ? 'selected' : '' }}>Product & Content</option>
                            <option value="Finance & Administration" {{ old('department') == 'Finance & Administration' ? 'selected' : '' }}>Finance & Administration</option>
                            <option value="Marketing" {{ old('department') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="IT & Systems" {{ old('department') == 'IT & Systems' ? 'selected' : '' }}>IT & Systems</option>
                            <option value="Management" {{ old('department') == 'Management' ? 'selected' : '' }}>Management</option>
                        </select>
                        @error('department')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="position" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Position
                        </label>
                        <select
                            id="position"
                            name="position"
                            x-model="selectedPosition"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('position') border-red-300 @enderror"
                            :disabled="!selectedDepartment"
                        >
                            <option value="">Select Position</option>
                            <template x-for="position in availablePositions" :key="position">
                                <option :value="position" x-text="position"></option>
                            </template>
                        </select>
                        @error('position')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Setup -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-green-50 to-blue-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                        <i data-lucide="mail" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Account Setup</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">A welcome email with password setup link will be sent to the personal email address</p>
            </div>
            <div class="p-8">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                    <div class="flex items-start gap-3">
                        <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5"></i>
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                Password Setup via Email
                            </p>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                The admin user will receive a welcome email at their personal email address with a secure link to set up their password. No password is required during account creation.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4 pt-6">
            <a href="{{ admin_route('users.admins') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 border border-stone-200 bg-white text-sm font-medium text-stone-700 rounded-xl transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Create Admin
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('departmentPositions', () => ({
        selectedDepartment: '{{ old('department') }}',
        selectedPosition: '{{ old('position') }}',
        
        positionMap: {
            'Sales & Customer Support': [
                'Customer Support Representative',
                'Sales Associate',
                'Returns & Repairs Coordinator',
                'Order Specialist'
            ],
            'Inventory & Fulfillment': [
                'Warehouse Associate',
                'Inventory Clerk',
                'Fulfillment Specialist',
                'Shipping Coordinator',
                'Logistics Manager'
            ],
            'Product & Content': [
                'Product Photographer',
                'Content Writer',
                'Product Catalog Manager',
                'E-commerce Specialist'
            ],
            'Finance & Administration': [
                'Bookkeeper',
                'Financial Analyst',
                'Accounts Receivable/Payable Clerk',
                'Office Administrator'
            ],
            'Marketing': [
                'Digital Marketing Specialist',
                'Social Media Manager',
                'SEO/Content Marketer'
            ],
            'IT & Systems': [
                'Web Developer',
                'IT Support Technician',
                'Systems Administrator'
            ],
            'Management': [
                'Owner',
                'General Manager',
                'Department Manager',
                'Operations Lead'
            ]
        },
        
        get availablePositions() {
            if (!this.selectedDepartment || !this.positionMap[this.selectedDepartment]) {
                return [];
            }
            return this.positionMap[this.selectedDepartment];
        },
        
        updatePositions() {
            // Reset position when department changes
            this.selectedPosition = '';
            const positionSelect = document.getElementById('position');
            if (positionSelect) {
                positionSelect.value = '';
            }
        },
        
        init() {
            // Initialize positions based on current department
            if (this.selectedDepartment) {
                this.$nextTick(() => {
                    const positionSelect = document.getElementById('position');
                    if (positionSelect && this.selectedPosition) {
                        positionSelect.value = this.selectedPosition;
                    }
                });
            }
        }
    }));
});

document.addEventListener('DOMContentLoaded', function() {
    const emailUsernameInput = document.getElementById('email_username');
    const emailHiddenInput = document.getElementById('email');
    const domain = '@dwatelier.co';
    
    // Function to update the full email
    function updateFullEmail() {
        let value = emailUsernameInput.value.trim();
        
        // Remove @dwatelier.co if user typed it
        if (value.endsWith(domain)) {
            value = value.slice(0, -domain.length);
            emailUsernameInput.value = value;
        }
        
        // Remove any other @ symbols and domains
        if (value.includes('@')) {
            value = value.split('@')[0];
            emailUsernameInput.value = value;
        }
        
        // Update hidden input with full email
        emailHiddenInput.value = value + domain;
    }
    
    // Update on input
    emailUsernameInput.addEventListener('input', updateFullEmail);
    
    // Update on blur (when user leaves the field)
    emailUsernameInput.addEventListener('blur', updateFullEmail);
    
    // Initialize on page load
    updateFullEmail();
    
    // Validate form before submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        updateFullEmail();
        
        // Ensure the full email is set
        if (!emailHiddenInput.value || !emailHiddenInput.value.includes('@')) {
            emailHiddenInput.value = emailUsernameInput.value + domain;
        }
        
        // Validate that username is not empty
        if (!emailUsernameInput.value.trim()) {
            e.preventDefault();
            alert('Please enter a username for the login email.');
            emailUsernameInput.focus();
            return false;
        }
    });
});
</script>
@endpush
@endsection

