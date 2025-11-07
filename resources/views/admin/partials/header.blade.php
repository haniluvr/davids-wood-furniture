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
            <div class="hidden sm:block relative" x-data="searchCommand()">
                <div class="relative">
                    <button 
                        @click="$refs.searchInput.focus()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors duration-200 dark:text-bodydark dark:hover:text-primary pointer-events-none"
                    >
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>

                    <input
                        x-ref="searchInput"
                        type="text"
                        x-model="query"
                        @keydown.arrow-down.prevent="selectNext()"
                        @keydown.arrow-up.prevent="selectPrevious()"
                        @keydown.enter.prevent="navigateToSelected()"
                        @keydown.escape="query = ''; open = false; $refs.searchInput.blur()"
                        @focus="open = true"
                        @blur="setTimeout(() => { if (!clickingDropdown) open = false; }, 200)"
                        placeholder="Search or type command..."
                        class="w-full xl:w-[500px] h-10 bg-gray-100 pl-11 pr-16 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all duration-200 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 rounded-lg border border-stroke dark:border-strokedark hover:bg-gray-200 dark:hover:bg-gray-600"
                        autocomplete="off"
                    />
                    
                    <!-- Keyboard Shortcut Button -->
                    <button 
                        type="button" 
                        @click="$refs.searchInput.focus()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 pointer-events-none"
                    >
                        <span class="text-xs font-medium bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded border border-stroke dark:border-strokedark">
                            <span class="hidden mac:inline">⌘</span><span class="mac:hidden">Ctrl</span>K
                        </span>
                    </button>
                </div>

                <!-- Search Dropdown -->
                <div 
                    x-show="open && query.length > 0 && filteredItems.length > 0"
                    @click.away="open = false"
                    @mousedown="clickingDropdown = true"
                    @mouseup="clickingDropdown = false"
                    class="absolute top-full left-0 mt-2 w-full xl:w-[500px] bg-white dark:bg-boxdark rounded-xl shadow-2xl border border-stroke dark:border-strokedark overflow-hidden z-99999"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                >
                    <!-- Results -->
                    <div class="max-h-96 overflow-y-auto">
                        <ul class="py-2">
                            <template x-for="(item, index) in filteredItems" :key="item.id">
                                <li>
                                    <a
                                        :href="item.url"
                                        @click="open = false; query = ''"
                                        @mousedown="clickingDropdown = true"
                                        class="flex items-center gap-3 px-6 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150"
                                        :class="selectedIndex === index ? 'bg-primary/5 dark:bg-primary/10' : ''"
                                        x-init="setTimeout(() => { if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons(); }, 10)"
                                    >
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
                                             :class="item.category === 'Dashboard' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' :
                                                     item.category === 'Orders' ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' :
                                                     item.category === 'Products' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400' :
                                                     item.category === 'Inventory' ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400' :
                                                     item.category === 'Customers' ? 'bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400' :
                                                     item.category === 'Shipping' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400' :
                                                     item.category === 'Content' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' :
                                                     item.category === 'Reports' ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400' :
                                                     'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400'">
                                            <i :data-lucide="item.icon" class="w-5 h-5"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="item.title"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="item.category"></p>
                                        </div>
                                        <kbd class="hidden sm:flex items-center px-2 py-1 text-xs font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                                            Enter
                                        </kbd>
                                    </a>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
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
                <li class="relative" x-data="{ 
                    dropdownOpen: false,
                    notifications: [],
                    unreadCount: 0
                }" x-init="
                    // Sync with parent scope
                    function syncNotifications() {
                        if ($root && $root.notifications) {
                            // Create new array to trigger reactivity
                            notifications = [...$root.notifications];
                            unreadCount = $root.unreadCount || 0;
                            console.log('Synced notifications in dropdown:', notifications.length);
                        }
                    }
                    // Watch parent scope for changes
                    $watch('$root.notifications', () => {
                        syncNotifications();
                    });
                    $watch('$root.unreadCount', () => {
                        if ($root) {
                            unreadCount = $root.unreadCount || 0;
                        }
                    });
                    // Listen for custom event
                    window.addEventListener('notifications-updated', (e) => {
                        notifications = [...e.detail.notifications];
                        unreadCount = e.detail.unreadCount;
                        const unreadNotifications = notifications.filter(n => !n.read);
                        console.log('Dropdown received notifications-updated event:', {
                            total: notifications.length,
                            unread: unreadNotifications.length,
                            unreadCount: unreadCount,
                            unreadNotifications: unreadNotifications
                        });
                    });
                    // Initial sync
                    syncNotifications();
                    // Also sync periodically as fallback
                    setInterval(syncNotifications, 1000);
                ">
                    <a
                        class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-brand-brown/20 bg-white/80 backdrop-blur-sm hover:text-brand-green hover:bg-brand-green/5 hover:border-brand-green/20 transition-all duration-200 dark:border-brand-brown/30 dark:bg-brand-dark-dm/80 dark:text-brand-beige dark:hover:bg-brand-green/10"
                        href="#"
                        @click.prevent="
                            dropdownOpen = ! dropdownOpen;
                            if (dropdownOpen && typeof loadNotifications === 'function') {
                                setTimeout(() => {
                                    loadNotifications();
                                    // Reinitialize icons after loading
                                    if (typeof lucide !== 'undefined' && lucide.createIcons) {
                                        setTimeout(() => lucide.createIcons(), 200);
                                    }
                                }, 100);
                            }
                        "
                    >
                        <span
                            x-show="unreadCount > 0"
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
                                <div class="flex items-center gap-2">
                                    <span x-show="unreadCount > 0" class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary" x-text="unreadCount + ' new'"></span>
                                    <button x-show="unreadCount > 0" @click="if (typeof markAllAsRead === 'function') markAllAsRead()" class="text-xs text-primary hover:text-primary/80 font-medium">Mark All Read</button>
                                </div>
                            </div>
                        </div>

                        <ul class="flex flex-1 flex-col overflow-y-auto min-h-0">
                            <template x-for="notification in (notifications.filter(n => !n.read) || []).slice(0, 10)" :key="notification.id">
                                <li>
                                    <a
                                        class="flex items-start gap-3 border-b border-stroke/30 px-6 py-4 hover:bg-gray-50/80 dark:border-strokedark/30 dark:hover:bg-gray-800/50 transition-colors duration-200"
                                        :class="notification.read ? 'opacity-60' : 'bg-blue-50/50 dark:bg-blue-900/10'"
                                        :href="notification.data && notification.data.link ? notification.data.link : (notification.data && notification.data.message_id ? '{{ admin_route("messages.show", ":id") }}'.replace(':id', notification.data.message_id) : (notification.data && notification.data.order_id ? '{{ admin_route("orders.show", ":id") }}'.replace(':id', notification.data.order_id) : '#'))"
                                        @click="markNotificationAsRead(notification.id)"
                                    >
                                        <!-- Profile Picture / Avatar -->
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-semibold text-sm"
                                                 :class="{
                                                     'bg-green-500': notification.type === 'order',
                                                     'bg-blue-500': notification.type === 'order_status',
                                                     'bg-yellow-500': notification.type === 'inventory',
                                                     'bg-purple-500': notification.type === 'message',
                                                     'bg-pink-500': notification.type === 'customer',
                                                     'bg-orange-500': notification.type === 'review',
                                                     'bg-red-500': notification.type === 'refund',
                                                     'bg-gray-500': notification.type === 'info' || !notification.type
                                                 }"
                                                 x-text="getNotificationIcon(notification.type)">
                                            </div>
                                        </div>
                                        
                                        <!-- Notification Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate" 
                                                       x-text="getNotificationTitle(notification.type)">
                                                    </p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5 line-clamp-2" 
                                                       x-text="getNotificationContent(notification)">
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1.5" 
                                                       x-text="formatTime(notification.timestamp)">
                                                    </p>
                                                </div>
                                                <div x-show="!notification.read" class="flex-shrink-0 h-2 w-2 rounded-full bg-primary mt-1"></div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </template>
                            <li x-show="notifications.filter(n => !n.read).length === 0" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i data-lucide="bell-off" class="h-8 w-8 mx-auto mb-2 opacity-50"></i>
                                    <p class="text-sm">No unread notifications</p>
                                </div>
                            </li>
                        </ul>
                        
                        <div class="px-6 py-3 border-t border-stroke/50 dark:border-strokedark/50">
                            <a href="{{ admin_route('notifications.index') }}" class="block text-center text-sm font-medium text-primary hover:text-primary/80 transition-colors duration-200">
                                View All Notifications
                            </a>
                        </div>
                    </div>
                    <!-- Dropdown End -->
                </li>
                <!-- Notification Menu Area -->
            </ul>

            <!-- User Area -->
            @auth('admin')
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
                        @php
                            $currentAdmin = auth('admin')->user();
                        @endphp
                        @if($currentAdmin->avatar)
                            <img src="{{ $currentAdmin->avatar_url }}" 
                                 alt="{{ $currentAdmin->full_name }}" 
                                 class="h-10 w-10 rounded-xl object-cover border-2 border-white dark:border-boxdark shadow-lg">
                        @else
                            <span class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center shadow-lg">
                                <span class="text-white font-semibold text-sm">
                                    {{ substr($currentAdmin->first_name, 0, 1) }}{{ substr($currentAdmin->last_name, 0, 1) }}
                                </span>
                            </span>
                        @endif
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
                            @php
                                $currentAdmin = auth('admin')->user();
                            @endphp
                            @if($currentAdmin->avatar)
                                <img src="{{ $currentAdmin->avatar_url }}" 
                                     alt="{{ $currentAdmin->full_name }}" 
                                     class="h-12 w-12 rounded-xl object-cover border-2 border-white dark:border-boxdark shadow-lg">
                            @else
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center shadow-lg">
                                    <span class="text-white font-semibold">
                                        {{ substr($currentAdmin->first_name, 0, 1) }}{{ substr($currentAdmin->last_name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $currentAdmin->first_name }} {{ $currentAdmin->last_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($currentAdmin->role) }}</p>
                            </div>
                        </div>
                    </div>

                    <ul class="flex flex-col py-2">
                        <li>
                            <a
                                href="{{ admin_route('profile.index') }}"
                                class="flex items-center gap-3 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50/80 hover:text-primary transition-colors duration-200 dark:text-gray-300 dark:hover:bg-gray-800/50 dark:hover:text-primary"
                            >
                                <i data-lucide="user" class="w-4 h-4"></i>
                                My Profile
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ admin_route('profile.contacts') }}"
                                class="flex items-center gap-3 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50/80 hover:text-primary transition-colors duration-200 dark:text-gray-300 dark:hover:bg-gray-800/50 dark:hover:text-primary"
                            >
                                <i data-lucide="users" class="w-4 h-4"></i>
                                My Contacts
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ admin_route('profile.settings') }}"
                                class="flex items-center gap-3 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50/80 hover:text-primary transition-colors duration-200 dark:text-gray-300 dark:hover:bg-gray-800/50 dark:hover:text-primary"
                            >
                                <i data-lucide="settings" class="w-4 h-4"></i>
                                Account Settings
                            </a>
                        </li>
                    </ul>
                    
                    <div class="border-t border-stroke/50 dark:border-strokedark/50 p-2">
                        <form method="POST" action="{{ admin_route('logout') }}">
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
            @endauth
            <!-- User Area -->
        </div>
    </div>
