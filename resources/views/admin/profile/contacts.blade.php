@extends('admin.layouts.app')

@section('title', 'My Contacts')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg">
                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">My Contacts</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">View your coworkers and their contact information</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <div class="bg-white dark:bg-boxdark rounded-xl shadow-lg border border-stone-200 dark:border-strokedark p-6">
            <form method="GET" action="{{ admin_route('profile.contacts') }}" class="flex items-center gap-4">
                <div class="flex-1 relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name or email..."
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-stone-200 bg-white text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400"
                    />
                </div>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-purple-700 hover:to-pink-700 hover:shadow-xl"
                >
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ admin_route('profile.contacts') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 border border-stone-200 bg-white text-sm font-medium text-stone-700 rounded-xl transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Contacts List -->
    @if($admins->count() > 0)
        <div class="bg-white dark:bg-boxdark rounded-xl shadow-lg border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200 dark:divide-strokedark">
                    <thead class="bg-stone-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-stone-500 dark:text-gray-400 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-stone-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-stone-500 dark:text-gray-400 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-stone-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-stone-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-boxdark divide-y divide-stone-200 dark:divide-strokedark">
                        @foreach($admins as $contact)
                            @php
                                $roleClasses = match($contact->role) {
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
                                
                                // Check if user is online (logged in within last 15 minutes OR is the currently logged in user)
                                $currentUser = auth()->guard('admin')->user();
                                $isOnline = ($currentUser && $contact->id === $currentUser->id) || 
                                           ($contact->last_login_at && $contact->last_login_at->diffInMinutes(now()) <= 15);
                            @endphp
                            <tr class="hover:bg-stone-50 dark:hover:bg-gray-800 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12 relative">
                                            <img src="{{ $contact->avatar_url }}" 
                                                 alt="{{ $contact->full_name }}" 
                                                 class="h-12 w-12 rounded-full object-cover border-2 border-stone-200 dark:border-strokedark">
                                            @if($isOnline)
                                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-boxdark"></div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-stone-900 dark:text-white">
                                                {{ $contact->full_name }}
                                            </div>
                                            @if($contact->position)
                                                <div class="text-sm text-stone-500 dark:text-gray-400">{{ $contact->position }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleClasses }}">
                                        {{ ucfirst(str_replace('_', ' ', $contact->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-900 dark:text-white">
                                    {{ $contact->department ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-stone-900 dark:text-white">{{ $contact->email }}</div>
                                    @if($contact->personal_email && $contact->personal_email !== $contact->email)
                                        <div class="text-xs text-stone-500 dark:text-gray-400">{{ $contact->personal_email }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @php
                                        // Extract username from email (part before @dwatelier.co)
                                        $username = str_replace('@dwatelier.co', '', $contact->email);
                                    @endphp
                                    <a href="{{ admin_route('profile.contact-view', $username) }}" 
                                       class="inline-flex items-center gap-2 text-primary hover:text-primary/80 transition-colors duration-200">
                                        <span>View Profile</span>
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-boxdark rounded-xl shadow-lg border border-stone-200 dark:border-strokedark p-12">
            <div class="text-center">
                <div class="mx-auto h-16 w-16 rounded-full bg-stone-100 dark:bg-gray-800 flex items-center justify-center mb-4">
                    <i data-lucide="users" class="h-8 w-8 text-stone-400 dark:text-gray-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white mb-2">No Contacts Found</h3>
                <p class="text-stone-600 dark:text-gray-400">There are no active employees to display.</p>
            </div>
        </div>
    @endif
</div>
@endsection

