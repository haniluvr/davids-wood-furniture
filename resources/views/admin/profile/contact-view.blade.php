@extends('admin.layouts.app')

@section('title', $admin->full_name . ' - Profile')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ admin_route('profile.contacts') }}" 
                   class="flex items-center justify-center w-10 h-10 rounded-xl border border-stone-200 bg-white text-stone-700 hover:bg-stone-50 transition-colors dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                        <i data-lucide="user" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">{{ $admin->full_name }}</h1>
                        <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Coworker Profile</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        <!-- Profile Header Card -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-8 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    <div class="flex-shrink-0">
                        <img src="{{ $admin->avatar_url }}" 
                             alt="{{ $admin->full_name }}" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-boxdark shadow-lg">
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h2 class="text-2xl font-bold text-stone-900 dark:text-white">{{ $admin->full_name }}</h2>
                        <p class="text-stone-600 dark:text-gray-400 mt-1">{{ $admin->email }}</p>
                        <div class="mt-3 flex flex-wrap items-center gap-3">
                            @php
                                $roleClasses = match($admin->role) {
                                    'super_admin' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    'admin' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                    'sales_support_manager' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    'inventory_fulfillment_manager' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-400',
                                    'product_content_manager' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                    'finance_reporting_analyst' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
                                    'staff' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'viewer' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $roleClasses }}">
                                {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                            </span>
                            @if($admin->department)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-stone-100 text-stone-800 dark:bg-stone-900/30 dark:text-stone-400">
                                    <i data-lucide="building" class="w-3 h-3"></i>
                                    {{ $admin->department }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                        <i data-lucide="user" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Personal Information</h3>
                </div>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            First Name
                        </label>
                        <input
                            type="text"
                            value="{{ $admin->first_name }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Last Name
                        </label>
                        <input
                            type="text"
                            value="{{ $admin->last_name }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Phone Number
                        </label>
                        <input
                            type="text"
                            value="{{ $admin->phone ?? 'N/A' }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Personal Email
                        </label>
                        <input
                            type="email"
                            value="{{ $admin->personal_email ?? 'N/A' }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Information -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl">
                        <i data-lucide="briefcase" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Work Information</h3>
                </div>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Role
                        </label>
                        <input
                            type="text"
                            value="{{ ucfirst(str_replace('_', ' ', $admin->role)) }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Department
                        </label>
                        <input
                            type="text"
                            value="{{ $admin->department ?? 'N/A' }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Position
                        </label>
                        <input
                            type="text"
                            value="{{ $admin->position ?? 'N/A' }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Login Email
                        </label>
                        <input
                            type="email"
                            value="{{ $admin->email }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="flex justify-end">
            <a href="{{ admin_route('profile.contacts') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 border border-stone-200 bg-white text-sm font-medium text-stone-700 rounded-xl transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Contacts
            </a>
        </div>
    </div>
</div>
@endsection