</header>
<!-- Header End -->

<script>
function searchCommand() {
    @php
        $admin = auth()->guard('admin')->user();
        $isSuperAdmin = $admin && $admin->isSuperAdmin();
    @endphp
    
    return {
        open: false,
        query: '',
        selectedIndex: 0,
        clickingDropdown: false,
        items: [
            // Dashboard
            @if($admin && ($isSuperAdmin || $admin->hasPermission('dashboard.view')))
            { id: 1, title: 'Dashboard', category: 'Dashboard', url: '{{ admin_route("dashboard") }}', icon: 'layout-dashboard', keywords: 'dashboard home main overview', permission: 'dashboard.view' },
            @endif
            
            // Orders
            @if($admin && ($isSuperAdmin || $admin->hasPermission('orders.view')))
            { id: 2, title: 'All Orders', category: 'Orders', url: '{{ admin_route("orders.index") }}', icon: 'shopping-cart', keywords: 'orders all list view orders list', permission: 'orders.view' },
            @endif
            @if($admin && ($isSuperAdmin || $admin->hasPermission('orders.create')))
            { id: 3, title: 'Create Order', category: 'Orders', url: '{{ admin_route("orders.create") }}', icon: 'plus-circle', keywords: 'create order new add order', permission: 'orders.create' },
            @endif
            @if($admin && ($isSuperAdmin || $admin->hasPermission('orders.view')))
            { id: 4, title: 'Pending Approval', category: 'Orders', url: '{{ admin_route("orders.pending-approval") }}', icon: 'clock', keywords: 'pending approval orders waiting', permission: 'orders.view' },
            { id: 5, title: 'Fulfillment', category: 'Orders', url: '{{ admin_route("orders.fulfillment") }}', icon: 'package-check', keywords: 'fulfillment shipping delivery', permission: 'orders.view' },
            { id: 6, title: 'Returns & Repairs', category: 'Orders', url: '{{ admin_route("orders.returns-repairs.index") }}', icon: 'rotate-ccw', keywords: 'returns repairs refunds', permission: 'orders.view' },
            @endif
            
            // Products
            @if($admin && ($isSuperAdmin || $admin->hasPermission('products.view')))
            { id: 7, title: 'All Products', category: 'Products', url: '{{ admin_route("products.index") }}', icon: 'package', keywords: 'products all list view items', permission: 'products.view' },
            @endif
            @if($admin && ($isSuperAdmin || $admin->hasPermission('products.create')))
            { id: 8, title: 'Add New Product', category: 'Products', url: '{{ admin_route("products.create") }}', icon: 'plus', keywords: 'create product new add product', permission: 'products.create' },
            @endif
            
            // Inventory
            @if($admin && ($isSuperAdmin || $admin->hasPermission('inventory.view')))
            { id: 9, title: 'Inventory Management', category: 'Inventory', url: '{{ admin_route("inventory.index") }}', icon: 'warehouse', keywords: 'inventory stock management warehouse', permission: 'inventory.view' },
            { id: 10, title: 'Low Stock Alerts', category: 'Inventory', url: '{{ admin_route("inventory.low-stock") }}', icon: 'alert-triangle', keywords: 'low stock alerts warning inventory', permission: 'inventory.view' },
            { id: 11, title: 'Inventory Movements', category: 'Inventory', url: '{{ admin_route("inventory.movements") }}', icon: 'arrow-right-left', keywords: 'inventory movements history log', permission: 'inventory.view' },
            @endif
            
            // Customers
            @if($admin && ($isSuperAdmin || $admin->hasPermission('users.view')))
            { id: 12, title: 'All Customers', category: 'Customers', url: '{{ admin_route("users.index") }}', icon: 'users', keywords: 'customers all users list people', permission: 'users.view' },
            @endif
            @if($admin && ($isSuperAdmin || $admin->hasPermission('users.create')))
            { id: 13, title: 'Add Customer', category: 'Customers', url: '{{ admin_route("users.create") }}', icon: 'user-plus', keywords: 'create customer new add user', permission: 'users.create' },
            @endif
            @if($admin && ($isSuperAdmin || $admin->hasPermission('notifications.view')))
            { id: 14, title: 'Messages', category: 'Customers', url: '{{ admin_route("messages.index") }}', icon: 'message-circle', keywords: 'messages inbox contact support', permission: 'notifications.view' },
            @endif
            
            // Shipping & Logistics
            @if($admin && ($isSuperAdmin || $admin->hasPermission('shipping.view')))
            { id: 15, title: 'Shipping Methods', category: 'Shipping', url: '{{ admin_route("shipping-methods.index") }}', icon: 'truck', keywords: 'shipping methods delivery logistics', permission: 'shipping.view' },
            { id: 16, title: 'Delivery Tracking', category: 'Shipping', url: '{{ admin_route("delivery-tracking.index") }}', icon: 'map-pin', keywords: 'delivery tracking shipment location', permission: 'shipping.view' },
            @endif
            
            // Content
            @if($admin && ($isSuperAdmin || $admin->hasPermission('cms.view')))
            { id: 17, title: 'CMS Pages', category: 'Content', url: '{{ admin_route("cms-pages.index") }}', icon: 'file-text', keywords: 'cms pages content management', permission: 'cms.view' },
            { id: 18, title: 'Blogs', category: 'Content', url: '{{ admin_route("blogs.index") }}', icon: 'book-open', keywords: 'blogs posts articles content', permission: 'cms.view' },
            { id: 19, title: 'Media Library', category: 'Content', url: '{{ admin_route("media-library") }}', icon: 'image', keywords: 'media library images files upload', permission: 'cms.view' },
            @endif
            @if($admin && ($isSuperAdmin || $admin->hasPermission('reviews.view')))
            { id: 20, title: 'Product Reviews', category: 'Content', url: '{{ admin_route("reviews.index") }}', icon: 'star', keywords: 'reviews ratings feedback products', permission: 'reviews.view' },
            @endif
            
            // Reports
            @if($admin && ($isSuperAdmin || $admin->hasPermission('analytics.view')))
            { id: 21, title: 'Analytics Dashboard', category: 'Reports', url: '{{ admin_route("analytics.index") }}', icon: 'bar-chart-3', keywords: 'analytics dashboard stats overview', permission: 'analytics.view' },
            { id: 22, title: 'Sales Reports', category: 'Reports', url: '{{ admin_route("analytics.sales") }}', icon: 'trending-up', keywords: 'sales reports revenue analytics', permission: 'analytics.view' },
            { id: 23, title: 'Customer Insights', category: 'Reports', url: '{{ admin_route("analytics.customers") }}', icon: 'users', keywords: 'customer insights analytics users', permission: 'analytics.view' },
            { id: 24, title: 'Product Reports', category: 'Reports', url: '{{ admin_route("analytics.products") }}', icon: 'package', keywords: 'product reports analytics items', permission: 'analytics.view' },
            { id: 25, title: 'Revenue Reports', category: 'Reports', url: '{{ admin_route("analytics.revenue") }}', icon: 'dollar-sign', keywords: 'revenue reports money income', permission: 'analytics.view' },
            @endif
            
            // Settings
            @if($admin && ($isSuperAdmin || $admin->hasPermission('settings.view')))
            { id: 26, title: 'Store Settings', category: 'Settings', url: '{{ admin_route("settings.index") }}', icon: 'store', keywords: 'settings store configuration preferences', permission: 'settings.view' },
            @endif
            @if($admin && ($isSuperAdmin || $admin->hasPermission('admins.view')))
            { id: 27, title: 'Manage Admins', category: 'Settings', url: '{{ admin_route("users.admins") }}', icon: 'users', keywords: 'admins users manage administrators', permission: 'admins.view' },
            @endif
            @if($admin && ($isSuperAdmin || $admin->hasPermission('admins.edit')))
            { id: 28, title: 'Permissions', category: 'Settings', url: '{{ admin_route("permissions.index") }}', icon: 'shield', keywords: 'permissions roles access control', permission: 'admins.edit' },
            @endif
            @if($admin && ($isSuperAdmin || $admin->hasPermission('payment_gateways.view')))
            { id: 29, title: 'Payment Gateways', category: 'Settings', url: '{{ admin_route("payment-gateways.index") }}', icon: 'credit-card', keywords: 'payment gateways payment methods', permission: 'payment_gateways.view' },
            @endif
            @if($admin && ($isSuperAdmin || $admin->hasPermission('settings.view')))
            { id: 30, title: 'Integrations', category: 'Settings', url: '{{ admin_route("integrations.index") }}', icon: 'plug', keywords: 'integrations api third party', permission: 'settings.view' },
            @endif
            @if($admin && ($isSuperAdmin || $admin->hasPermission('audit.view')))
            { id: 31, title: 'Audit Trail', category: 'Settings', url: '{{ admin_route("audit.index") }}', icon: 'shield-check', keywords: 'audit trail logs history', permission: 'audit.view' },
            @endif
        ],
        
        get filteredItems() {
            // Only show results when there's a query
            if (this.query.length === 0) {
                return [];
            }
            
            const queryLower = this.query.toLowerCase();
            const filtered = this.items.filter(item => {
                const searchableText = `${item.title} ${item.category} ${item.keywords}`.toLowerCase();
                return searchableText.includes(queryLower);
            }).slice(0, 10); // Limit to 10 results
            
            // Reset selection when results change
            if (this.selectedIndex >= filtered.length) {
                this.selectedIndex = Math.max(0, filtered.length - 1);
            }
            
            return filtered;
        },
        
        selectNext() {
            if (this.selectedIndex < this.filteredItems.length - 1) {
                this.selectedIndex++;
                this.scrollToSelected();
            }
        },
        
        selectPrevious() {
            if (this.selectedIndex > 0) {
                this.selectedIndex--;
                this.scrollToSelected();
            }
        },
        
        navigateToSelected() {
            if (this.filteredItems.length > 0 && this.filteredItems[this.selectedIndex]) {
                window.location.href = this.filteredItems[this.selectedIndex].url;
            }
        },
        
        scrollToSelected() {
            this.$nextTick(() => {
                const selectedElement = this.$el.querySelector(`li:nth-child(${this.selectedIndex + 1})`);
                if (selectedElement) {
                    selectedElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        },
        
        init() {
            // Store reference to this component globally
            window.searchCommandInstance = this;
            
            // Watch query changes to reset selection
            this.$watch('query', () => {
                this.selectedIndex = 0;
                // Reinitialize Lucide icons when query changes
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined' && lucide.createIcons) {
                        lucide.createIcons();
                    }
                });
            });
            
            // Detect Mac vs Windows/Linux for keyboard shortcut display
            const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
            if (isMac) {
                document.documentElement.classList.add('mac');
            }
        }
    }
}

// Global keyboard shortcut handler
document.addEventListener('keydown', function(e) {
    // Cmd+K on Mac or Ctrl+K on Windows/Linux
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        if (window.searchCommandInstance && window.searchCommandInstance.$refs.searchInput) {
            window.searchCommandInstance.$refs.searchInput.focus();
            window.searchCommandInstance.open = true;
        }
    }
});
</script>