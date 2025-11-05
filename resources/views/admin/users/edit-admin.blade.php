@extends('admin.layouts.app')

@section('title', 'Edit Admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
            <div class="flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0"></i>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700 dark:text-red-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
            <div class="flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0"></i>
                <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                    <i data-lucide="user-cog" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Edit Admin User</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update admin user information and permissions</p>
                </div>
            </div>
            <a href="{{ admin_route('users.admins') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Admins
            </a>
        </div>
    </div>

    <form action="{{ admin_route('users.update-admin', $admin->id) }}" method="POST" class="space-y-8" x-data="departmentPositions">
        @csrf
        @method('PUT')

        <!-- Personal Information -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                        <i data-lucide="user" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Personal Information</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update the essential details about the admin user</p>
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
                            value="{{ old('first_name', $admin->first_name) }}"
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
                            value="{{ old('last_name', $admin->last_name) }}"
                            placeholder="Enter last name"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('last_name') border-red-300 @enderror"
                            required
                        />
                        @error('last_name')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $admin->email) }}"
                            placeholder="Enter email address"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('email') border-red-300 @enderror"
                            required
                        />
                        @error('email')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
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
                            value="{{ old('department', $admin->department) }}"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('department') border-red-300 @enderror"
                        >
                            <option value="">Select Department</option>
                            <option value="Sales & Customer Support" {{ old('department', $admin->department) == 'Sales & Customer Support' ? 'selected' : '' }}>Sales & Customer Support</option>
                            <option value="Inventory & Fulfillment" {{ old('department', $admin->department) == 'Inventory & Fulfillment' ? 'selected' : '' }}>Inventory & Fulfillment</option>
                            <option value="Product & Content" {{ old('department', $admin->department) == 'Product & Content' ? 'selected' : '' }}>Product & Content</option>
                            <option value="Finance & Administration" {{ old('department', $admin->department) == 'Finance & Administration' ? 'selected' : '' }}>Finance & Administration</option>
                            <option value="Marketing" {{ old('department', $admin->department) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="IT & Systems" {{ old('department', $admin->department) == 'IT & Systems' ? 'selected' : '' }}>IT & Systems</option>
                            <option value="Management" {{ old('department', $admin->department) == 'Management' ? 'selected' : '' }}>Management</option>
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

                <!-- Actions -->
                <div class="flex justify-end space-x-4 pt-6">
                    <a href="{{ admin_route('users.admins') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 border border-stone-200 bg-white text-sm font-medium text-stone-700 rounded-xl transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Update Admin
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Security & Authentication -->
    <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden mt-8">
        <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-green-50 to-blue-50 dark:from-gray-800 dark:to-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                    <i data-lucide="lock" class="w-5 h-5 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Security & Authentication</h3>
            </div>
            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Send password reset link to the admin's personal email</p>
        </div>
        <div class="p-8">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 flex-1">
                        <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0"></i>
                        <div class="space-y-2 flex-1">
                            <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                Password Reset
                            </p>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                Send a password reset link to <strong>{{ $admin->personal_email ?? $admin->email }}</strong>. The link will be valid for 1 hour and will allow the admin to reset their password securely.
                            </p>
                            @if(!$admin->personal_email)
                                <p class="text-sm text-amber-700 dark:text-amber-300 mt-2">
                                    <i data-lucide="alert-triangle" class="w-4 h-4 inline mr-1"></i>
                                    No personal email configured. The reset link will be sent to their login email instead.
                                </p>
                            @endif
                            <!-- Success/Error Message -->
                            <div id="reset-link-message" class="hidden mt-2">
                                <p id="reset-link-message-text" class="text-sm font-medium"></p>
                            </div>
                        </div>
                    </div>
                    <form id="send-reset-link-form" action="{{ admin_route('users.send-reset-link', $admin->id) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit" 
                                id="send-reset-link-btn"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-600 to-blue-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-green-700 hover:to-blue-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            <span id="reset-link-btn-text">Send Reset Link</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('departmentPositions', () => ({
        selectedDepartment: {!! json_encode(old('department', $admin->department ?? '')) !!},
        selectedPosition: {!! json_encode(old('position', $admin->position ?? '')) !!},
        
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
            // Reset position when department changes (only if it's a new selection)
            const initialDepartment = {!! json_encode(old('department', $admin->department ?? '')) !!};
            if (this.selectedDepartment !== initialDepartment) {
                this.selectedPosition = '';
            }
        },
        
        init() {
            // Ensure position is set when positions become available
            const initialPosition = {!! json_encode(old('position', $admin->position ?? '')) !!};
            const initialDepartment = {!! json_encode(old('department', $admin->department ?? '')) !!};
            
            if (initialPosition && initialDepartment) {
                // Watch for when positions become available
                this.$watch('availablePositions', (positions) => {
                    if (positions && positions.length > 0 && initialPosition) {
                        // Check if position exists in the list
                        if (positions.includes(initialPosition)) {
                            this.$nextTick(() => {
                                this.selectedPosition = initialPosition;
                                // Also directly set the select element value
                                const positionSelect = document.getElementById('position');
                                if (positionSelect) {
                                    positionSelect.value = initialPosition;
                                }
                            });
                        }
                    }
                }, { immediate: true });
                
                // Also set it after a short delay to ensure everything is ready
                this.$nextTick(() => {
                    setTimeout(() => {
                        if (this.availablePositions && this.availablePositions.length > 0) {
                            if (this.availablePositions.includes(initialPosition)) {
                                this.selectedPosition = initialPosition;
                                const positionSelect = document.getElementById('position');
                                if (positionSelect) {
                                    positionSelect.value = initialPosition;
                                }
                            }
                        }
                    }, 200);
                });
            }
        }
    }));
});

