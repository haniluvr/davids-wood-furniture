@extends('admin.layouts.app')

@section('title', 'Role & Permission Management')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Role & Permission Management
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li class="font-medium text-primary">Permissions</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3" x-data='permissionsPage()'>
    <!-- Roles List -->
    <div class="lg:col-span-1">
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-black dark:text-white">Admin Roles</h4>
                <button class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Role
                </button>
            </div>

            <div class="space-y-3">
                <!-- Super Admin Role -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20" :class="selectedRole==='super_admin' ? 'ring-2 ring-red-400' : ''">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="font-semibold text-red-800 dark:text-red-300">Super Admin</h5>
                            <p class="text-sm text-red-600 dark:text-red-400">Full system access</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                1 user
                            </span>
                            <button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" @click.prevent="selectedRole='super_admin'">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Admin Role -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20" :class="selectedRole==='admin' ? 'ring-2 ring-blue-400' : ''">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="font-semibold text-blue-800 dark:text-blue-300">Admin</h5>
                            <p class="text-sm text-blue-600 dark:text-blue-400">Full admin access</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                2 users
                            </span>
                            <button class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" @click.prevent="selectedRole='admin'">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sales & Customer Support Manager -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20" :class="selectedRole==='sales_support_manager' ? 'ring-2 ring-green-400' : ''">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="font-semibold text-green-800 dark:text-green-300">Sales & Customer Support Manager</h5>
                            <p class="text-sm text-green-600 dark:text-green-400">Orders, customers, messages, returns</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                3 users
                            </span>
                            <button class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" @click.prevent="selectedRole='sales_support_manager'">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Inventory & Fulfillment Manager -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20" :class="selectedRole==='inventory_fulfillment_manager' ? 'ring-2 ring-emerald-400' : ''">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="font-semibold text-emerald-800 dark:text-emerald-300">Inventory & Fulfillment Manager</h5>
                            <p class="text-sm text-emerald-600 dark:text-emerald-400">Inventory, stock, fulfillment</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                2 users
                            </span>
                            <button class="text-emerald-700 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300" @click.prevent="selectedRole='inventory_fulfillment_manager'">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product & Content Manager -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20" :class="selectedRole==='product_content_manager' ? 'ring-2 ring-purple-400' : ''">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="font-semibold text-purple-800 dark:text-purple-300">Product & Content Manager</h5>
                            <p class="text-sm text-purple-600 dark:text-purple-400">Catalog, CMS, reviews moderation</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                1 user
                            </span>
                            <button class="text-purple-700 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300" @click.prevent="selectedRole='product_content_manager'">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Finance & Reporting Analyst -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20" :class="selectedRole==='finance_reporting_analyst' ? 'ring-2 ring-orange-400' : ''">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="font-semibold text-orange-800 dark:text-orange-300">Finance & Reporting Analyst</h5>
                            <p class="text-sm text-orange-600 dark:text-orange-400">Analytics, revenue, read-only orders/customers</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:bg-orange-900/30 dark:text-orange-400">
                                1 user
                            </span>
                            <button class="text-orange-700 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300" @click.prevent="selectedRole='finance_reporting_analyst'">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Staff Role -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20" :class="selectedRole==='staff' ? 'ring-2 ring-yellow-400' : ''">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="font-semibold text-yellow-800 dark:text-yellow-300">Staff</h5>
                            <p class="text-sm text-yellow-600 dark:text-yellow-400">Limited access</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                5 users
                            </span>
                            <button class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300" @click.prevent="selectedRole='staff'">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Viewer Role -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900/20 dark:to-gray-800/20" :class="selectedRole==='viewer' ? 'ring-2 ring-gray-400' : ''">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="font-semibold text-gray-800 dark:text-gray-300">Viewer</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Read-only access</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                                2 users
                            </span>
                            <button class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300" @click.prevent="selectedRole='viewer'">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Management -->
    <div class="lg:col-span-2">
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-black dark:text-white">Permission Matrix <span class="text-sm text-gray-500 ml-2" x-text="'— Role: ' + selectedRole.replaceAll('_',' ') "></span></h4>
                <div class="flex items-center gap-2">
                    <button @click="save()" class="flex items-center gap-2 rounded-lg border border-gray-500 bg-gray-500 px-4 py-2 text-white hover:bg-gray-600 transition-colors duration-200">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Changes
                    </button>
                </div>
            </div>

            <!-- Permission Categories -->
            <div class="space-y-6">
                <!-- Dashboard -->
                <div class="border border-stroke dark:border-strokedark rounded-lg p-4">
                    <h5 class="font-semibold text-black dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 text-primary"></i>
                        Dashboard
                    </h5>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">View Dashboard</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">View Analytics</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="border border-stroke dark:border-strokedark rounded-lg p-4">
                    <h5 class="font-semibold text-black dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5 text-blue-600"></i>
                        Products
                    </h5>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">View Products</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['products.view']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Create Products</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['products.create']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Edit Products</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['products.edit']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Delete Products</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['products.delete']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Orders -->
                <div class="border border-stroke dark:border-strokedark rounded-lg p-4">
                    <h5 class="font-semibold text-black dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="shopping-cart" class="w-5 h-5 text-green-600"></i>
                        Orders
                    </h5>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">View Orders</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['orders.view']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Create Orders</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['orders.create']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Update Order Status</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['orders.update_status']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Cancel Orders</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['orders.delete']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Users -->
                <div class="border border-stroke dark:border-strokedark rounded-lg p-4">
                    <h5 class="font-semibold text-black dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="users" class="w-5 h-5 text-purple-600"></i>
                        Users
                    </h5>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">View Users</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['users.view']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Create Users</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['users.create']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Edit Users</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['users.edit']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Delete Users</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['users.delete']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="border border-stroke dark:border-strokedark rounded-lg p-4">
                    <h5 class="font-semibold text-black dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="settings" class="w-5 h-5 text-orange-600"></i>
                        Settings
                    </h5>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">View Settings</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['settings.view']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Edit Settings</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['settings.edit']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Manage Permissions</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['admins.edit']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">System Administration</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="matrix[selectedRole]['audit_logs.view']">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function permissionsPage(){
  return {
    selectedRole: 'admin',
    matrix: @js($rolePermissions),
    async save(){
      const body = { permissions: { [this.selectedRole]: this.matrix[this.selectedRole] } };
      const resp = await fetch('{{ admin_route('permissions.update') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(body)
      });
      if(resp.ok){ location.reload(); } else { alert('Save failed'); }
    }
  }
}
</script>
@endsection
