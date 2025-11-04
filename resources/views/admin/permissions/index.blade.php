@extends('admin.layouts.app')

@section('title', 'Role & Permission Management')

@section('content')
<div class="min-h-screen bg-white" x-data='permissionsPage()' @keydown.window.ctrl.s.prevent="save()" @keydown.window.meta.s.prevent="save()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 py-6 px-4 sm:px-6">
            <div>
                <h1 class="text-2xl font-bold text-stone-900">Role & Permission Management</h1>
                <p class="mt-1 text-sm text-stone-600">Manage admin roles and their permissions</p>
</div>
            <div class="flex items-center gap-3 flex-wrap">
                <button @click="showResetModal = true" class="inline-flex items-center justify-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 w-full sm:w-auto">
                    <i data-lucide="rotate-ccw" class="w-4 h-4 mr-2"></i>
                    Reset to Defaults
                </button>
                <button @click="showAddRoleModal = true" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Role
                </button>
            </div>
        </div>
            </div>

    <!-- Unsaved Changes Indicator -->
    <div x-show="hasUnsavedChanges" x-transition class="bg-yellow-50 border-b border-yellow-200 px-4 sm:px-6 py-3">
                    <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-yellow-600"></i>
                <span class="text-sm font-medium text-yellow-800">You have unsaved changes</span>
                <span class="text-xs text-yellow-600">(Press Ctrl+S or Cmd+S to save)</span>
                        </div>
            <button @click="save()" class="text-sm font-medium text-yellow-800 hover:text-yellow-900 underline">
                Save Now
                            </button>
                    </div>
                </div>

    <div class="py-6 px-4 sm:px-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Roles List -->
            <div class="lg:col-span-1 order-2 lg:order-1">
                <div class="bg-white rounded-xl shadow-sm border border-stone-200">
                    <div class="px-4 sm:px-6 py-4 border-b border-stone-200 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <h4 class="text-lg font-semibold text-stone-900 dark:text-white">Admin Roles</h4>
                            <button @click="showAddRoleModal = true" class="flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-3 py-1.5 text-sm text-white hover:bg-emerald-700 transition-colors duration-200 w-full sm:w-auto">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Add Role
                            </button>
                        </div>
                    </div>
                    <div class="p-4 space-y-3 overflow-y-auto max-h-[calc(100vh-20rem)] lg:max-h-none">
                        @foreach($roles as $role)
                            @php
                                $roleKey = $role;
                                $roleName = ucwords(str_replace('_', ' ', $role));
                                $roleColors = [
                                    'super_admin' => ['from-red-50 to-red-100', 'text-red-800', 'text-red-600', 'bg-red-100', 'text-red-800', 'ring-red-400'],
                                    'admin' => ['from-blue-50 to-blue-100', 'text-blue-800', 'text-blue-600', 'bg-blue-100', 'text-blue-800', 'ring-blue-400'],
                                    'sales_support_manager' => ['from-green-50 to-green-100', 'text-green-800', 'text-green-600', 'bg-green-100', 'text-green-800', 'ring-green-400'],
                                    'inventory_fulfillment_manager' => ['from-cyan-50 to-cyan-100', 'text-cyan-800', 'text-cyan-600', 'bg-cyan-100', 'text-cyan-800', 'ring-cyan-400'],
                                    'product_content_manager' => ['from-purple-50 to-purple-100', 'text-purple-800', 'text-purple-600', 'bg-purple-100', 'text-purple-800', 'ring-purple-400'],
                                    'finance_reporting_analyst' => ['from-orange-50 to-orange-100', 'text-orange-800', 'text-orange-600', 'bg-orange-100', 'text-orange-800', 'ring-orange-400'],
                                    'staff' => ['from-yellow-50 to-yellow-100', 'text-yellow-800', 'text-yellow-600', 'bg-yellow-100', 'text-yellow-800', 'ring-yellow-400'],
                                    'viewer' => ['from-gray-50 to-gray-100', 'text-gray-800', 'text-gray-600', 'bg-gray-100', 'text-gray-800', 'ring-gray-400'],
                                ];
                                $colors = $roleColors[$role] ?? $roleColors['viewer'];
                                $userCount = $roleUserCounts[$role] ?? 0;
                                $grantedCount = count(array_filter($rolePermissions[$role] ?? []));
                                $totalCount = count($permissions);
                            @endphp
                            <div class="p-4 border border-stone-200 dark:border-strokedark rounded-xl bg-gradient-to-r {{ $colors[0] }} dark:from-gray-900/20 dark:to-gray-800/20 cursor-pointer transition-all duration-200 hover:shadow-md" 
                                 :class="selectedRole==='{{ $role }}' ? 'ring-2 {{ $colors[5] }} shadow-md' : ''" 
                                 @click.prevent="selectedRole='{{ $role }}'">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <h5 class="font-semibold {{ $colors[1] }} dark:{{ $colors[1] }} truncate">{{ $roleName }}</h5>
                                        <p class="text-sm {{ $colors[2] }} dark:{{ $colors[2] }} mt-1 line-clamp-2">
                                            @if($role === 'super_admin') Full system access
                                            @elseif($role === 'admin') Full admin access
                                            @elseif($role === 'sales_support_manager') Orders, customers, messages, returns
                                            @elseif($role === 'inventory_fulfillment_manager') Inventory, stock, fulfillment
                                            @elseif($role === 'product_content_manager') Catalog, CMS, reviews moderation
                                            @elseif($role === 'finance_reporting_analyst') Analytics, revenue, read-only orders/customers
                                            @elseif($role === 'staff') Limited access
                                            @else Read-only access
                                            @endif
                                        </p>
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-xs {{ $colors[2] }}">{{ $grantedCount }}/{{ $totalCount }} permissions</span>
                </div>
                        </div>
                                    <div class="flex items-center gap-2 sm:ml-4 flex-shrink-0">
                                        <span class="inline-flex items-center rounded-full {{ $colors[3] }} px-2.5 py-0.5 text-xs font-medium {{ $colors[4] }} dark:bg-gray-900/30 dark:text-gray-400">
                                            {{ $userCount }} {{ $userCount === 1 ? 'user' : 'users' }}
                            </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>

            <!-- Permissions Management -->
            <div class="lg:col-span-2 order-1 lg:order-2">
                <div class="bg-white rounded-xl shadow-sm border border-stone-200">
                    <div class="px-4 sm:px-6 py-4 border-b border-stone-200 transition-all duration-300" 
                         :class="getRoleHeaderClass()">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg transition-all duration-300" 
                                         :class="getRoleIndicatorClass()">
                                        <i data-lucide="shield" class="w-4 h-4 text-white"></i>
                                    </div>
                        <div>
                                        <h4 class="text-lg font-semibold text-stone-900 dark:text-white">Permission Matrix</h4>
                                        <p class="text-sm font-medium mt-1 transition-colors duration-300" 
                                           :class="getRoleTextClass()" 
                                           x-text="'Editing: ' + selectedRole.replaceAll('_', ' ')"></p>
                                    </div>
                                </div>
                        </div>
                        <div class="flex items-center gap-2">
                                <input type="text" 
                                       x-model="searchQuery" 
                                       @input="filterPermissions()"
                                       placeholder="Search permissions..." 
                                       class="px-3 py-2 border border-stone-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-full sm:w-64">
                                <button @click="save()" 
                                        :disabled="saving"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-transparent bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed w-full sm:w-auto">
                                    <i data-lucide="save" class="w-4 h-4" x-show="!saving"></i>
                                    <svg x-show="saving" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                    <div class="p-4 sm:p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <button @click="expandAll()" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                                Expand All
                            </button>
                            <button @click="collapseAll()" class="text-sm text-stone-600 hover:text-stone-700 font-medium">
                                Collapse All
                            </button>
                        </div>
                        <!-- Permission Categories -->
                        <div class="space-y-6" x-show="filteredCategories.length > 0">
                            <template x-for="category in filteredCategories" :key="category.name">
                                <div class="border border-stone-200 dark:border-strokedark rounded-xl overflow-hidden bg-gradient-to-br from-stone-50 to-stone-100 dark:from-gray-800 dark:to-gray-700">
                                    <!-- Category Header -->
                                    <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 cursor-pointer" 
                                         @click="toggleCategory(category.name)">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3 flex-1">
                                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex-shrink-0">
                                                    <i :data-lucide="category.icon" class="w-5 h-5 text-white"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h5 class="text-lg font-semibold text-stone-900 dark:text-white" x-text="category.displayName"></h5>
                                                    <div class="flex items-center gap-3 mt-1">
                                                        <span class="text-xs text-stone-600 dark:text-gray-400" x-text="category.grantedCount + '/' + category.permissions.length + ' granted'"></span>
                                                        <div class="flex-1 h-2 bg-stone-200 dark:bg-gray-700 rounded-full overflow-hidden max-w-xs">
                                                            <div class="h-full bg-emerald-500 transition-all duration-300" 
                                                                 :style="'width: ' + (category.grantedCount / category.permissions.length * 100) + '%'"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 ml-4">
                                                <button @click.stop="toggleCategoryAll(category.name, true)" 
                                                        class="text-xs text-emerald-600 hover:text-emerald-700 font-medium">
                                                    Select All
                                                </button>
                                                <button @click.stop="toggleCategoryAll(category.name, false)" 
                                                        class="text-xs text-stone-600 hover:text-stone-700 font-medium">
                                                    Clear
                                                </button>
                                                <i :data-lucide="category.expanded ? 'chevron-down' : 'chevron-right'" 
                                                   class="w-5 h-5 text-stone-600 dark:text-gray-400 transition-transform"></i>
                    </div>
                </div>
                        </div>
                                    <!-- Category Permissions -->
                                    <div x-show="category.expanded" 
                                         x-transition
                                         class="p-4 sm:p-6 bg-white dark:bg-boxdark">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <template x-for="permission in category.permissions" :key="permission.key">
                                                <div class="flex items-center justify-between p-3 bg-white dark:bg-boxdark rounded-lg border border-stone-200 dark:border-strokedark hover:border-emerald-300 dark:hover:border-emerald-600 transition-colors"
                                                     :class="{'ring-2 ring-emerald-200': hasChanged(permission.key)}">
                                                    <div class="flex-1 min-w-0 mr-3">
                        <div class="flex items-center gap-2">
                                                            <span class="text-sm font-medium text-stone-700 dark:text-gray-300" x-text="permission.label"></span>
                                                            <div class="group relative">
                                                                <i data-lucide="info" class="w-4 h-4 text-stone-400 hover:text-stone-600 cursor-help"></i>
                                                                <div class="absolute left-0 bottom-full mb-2 hidden group-hover:block z-50 w-64 p-2 bg-stone-900 text-white text-xs rounded-lg shadow-lg">
                                                                    <span x-text="permission.description"></span>
                                                                </div>
                        </div>
                    </div>
                </div>
                                                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                                        <input type="checkbox" 
                                                               class="sr-only" 
                                                               :checked="matrix[selectedRole][permission.key] || false"
                                                               @change="togglePermission(permission.key, $event.target.checked)">
                                                        <div class="relative w-11 h-6 rounded-full transition-all duration-200"
                                                             :style="getToggleStyle(permission.key)">
                                                            <div class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-all duration-200"
                                                                 :style="getToggleCircleStyle(permission.key)"></div>
                                                        </div>
                                                    </label>
                        </div>
                                            </template>
                        </div>
                    </div>
                </div>
                            </template>
                        </div>
                        <div x-show="filteredCategories.length === 0" class="text-center py-12">
                            <i data-lucide="search" class="w-12 h-12 text-stone-400 mx-auto mb-4"></i>
                            <p class="text-stone-600 dark:text-gray-400">No permissions found matching your search.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div x-show="showAddRoleModal" 
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-[9999] flex items-center justify-center p-4"
         @click.self="showAddRoleModal = false">
        <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-boxdark text-left align-middle shadow-2xl transition-all">
            <div class="px-6 py-4 border-b border-stone-200 dark:border-strokedark">
                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Add New Role</h3>
            </div>
            <form @submit.prevent="createRole()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-stone-700 dark:text-gray-300 mb-2">Role Key <span class="text-red-500">*</span></label>
                    <input type="text" 
                           x-model="newRole.role_key" 
                           required
                           pattern="[a-z_]+"
                           placeholder="e.g., custom_manager"
                           class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <p class="mt-1 text-xs text-stone-500">Lowercase letters and underscores only</p>
                        </div>
                <div>
                    <label class="block text-sm font-medium text-stone-700 dark:text-gray-300 mb-2">Role Name <span class="text-red-500">*</span></label>
                    <input type="text" 
                           x-model="newRole.role_name" 
                           required
                           placeholder="e.g., Custom Manager"
                           class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-stone-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea x-model="newRole.description" 
                              rows="3"
                              placeholder="Brief description of this role..."
                              class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" 
                            @click="showAddRoleModal = false"
                            class="flex-1 px-4 py-2 border border-stone-200 dark:border-strokedark bg-white dark:bg-boxdark text-stone-700 dark:text-white text-sm font-medium rounded-xl hover:bg-stone-50 dark:hover:bg-gray-800 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            :disabled="creatingRole"
                            class="flex-1 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 disabled:opacity-50 transition-colors">
                        <span x-text="creatingRole ? 'Creating...' : 'Create Role'"></span>
                    </button>
                </div>
            </form>
                        </div>
                        </div>

    <!-- Reset Confirmation Modal -->
    <div x-show="showResetModal" 
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-[9999] flex items-center justify-center p-4"
         @click.self="showResetModal = false">
        <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-boxdark text-left align-middle shadow-2xl transition-all">
            <div class="px-6 py-4 border-b border-stone-200 dark:border-strokedark">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Reset to Defaults</h3>
                </div>
                        </div>
            <div class="p-6">
                <p class="text-stone-600 dark:text-gray-400 mb-4">
                    Are you sure you want to reset all permissions to their default values? This action cannot be undone and will overwrite all current permission settings.
                </p>
                <div class="flex gap-3">
                    <button @click="showResetModal = false"
                            class="flex-1 px-4 py-2 border border-stone-200 dark:border-strokedark bg-white dark:bg-boxdark text-stone-700 dark:text-white text-sm font-medium rounded-xl hover:bg-stone-50 dark:hover:bg-gray-800 transition-colors">
                        Cancel
                    </button>
                    <button @click="resetToDefaults()"
                            :disabled="resetting"
                            class="flex-1 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 disabled:opacity-50 transition-colors">
                        <span x-text="resetting ? 'Resetting...' : 'Reset to Defaults'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function permissionsPage(){
    const permissions = @js($permissions);
    const rolePermissions = @js($rolePermissions);
    const permissionDescriptions = @js($permissionDescriptions);
    const permissionCategories = @js($permissionCategories);
    
    // Category icons mapping
    const categoryIcons = {
        'products': 'package',
        'orders': 'shopping-cart',
        'inventory': 'warehouse',
        'users': 'users',
        'admins': 'shield',
        'shipping': 'truck',
        'payment_gateways': 'credit-card',
        'cms': 'file-text',
        'analytics': 'bar-chart-3',
        'reviews': 'star',
        'settings': 'settings',
        'audit_logs': 'file-search',
        'notifications': 'bell',
        'dashboard': 'layout-dashboard',
    };
    
    // Category display names
    const categoryNames = {
        'products': 'Products',
        'orders': 'Orders',
        'inventory': 'Inventory',
        'users': 'Users',
        'admins': 'Admins',
        'shipping': 'Shipping',
        'payment_gateways': 'Payment Gateways',
        'cms': 'CMS Pages',
        'analytics': 'Analytics',
        'reviews': 'Reviews',
        'settings': 'Settings',
        'audit_logs': 'Audit Logs',
        'notifications': 'Notifications',
        'dashboard': 'Dashboard',
    };
    
    // Category importance order (most important first)
    const categoryOrder = [
        'dashboard',
        'orders',
        'products',
        'inventory',
        'users',
        'admins',
        'shipping',
        'payment_gateways',
        'cms',
        'analytics',
        'reviews',
        'settings',
        'audit_logs',
        'notifications',
    ];
    
    // Build categories with permissions, sorted by importance
    const categories = categoryOrder.filter(key => permissionCategories[key]).map(categoryKey => {
        const categoryPerms = permissionCategories[categoryKey].map(permKey => {
            const parts = permKey.split('.');
            const action = parts[1] || '';
            const label = action.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            return {
                key: permKey,
                label: label,
                description: permissionDescriptions[permKey] || 'No description available'
            };
        });
        
        return {
            name: categoryKey,
            displayName: categoryNames[categoryKey] || categoryKey,
            icon: categoryIcons[categoryKey] || 'settings',
            permissions: categoryPerms,
            expanded: true,
            grantedCount: 0
        };
    });
    
    // Initialize matrix with default values
    const matrix = {};
    const defaultPermissions = @js($defaultPermissions ?? []);
    
    Object.keys(rolePermissions).forEach(role => {
        matrix[role] = {};
        permissions.forEach(perm => {
            // Use DB value if exists, otherwise use default, otherwise false
            if (rolePermissions[role] && rolePermissions[role][perm] !== undefined) {
                matrix[role][perm] = rolePermissions[role][perm];
            } else if (defaultPermissions[role] && defaultPermissions[role][perm] !== undefined) {
                matrix[role][perm] = defaultPermissions[role][perm];
            } else {
                matrix[role][perm] = false;
            }
        });
    });
    
    // Original matrix for change tracking
    const originalMatrix = JSON.parse(JSON.stringify(matrix));
    
        // Role color mappings
        const roleColors = {
            'super_admin': {
                border: 'border-red-400',
                header: 'bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20',
                indicator: 'bg-gradient-to-br from-red-500 to-red-600',
                text: 'text-red-700 dark:text-red-400'
            },
            'admin': {
                border: 'border-blue-400',
                header: 'bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20',
                indicator: 'bg-gradient-to-br from-blue-500 to-blue-600',
                text: 'text-blue-700 dark:text-blue-400'
            },
            'sales_support_manager': {
                border: 'border-green-400',
                header: 'bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20',
                indicator: 'bg-gradient-to-br from-green-500 to-green-600',
                text: 'text-green-700 dark:text-green-400'
            },
            'inventory_fulfillment_manager': {
                border: 'border-cyan-400',
                header: 'bg-gradient-to-r from-cyan-50 to-cyan-100 dark:from-cyan-900/20 dark:to-cyan-800/20',
                indicator: 'bg-gradient-to-br from-cyan-500 to-cyan-600',
                text: 'text-cyan-700 dark:text-cyan-400'
            },
            'product_content_manager': {
                border: 'border-purple-400',
                header: 'bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20',
                indicator: 'bg-gradient-to-br from-purple-500 to-purple-600',
                text: 'text-purple-700 dark:text-purple-400'
            },
            'finance_reporting_analyst': {
                border: 'border-orange-400',
                header: 'bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20',
                indicator: 'bg-gradient-to-br from-orange-500 to-orange-600',
                text: 'text-orange-700 dark:text-orange-400'
            },
            'staff': {
                border: 'border-yellow-400',
                header: 'bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20',
                indicator: 'bg-gradient-to-br from-yellow-500 to-yellow-600',
                text: 'text-yellow-700 dark:text-yellow-400'
            },
            'viewer': {
                border: 'border-gray-400',
                header: 'bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900/20 dark:to-gray-800/20',
                indicator: 'bg-gradient-to-br from-gray-500 to-gray-600',
                text: 'text-gray-700 dark:text-gray-400'
            }
        };
        
  return {
    selectedRole: 'admin',
            matrix: matrix,
            originalMatrix: originalMatrix,
            categories: categories,
            filteredCategories: categories,
            searchQuery: '',
            hasUnsavedChanges: false,
            saving: false,
            resetting: false,
            showAddRoleModal: false,
            showResetModal: false,
            creatingRole: false,
            newRole: {
                role_key: '',
                role_name: '',
                description: ''
            },
            roleColors: roleColors,
            
            getRoleBorderClass() {
                const colors = this.roleColors[this.selectedRole] || this.roleColors['viewer'];
                return colors.border;
            },
            
            getRoleHeaderClass() {
                const colors = this.roleColors[this.selectedRole] || this.roleColors['viewer'];
                return colors.header;
            },
            
            getRoleIndicatorClass() {
                const colors = this.roleColors[this.selectedRole] || this.roleColors['viewer'];
                return colors.indicator;
            },
            
            getRoleTextClass() {
                const colors = this.roleColors[this.selectedRole] || this.roleColors['viewer'];
                return colors.text;
            },
            
            init() {
            this.updateGrantedCounts();
            this.filterPermissions();
            
            // Watch for selectedRole changes and update counts
            this.$watch('selectedRole', () => {
                this.updateGrantedCounts();
            });
            
            // Warn before leaving if there are unsaved changes
            window.addEventListener('beforeunload', (e) => {
                if (this.hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });
        },
        
        togglePermission(permission, granted) {
            this.matrix[this.selectedRole][permission] = granted;
            this.checkUnsavedChanges();
            this.updateGrantedCounts();
        },
        
        toggleCategory(categoryName) {
            const category = this.categories.find(c => c.name === categoryName);
            if (category) {
                category.expanded = !category.expanded;
            }
        },
        
        toggleCategoryAll(categoryName, granted) {
            const category = this.categories.find(c => c.name === categoryName);
            if (category) {
                category.permissions.forEach(perm => {
                    this.matrix[this.selectedRole][perm.key] = granted;
                });
                this.checkUnsavedChanges();
                this.updateGrantedCounts();
            }
        },
        
        expandAll() {
            this.categories.forEach(cat => cat.expanded = true);
        },
        
        collapseAll() {
            this.categories.forEach(cat => cat.expanded = false);
        },
        
        filterPermissions() {
            if (!this.searchQuery.trim()) {
                // Create new array reference to ensure reactivity
                this.filteredCategories = this.categories.map(cat => ({ ...cat }));
                return;
            }
            
            const query = this.searchQuery.toLowerCase();
            this.filteredCategories = this.categories.map(category => {
                const filteredPerms = category.permissions.filter(perm => 
                    perm.label.toLowerCase().includes(query) ||
                    perm.description.toLowerCase().includes(query) ||
                    perm.key.toLowerCase().includes(query)
                );
                
                if (filteredPerms.length > 0 || category.displayName.toLowerCase().includes(query)) {
                    return {
                        ...category,
                        permissions: filteredPerms.length > 0 ? filteredPerms : category.permissions,
                        expanded: true,
                        grantedCount: category.grantedCount // Preserve the count
                    };
                }
                return null;
            }).filter(cat => cat !== null);
        },
        
        updateGrantedCounts() {
            if (!this.matrix[this.selectedRole]) {
                return;
            }
            
            this.categories.forEach(category => {
                const count = category.permissions.filter(perm => 
                    this.matrix[this.selectedRole] && this.matrix[this.selectedRole][perm.key] === true
                ).length;
                category.grantedCount = count;
            });
            
            // Update filteredCategories to ensure reactivity
            // If search is active, recalculate filtered categories with updated counts
            if (this.searchQuery.trim()) {
                this.filterPermissions();
            } else {
                // Force reactivity by creating a new array reference
                this.filteredCategories = this.categories.map(cat => ({ ...cat }));
            }
        },
        
        checkUnsavedChanges() {
            const current = JSON.stringify(this.matrix[this.selectedRole]);
            const original = JSON.stringify(this.originalMatrix[this.selectedRole] || {});
            this.hasUnsavedChanges = current !== original;
        },
        
        getToggleStyle(permission) {
            const isActive = this.matrix[this.selectedRole][permission] || false;
            if (isActive) {
                return 'background-color: rgb(5, 150, 105); border: 1px solid rgb(4, 120, 87);';
            } else {
                return 'background-color: rgb(229, 231, 235); border: 1px solid rgb(209, 213, 219);';
            }
        },
        
        getToggleCircleStyle(permission) {
            const isActive = this.matrix[this.selectedRole][permission] || false;
            if (isActive) {
                return 'transform: translateX(1.25rem); left: 2px;';
            } else {
                return 'transform: translateX(0); left: 2px;';
            }
        },
        
        hasChanged(permission) {
            const current = this.matrix[this.selectedRole][permission] || false;
            const original = this.originalMatrix[this.selectedRole]?.[permission] || false;
            return current !== original;
        },
        
        async save() {
            if (this.saving) return;
            
            this.saving = true;
            try {
      const body = { permissions: { [this.selectedRole]: this.matrix[this.selectedRole] } };
      const resp = await fetch('{{ admin_route('permissions.update') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(body)
      });
                
                const data = await resp.json().catch(() => ({}));
                
                if (resp.ok) {
                    // Update original matrix
                    this.originalMatrix[this.selectedRole] = JSON.parse(JSON.stringify(this.matrix[this.selectedRole]));
                    this.hasUnsavedChanges = false;
                    this.showNotification('Permissions saved successfully!', 'success');
                    // Refresh page after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    this.showNotification(data.message || 'Failed to save permissions', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showNotification('Network error. Please try again.', 'error');
            } finally {
                this.saving = false;
            }
        },
        
        async resetToDefaults() {
            if (this.resetting) return;
            
            this.resetting = true;
            try {
                const resp = await fetch('{{ admin_route('permissions.reset') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await resp.json();
                
                if (resp.ok) {
                    this.showResetModal = false;
                    this.showNotification('Permissions reset to defaults successfully!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    this.showNotification(data.message || 'Failed to reset permissions', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showNotification('Network error. Please try again.', 'error');
            } finally {
                this.resetting = false;
            }
        },
        
        async createRole() {
            if (this.creatingRole) return;
            
            this.creatingRole = true;
            try {
                const resp = await fetch('{{ admin_route('permissions.store-role') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.newRole)
                });
                
                const data = await resp.json();
                
                if (resp.ok) {
                    this.showAddRoleModal = false;
                    this.newRole = { role_key: '', role_name: '', description: '' };
                    this.showNotification('Role created successfully!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    this.showNotification(data.message || 'Failed to create role', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showNotification('Network error. Please try again.', 'error');
            } finally {
                this.creatingRole = false;
            }
        },
        
        showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl text-white z-50 shadow-lg ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 
                'bg-blue-500'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transition = 'opacity 0.3s';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
    }
  }
}
</script>
@endsection