// Handle Send Reset Link form submission via AJAX
document.addEventListener('DOMContentLoaded', function() {
    const resetForm = document.getElementById('send-reset-link-form');
    const resetBtn = document.getElementById('send-reset-link-btn');
    const resetBtnText = document.getElementById('reset-link-btn-text');
    const resetMessage = document.getElementById('reset-link-message');
    const resetMessageText = document.getElementById('reset-link-message-text');
    
    if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable button and show loading state
            resetBtn.disabled = true;
            resetBtnText.textContent = 'Sending...';
            
            // Hide previous messages
            resetMessage.classList.add('hidden');
            
            // Get form data
            const formData = new FormData(this);
            const url = this.action;
            const token = formData.get('_token');
            
            // Send AJAX request
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                // Re-enable button
                resetBtn.disabled = false;
                resetBtnText.textContent = 'Send Reset Link';
                
                // Show success message
                if (data.success || data.message) {
                    resetMessage.classList.remove('hidden');
                    resetMessage.classList.add('text-green-700', 'dark:text-green-300');
                    resetMessage.classList.remove('text-red-700', 'dark:text-red-300');
                    resetMessageText.textContent = data.message || data.success || 'Password reset link sent successfully!';
                } else if (data.errors) {
                    // Show error message
                    resetMessage.classList.remove('hidden');
                    resetMessage.classList.add('text-red-700', 'dark:text-red-300');
                    resetMessage.classList.remove('text-green-700', 'dark:text-green-300');
                    const errorMsg = Object.values(data.errors).flat().join(', ') || 'Failed to send reset link. Please try again.';
                    resetMessageText.textContent = errorMsg;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Re-enable button
                resetBtn.disabled = false;
                resetBtnText.textContent = 'Send Reset Link';
                
                // Show error message
                resetMessage.classList.remove('hidden');
                resetMessage.classList.add('text-red-700', 'dark:text-red-300');
                resetMessage.classList.remove('text-green-700', 'dark:text-green-300');
                
                // Show specific error message if available
                if (error && error.message) {
                    resetMessageText.textContent = error.message;
                } else if (error && error.errors) {
                    const errorMsg = Object.values(error.errors).flat().join(', ');
                    resetMessageText.textContent = errorMsg || 'Failed to send reset link. Please try again.';
                } else {
                    resetMessageText.textContent = 'Failed to send reset link. Please try again.';
                }
            });
        });
    }
});
</script>
@endpush
@endsection

