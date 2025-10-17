<!-- Header Start -->
<header class="sticky top-0 z-999 flex w-full bg-white/80 backdrop-blur-xl border-b border-stroke/50 dark:bg-boxdark/80 dark:border-strokedark/50">
    <div class="flex flex-grow items-center justify-between px-4 py-4 md:px-6 2xl:px-11">
        <div class="flex items-center gap-4">
            <!-- Sidebar Toggle Button -->
            <button
                class="flex items-center justify-center w-10 h-10 rounded-xl border border-stroke bg-white text-gray-600 hover:text-primary hover:bg-primary/5 hover:border-primary/20 transition-all duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-400 dark:hover:text-primary dark:hover:bg-primary/10"
                @click.stop="
                    if (window.innerWidth < 1024) {
                        sidebarOpen = !sidebarOpen;
                    } else {
                        sidebarCollapsed = !sidebarCollapsed;
                    }
                "
                :class="sidebarOpen ? 'text-primary bg-primary/5 border-primary/20' : ''"
            >
                <i data-lucide="menu" class="w-5 h-5 transition-transform duration-200" :class="sidebarOpen ? 'rotate-90' : ''" x-show="window.innerWidth < 1024"></i>
                <i data-lucide="panel-left" class="w-5 h-5 transition-transform duration-200" x-show="window.innerWidth >= 1024 && !sidebarCollapsed"></i>
                <i data-lucide="panel-right" class="w-5 h-5 transition-transform duration-200" x-show="window.innerWidth >= 1024 && sidebarCollapsed"></i>
            </button>

            <!-- Search Bar -->
            <div class="hidden sm:block">
                <form action="#" method="POST">
                    <div class="relative">
                        <button class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors duration-200 dark:text-bodydark dark:hover:text-primary">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>

                        <input
                            type="text"
                            placeholder="Search or type command..."
                            class="w-full bg-gray-100 pl-11 pr-16 py-3 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all duration-200 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 xl:w-80 rounded-lg border-0 hover:bg-gray-200 dark:hover:bg-gray-600"
                        />
                        
                        <!-- Keyboard Shortcut Button -->
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                            <span class="text-xs font-medium bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded border">⌘K</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex items-center gap-3 2xsm:gap-7">
            <ul class="flex items-center gap-2 2xsm:gap-4">
                <!-- Dark Mode Toggle Button -->
                <li>
                    <button
                        @click="darkMode = !darkMode"
                        class="flex items-center justify-center w-10 h-10 rounded-xl border border-stroke bg-white text-gray-600 hover:text-primary hover:bg-primary/5 hover:border-primary/20 transition-all duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-400 dark:hover:text-primary dark:hover:bg-primary/10"
                        :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                    >
                        <i data-lucide="moon" class="w-4 h-4 transition-opacity duration-200" :class="darkMode ? 'opacity-0' : 'opacity-100'"></i>
                        <i data-lucide="sun" class="w-4 h-4 absolute transition-opacity duration-200" :class="darkMode ? 'opacity-100' : 'opacity-0'"></i>
                    </button>
                </li>
                <!-- Dark Mode Toggle Button -->

                <!-- Notification Menu Area -->
                <li class="relative" x-data="{ dropdownOpen: false, notifying: true }">
                    <a
                        class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-brand-brown/20 bg-white/80 backdrop-blur-sm hover:text-brand-green hover:bg-brand-green/5 hover:border-brand-green/20 transition-all duration-200 dark:border-brand-brown/30 dark:bg-brand-dark-dm/80 dark:text-brand-beige dark:hover:bg-brand-green/10"
                        href="#"
                        @click.prevent="dropdownOpen = ! dropdownOpen; notifying = false"
                    >
                        <span
                            :class="!notifying && 'hidden'"
                            class="absolute -top-1 -right-1 z-10 h-3 w-3 rounded-full bg-red-500 border-2 border-white dark:border-boxdark"
                        >
                            <span
                                class="absolute -z-1 inline-flex h-full w-full animate-ping rounded-full bg-red-500 opacity-75"
                            ></span>
                        </span>

                        <i data-lucide="bell" class="w-4 h-4"></i>
                    </a>

                    <!-- Dropdown Start -->
                    <div
                        x-show="dropdownOpen"
                        @click.outside="dropdownOpen = false"
                        class="absolute -right-27 mt-3 flex h-96 w-80 flex-col rounded-2xl border border-stroke/50 bg-white/95 backdrop-blur-xl shadow-2xl dark:border-strokedark/50 dark:bg-boxdark/95 sm:right-0"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                    >
                        <div class="px-6 py-4 border-b border-stroke/50 dark:border-strokedark/50">
                            <div class="flex items-center justify-between">
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h5>
                                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">3 new</span>
                            </div>
                        </div>

                        <ul class="flex h-auto flex-col overflow-y-auto">
                            <li>
                                <a
                                    class="flex flex-col gap-3 border-b border-stroke/30 px-6 py-4 hover:bg-gray-50/80 dark:border-strokedark/30 dark:hover:bg-gray-800/50 transition-colors duration-200"
                                    href="#"
                                >
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                                            <i data-lucide="shopping-cart" class="h-4 w-4 text-green-600 dark:text-green-400"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">New Order Received</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Order #12345 from John Doe - $299.99</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">2 minutes ago</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a
                                    class="flex flex-col gap-3 border-b border-stroke/30 px-6 py-4 hover:bg-gray-50/80 dark:border-strokedark/30 dark:hover:bg-gray-800/50 transition-colors duration-200"
                                    href="#"
                                >
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30">
                                            <i data-lucide="alert-triangle" class="h-4 w-4 text-yellow-600 dark:text-yellow-400"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">Low Stock Alert</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Oak Dining Table is running low (3 remaining)</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">1 hour ago</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a
                                    class="flex flex-col gap-3 px-6 py-4 hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-colors duration-200"
                                    href="#"
                                >
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                                            <i data-lucide="user-plus" class="h-4 w-4 text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">New Customer Registration</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Sarah Johnson has joined David's Wood</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">3 hours ago</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        
                        <div class="px-6 py-3 border-t border-stroke/50 dark:border-strokedark/50">
                            <a href="#" class="block text-center text-sm font-medium text-primary hover:text-primary/80 transition-colors duration-200">
                                View all notifications
                            </a>
                        </div>
                    </div>
                    <!-- Dropdown End -->
                </li>
                <!-- Notification Menu Area -->
            </ul>

            <!-- User Area -->
            <div class="relative" x-data="{ dropdownOpen: false }">
                <a
                    class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-all duration-200"
                    href="#"
                    @click.prevent="dropdownOpen = ! dropdownOpen"
                >
                    <span class="hidden text-right lg:block">
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ auth('admin')->user()->first_name }} {{ auth('admin')->user()->last_name }}</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(auth('admin')->user()->role) }}</span>
                    </span>

                    <div class="relative">
                        <span class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center shadow-lg">
                            <span class="text-white font-semibold text-sm">
                            {{ substr(auth('admin')->user()->first_name, 0, 1) }}{{ substr(auth('admin')->user()->last_name, 0, 1) }}
                        </span>
                    </span>
                        <span class="absolute -bottom-1 -right-1 h-3 w-3 rounded-full bg-green-500 border-2 border-white dark:border-boxdark"></span>
                    </div>

                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500 dark:text-gray-400 hidden sm:block transition-transform duration-200" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                </a>

                <!-- Dropdown Start -->
                <div
                    x-show="dropdownOpen"
                    @click.outside="dropdownOpen = false"
                    class="absolute right-0 mt-3 flex w-64 flex-col rounded-2xl border border-stroke/50 bg-white/95 backdrop-blur-xl shadow-2xl dark:border-strokedark/50 dark:bg-boxdark/95"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                >
                    <!-- User Info Header -->
                    <div class="px-6 py-4 border-b border-stroke/50 dark:border-strokedark/50">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center shadow-lg">
                                <span class="text-white font-semibold">
                                    {{ substr(auth('admin')->user()->first_name, 0, 1) }}{{ substr(auth('admin')->user()->last_name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth('admin')->user()->first_name }} {{ auth('admin')->user()->last_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(auth('admin')->user()->role) }}</p>
                            </div>
                        </div>
                    </div>

                    <ul class="flex flex-col py-2">
                        <li>
                            <a
                                href="#"
                                class="flex items-center gap-3 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50/80 hover:text-primary transition-colors duration-200 dark:text-gray-300 dark:hover:bg-gray-800/50 dark:hover:text-primary"
                            >
                                <i data-lucide="user" class="w-4 h-4"></i>
                                My Profile
                            </a>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center gap-3 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50/80 hover:text-primary transition-colors duration-200 dark:text-gray-300 dark:hover:bg-gray-800/50 dark:hover:text-primary"
                            >
                                <i data-lucide="contact" class="w-4 h-4"></i>
                                My Contacts
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ route('admin.settings.index') }}"
                                class="flex items-center gap-3 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50/80 hover:text-primary transition-colors duration-200 dark:text-gray-300 dark:hover:bg-gray-800/50 dark:hover:text-primary"
                            >
                                <i data-lucide="settings" class="w-4 h-4"></i>
                                Account Settings
                            </a>
                        </li>
                    </ul>
                    
                    <div class="border-t border-stroke/50 dark:border-strokedark/50 p-2">
                        <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50/80 hover:text-red-700 transition-colors duration-200 dark:text-red-400 dark:hover:bg-red-900/20 dark:hover:text-red-300 rounded-lg">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            Log Out
                        </button>
                    </form>
                    </div>
                </div>
                <!-- Dropdown End -->
            </div>
            <!-- User Area -->
        </div>
    </div>
</header>
<!-- Header End -->