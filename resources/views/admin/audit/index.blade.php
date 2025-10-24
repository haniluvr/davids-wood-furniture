@extends('admin.layouts.app')

@section('title', 'Audit Trail')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Audit Trail
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li class="font-medium text-primary">Audit Trail</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
    <!-- Audit Stats -->
    <div class="lg:col-span-1">
        <div class="space-y-6">
            <!-- Today's Activity -->
            <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                        <i data-lucide="activity" class="w-6 h-6 text-primary"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-black dark:text-white">Today's Activity</h4>
                        <p class="text-2xl font-bold text-primary">47</p>
                    </div>
                </div>
            </div>

            <!-- This Week -->
            <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                        <i data-lucide="calendar" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-black dark:text-white">This Week</h4>
                        <p class="text-2xl font-bold text-blue-600">312</p>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                        <i data-lucide="trending-up" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-black dark:text-white">This Month</h4>
                        <p class="text-2xl font-bold text-green-600">1,247</p>
                    </div>
                </div>
            </div>

            <!-- Top Users -->
            <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                <h4 class="text-lg font-semibold text-black dark:text-white mb-4">Most Active Users</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">JD</span>
                            </div>
                            <div>
                                <p class="font-medium text-black dark:text-white">John Doe</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Admin</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-primary">89</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">JS</span>
                            </div>
                            <div>
                                <p class="font-medium text-black dark:text-white">Jane Smith</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Manager</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-blue-600">67</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">MJ</span>
                            </div>
                            <div>
                                <p class="font-medium text-black dark:text-white">Mike Johnson</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Staff</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-green-600">45</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Log -->
    <div class="lg:col-span-3">
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <!-- Filters -->
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Filter by:</label>
                        <select class="rounded border border-stroke bg-transparent px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                            <option>All Actions</option>
                            <option>Login/Logout</option>
                            <option>Product Changes</option>
                            <option>Order Changes</option>
                            <option>User Changes</option>
                            <option>Settings Changes</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">User:</label>
                        <select class="rounded border border-stroke bg-transparent px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                            <option>All Users</option>
                            <option>John Doe</option>
                            <option>Jane Smith</option>
                            <option>Mike Johnson</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Date:</label>
                        <input type="date" class="rounded border border-stroke bg-transparent px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="flex items-center gap-2 rounded-lg border border-gray-500 bg-gray-500 px-4 py-2 text-white hover:bg-gray-600 transition-colors duration-200">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Export
                    </button>
                    <button class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        Apply Filters
                    </button>
                </div>
            </div>

            <!-- Audit Log Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Resource</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">IP Address</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Login -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                    2024-01-15 14:32:15
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">JD</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-black dark:text-white">John Doe</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Admin</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    <i data-lucide="log-in" class="w-3 h-3 mr-1"></i>
                                    Login
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                Admin Panel
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                192.168.1.100
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Success
                                </span>
                            </td>
                        </tr>

                        <!-- Product Update -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                    2024-01-15 14:28:42
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">JS</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-black dark:text-white">Jane Smith</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Manager</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    <i data-lucide="edit" class="w-3 h-3 mr-1"></i>
                                    Update
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                Product: Oak Dining Table
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                192.168.1.101
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Success
                                </span>
                            </td>
                        </tr>

                        <!-- Order Status Change -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                    2024-01-15 14:25:18
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">MJ</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-black dark:text-white">Mike Johnson</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Staff</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    <i data-lucide="truck" class="w-3 h-3 mr-1"></i>
                                    Status Change
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                Order #12345: Processing → Shipped
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                192.168.1.102
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Success
                                </span>
                            </td>
                        </tr>

                        <!-- Failed Login -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                    2024-01-15 14:20:33
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-gray-600 font-semibold text-sm">?</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-black dark:text-white">Unknown User</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">admin@test.com</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                    <i data-lucide="log-in" class="w-3 h-3 mr-1"></i>
                                    Login Attempt
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                Admin Panel
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                192.168.1.200
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                    Failed
                                </span>
                            </td>
                        </tr>

                        <!-- Settings Change -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                    2024-01-15 14:15:07
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">JD</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-black dark:text-white">John Doe</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Admin</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                    <i data-lucide="settings" class="w-3 h-3 mr-1"></i>
                                    Settings Update
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                Email Settings: SMTP Configuration
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                192.168.1.100
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Success
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">247</span> results
                </div>
                <div class="flex items-center gap-2">
                    <button class="flex items-center gap-2 rounded-lg border border-stroke bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-300 dark:hover:bg-gray-800">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        Previous
                    </button>
                    <button class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                        1
                    </button>
                    <button class="flex items-center gap-2 rounded-lg border border-stroke bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-300 dark:hover:bg-gray-800">
                        2
                    </button>
                    <button class="flex items-center gap-2 rounded-lg border border-stroke bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-300 dark:hover:bg-gray-800">
                        3
                    </button>
                    <button class="flex items-center gap-2 rounded-lg border border-stroke bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-300 dark:hover:bg-gray-800">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
