<!-- Sidebar Start -->
<aside
    :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        sidebarCollapsed ? 'lg:w-23' : 'lg:w-72'
    ]"
    class="absolute left-0 top-0 z-9999 flex h-screen flex-col overflow-y-hidden bg-white dark:bg-boxdark border-r border-stroke dark:border-strokedark duration-300 ease-linear lg:static lg:translate-x-0"
    @click.outside="sidebarOpen = false"
    x-data="{ 
        ordersOpen: false,
        productsOpen: false,
        inventoryOpen: false,
        customersOpen: false,
        contactOpen: false,
        shippingOpen: false,
        contentOpen: false,
        settingsOpen: false,
        reportsOpen: false
    }"
    x-init="
        // Auto-collapse on mobile
        if (window.innerWidth < 1024) {
            $parent.sidebarCollapsed = false;
        }
        
        // Close all menus when sidebar is collapsed
        $watch('$parent.sidebarCollapsed', val => {
            if (val) {
                ordersOpen = false;
                productsOpen = false;
                inventoryOpen = false;
                customersOpen = false;
                contactOpen = false;
                shippingOpen = false;
                contentOpen = false;
                settingsOpen = false;
                reportsOpen = false;
            }
        });
    "
>
    <!-- SIDEBAR HEADER -->
    <div class="flex items-center justify-between gap-2 px-6 py-6 border-b border-stroke dark:border-strokedark">
        <a href="{{ route('admin.dashboard') }}" :class="sidebarCollapsed ? 'justify-center' : ''">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-primary/80 shadow-lg">
                    <img src="{{ asset('admin/images/logo/favicon.png') }}" alt="Logo" class="h-8">
                </div>
                <div x-show="!sidebarCollapsed" x-transition>
                    <h1 class="text-gray-900 dark:text-white text-xl font-bold">David's Wood</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-xs">Admin Panel</p>
                </div>
            </div>
        </a>

        <!-- Close Button (Mobile) -->
        <button
            class="block lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200"
            @click.stop="sidebarOpen = false"
        >
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>
    <!-- SIDEBAR HEADER -->

    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
        <!-- Sidebar Menu -->
        <nav class="mt-5 px-4 py-4 lg:mt-9 lg:px-6">
            <ul class="mb-6 flex flex-col gap-1.5">
                
                <!-- Dashboard (Top-level) -->
                <li>
                                <a
                                    class="group relative flex items-center gap-3 rounded-xl px-4 py-3 font-medium text-gray-700 duration-300 ease-in-out hover:bg-primary/5 hover:text-primary dark:text-bodydark1 dark:hover:bg-graydark/50 dark:hover:text-primary {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary shadow-sm dark:bg-graydark/50 dark:text-primary' : '' }}"
                                    href="{{ route('admin.dashboard') }}"
                                    :title="sidebarCollapsed ? 'Dashboard' : ''"
                                    x-tooltip="sidebarCollapsed ? 'Dashboard' : ''"
                                >
                                    <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
                                    <span x-show="!sidebarCollapsed" x-transition>Dashboard</span>
                    </a>
                </li>

                <!-- Orders Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (ordersOpen = !ordersOpen)"
                        class="group relative flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3 font-medium text-gray-700 duration-300 ease-in-out hover:bg-primary/5 hover:text-primary dark:text-bodydark1 dark:hover:bg-graydark/50 dark:hover:text-primary {{ request()->routeIs('admin.orders.*') ? 'bg-primary/10 text-primary shadow-sm dark:bg-graydark/50 dark:text-primary' : '' }}"
                        :title="sidebarCollapsed ? 'Orders' : ''"
                        x-tooltip="sidebarCollapsed ? 'Orders' : ''"
                    >
                        <div class="flex items-center gap-3">
                            <i data-lucide="shopping-cart" class="w-5 h-5 flex-shrink-0"></i>
                            <span x-show="!sidebarCollapsed" x-transition>Orders</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="ordersOpen ? 'rotate-180' : ''" x-show="!sidebarCollapsed"></i>
                    </button>
                    <ul x-show="ordersOpen && !sidebarCollapsed" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.orders.index') ? 'text-primary dark:text-primary' : '' }}">All Orders</a></li>
                        <li><a href="{{ route('admin.orders.create') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.orders.create') ? 'text-primary dark:text-primary' : '' }}">Create Order</a></li>
                        <li><a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Pending Approval</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Fulfillment</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Returns & Repairs</a></li>
                    </ul>
                </li>

                <!-- Products Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (productsOpen = !productsOpen)"
                        class="group relative flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3 font-medium text-gray-700 duration-300 ease-in-out hover:bg-primary/5 hover:text-primary dark:text-bodydark1 dark:hover:bg-graydark/50 dark:hover:text-primary {{ request()->routeIs('admin.products.*') ? 'bg-primary/10 text-primary shadow-sm dark:bg-graydark/50 dark:text-primary' : '' }}"
                        :title="sidebarCollapsed ? 'Products' : ''"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="package" class="w-5 h-5 flex-shrink-0"></i>
                            <span x-show="!sidebarCollapsed" x-transition>Products</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="productsOpen ? 'rotate-180' : ''" x-show="!sidebarCollapsed"></i>
                    </button>
                    <ul x-show="productsOpen && !sidebarCollapsed" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.products.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.products.index') ? 'text-primary dark:text-primary' : '' }}">All Products</a></li>
                        <li><a href="{{ route('admin.products.create') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.products.create') ? 'text-primary dark:text-primary' : '' }}">Add New Product</a></li>
                        <li><a href="{{ route('admin.images.upload-page') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.images.upload-page') ? 'text-primary dark:text-primary' : '' }}">Image Upload</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Categories</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Materials & Finishes</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Custom Orders</a></li>
                    </ul>
                </li>

                <!-- Inventory Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (inventoryOpen = !inventoryOpen)"
                        class="group relative flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3 font-medium text-gray-700 duration-300 ease-in-out hover:bg-primary/5 hover:text-primary dark:text-bodydark1 dark:hover:bg-graydark/50 dark:hover:text-primary {{ request()->routeIs('admin.inventory.*') ? 'bg-primary/10 text-primary shadow-sm dark:bg-graydark/50 dark:text-primary' : '' }}"
                        :title="sidebarCollapsed ? 'Inventory' : ''"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="warehouse" class="w-5 h-5 flex-shrink-0"></i>
                            <span x-show="!sidebarCollapsed" x-transition>Inventory</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="inventoryOpen ? 'rotate-180' : ''" x-show="!sidebarCollapsed"></i>
                    </button>
                    <ul x-show="inventoryOpen && !sidebarCollapsed" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.inventory.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.inventory.index') ? 'text-primary dark:text-primary' : '' }}">Stock Levels</a></li>
                        <li><a href="{{ route('admin.inventory.low-stock') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.inventory.low-stock') ? 'text-primary dark:text-primary' : '' }}">Low Stock Alerts</a></li>
                        <li><a href="{{ route('admin.inventory.movements') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.inventory.movements') ? 'text-primary dark:text-primary' : '' }}">Inventory History</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Wood Material Inventory</a></li>
                    </ul>
                </li>

                <!-- Customers Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (customersOpen = !customersOpen)"
                        class="group relative flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3 font-medium text-gray-700 duration-300 ease-in-out hover:bg-primary/5 hover:text-primary dark:text-bodydark1 dark:hover:bg-graydark/50 dark:hover:text-primary {{ request()->routeIs('admin.users.*') ? 'bg-primary/10 text-primary shadow-sm dark:bg-graydark/50 dark:text-primary' : '' }}"
                        :title="sidebarCollapsed ? 'Customers' : ''"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                            <span x-show="!sidebarCollapsed" x-transition>Customers</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="customersOpen ? 'rotate-180' : ''" x-show="!sidebarCollapsed"></i>
                    </button>
                    <ul x-show="customersOpen && !sidebarCollapsed" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.users.index') ? 'text-primary dark:text-primary' : '' }}">All Customers</a></li>
                        <li><a href="{{ route('admin.users.create') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.users.create') ? 'text-primary dark:text-primary' : '' }}">Add Customer</a></li>
                        <li><a href="{{ route('admin.users.admins') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.users.admins') ? 'text-primary dark:text-primary' : '' }}">Admin Users</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Customer Groups</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Wishlist Tracking</a></li>
                    </ul>
                </li>

                <!-- Contact Messages Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (contactOpen = !contactOpen)"
                        class="group relative flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3 font-medium text-gray-700 duration-300 ease-in-out hover:bg-primary/5 hover:text-primary dark:text-bodydark1 dark:hover:bg-graydark/50 dark:hover:text-primary {{ request()->routeIs('admin.contact-messages.*') ? 'bg-primary/10 text-primary shadow-sm dark:bg-graydark/50 dark:text-primary' : '' }}"
                        :title="sidebarCollapsed ? 'Contact Messages' : ''"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="mail" class="w-5 h-5 flex-shrink-0"></i>
                            <span x-show="!sidebarCollapsed" x-transition>Contact Messages</span>
                            @php
                                $newMessagesCount = \App\Models\ContactMessage::where('status', 'new')->count();
                            @endphp
                            @if($newMessagesCount > 0)
                                <span class="ml-auto inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full" x-show="!sidebarCollapsed">{{ $newMessagesCount }}</span>
                            @endif
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="contactOpen ? 'rotate-180' : ''" x-show="!sidebarCollapsed"></i>
                    </button>
                    <ul x-show="contactOpen && !sidebarCollapsed" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.contact-messages.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.contact-messages.index') && !request('status') ? 'text-primary dark:text-primary' : '' }}">All Messages</a></li>
                        <li><a href="{{ route('admin.contact-messages.index', ['status' => 'new']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request('status') == 'new' ? 'text-primary dark:text-primary' : '' }}">New Messages</a></li>
                        <li><a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request('status') == 'read' ? 'text-primary dark:text-primary' : '' }}">Read</a></li>
                        <li><a href="{{ route('admin.contact-messages.index', ['status' => 'responded']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request('status') == 'responded' ? 'text-primary dark:text-primary' : '' }}">Responded</a></li>
                    </ul>
                </li>

                <!-- Shipping & Logistics Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (shippingOpen = !shippingOpen)"
                        class="group relative flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3 font-medium text-gray-700 duration-300 ease-in-out hover:bg-primary/5 hover:text-primary dark:text-bodydark1 dark:hover:bg-graydark/50 dark:hover:text-primary {{ request()->routeIs('admin.shipping-methods.*') ? 'bg-primary/10 text-primary shadow-sm dark:bg-graydark/50 dark:text-primary' : '' }}"
                        :title="sidebarCollapsed ? 'Shipping & Logistics' : ''"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="truck" class="w-5 h-5 flex-shrink-0"></i>
                            <span x-show="!sidebarCollapsed" x-transition>Shipping & Logistics</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="shippingOpen ? 'rotate-180' : ''" x-show="!sidebarCollapsed"></i>
                    </button>
                    <ul x-show="shippingOpen && !sidebarCollapsed" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.shipping-methods.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.shipping-methods.*') ? 'text-primary dark:text-primary' : '' }}">Shipping Methods</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Shipping Zones</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Carriers & Rates</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Delivery Tracking</a></li>
                    </ul>
                </li>


                <!-- Content Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (contentOpen = !contentOpen)"
                        class="group relative flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3 font-medium text-gray-700 duration-300 ease-in-out hover:bg-primary/5 hover:text-primary dark:text-bodydark1 dark:hover:bg-graydark/50 dark:hover:text-primary {{ request()->routeIs('admin.cms-pages.*') || request()->routeIs('admin.reviews.*') ? 'bg-primary/10 text-primary shadow-sm dark:bg-graydark/50 dark:text-primary' : '' }}"
                        :title="sidebarCollapsed ? 'Content' : ''"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="file-text" class="w-5 h-5 flex-shrink-0"></i>
                            <span x-show="!sidebarCollapsed" x-transition>Content</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="contentOpen ? 'rotate-180' : ''" x-show="!sidebarCollapsed"></i>
                    </button>
                    <ul x-show="contentOpen && !sidebarCollapsed" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.cms-pages.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.cms-pages*') ? 'text-primary dark:text-primary' : '' }}">CMS Pages</a></li>
                        <li><a href="{{ route('admin.reviews.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.reviews*') ? 'text-primary dark:text-primary' : '' }}">Product Reviews</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Blogs</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Media Library</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Testimonials</a></li>
                    </ul>
                </li>

                <!-- Settings Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (settingsOpen = !settingsOpen)"
                        class="group relative flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3 font-medium text-gray-700 duration-300 ease-in-out hover:bg-primary/5 hover:text-primary dark:text-bodydark1 dark:hover:bg-graydark/50 dark:hover:text-primary {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.shipping-methods.*') || request()->routeIs('admin.payment-gateways.*') || request()->routeIs('admin.users.admins') || request()->routeIs('admin.permissions.*') ? 'bg-primary/10 text-primary shadow-sm dark:bg-graydark/50 dark:text-primary' : '' }}"
                        :title="sidebarCollapsed ? 'Settings' : ''"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="settings" class="w-5 h-5 flex-shrink-0"></i>
                            <span x-show="!sidebarCollapsed" x-transition>Settings</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="settingsOpen ? 'rotate-180' : ''" x-show="!sidebarCollapsed"></i>
                    </button>
                    <ul x-show="settingsOpen && !sidebarCollapsed" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.settings.index') ? 'text-primary dark:text-primary' : '' }}">Store Settings</a></li>
                        <li><a href="{{ route('admin.shipping-methods.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.shipping-methods*') ? 'text-primary dark:text-primary' : '' }}">Shipping Methods</a></li>
                        <li><a href="{{ route('admin.payment-gateways.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.payment-gateways*') ? 'text-primary dark:text-primary' : '' }}">Payment Gateways</a></li>
                        <li><a href="{{ route('admin.users.admins') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.users.admins') ? 'text-primary dark:text-primary' : '' }}">User Roles</a></li>
                        <li><a href="{{ route('admin.permissions.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.permissions*') ? 'text-primary dark:text-primary' : '' }}">Permissions</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Sustainability Settings</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">Integrations</a></li>
                    </ul>
                </li>

                <!-- Reports Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (reportsOpen = !reportsOpen)"
                        class="group relative flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3 font-medium text-gray-700 duration-300 ease-in-out hover:bg-primary/5 hover:text-primary dark:text-bodydark1 dark:hover:bg-graydark/50 dark:hover:text-primary {{ request()->routeIs('admin.analytics*') ? 'bg-primary/10 text-primary shadow-sm dark:bg-graydark/50 dark:text-primary' : '' }}"
                        :title="sidebarCollapsed ? 'Reports' : ''"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 flex-shrink-0"></i>
                            <span x-show="!sidebarCollapsed" x-transition>Reports</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="reportsOpen ? 'rotate-180' : ''" x-show="!sidebarCollapsed"></i>
                    </button>
                    <ul x-show="reportsOpen && !sidebarCollapsed" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.analytics.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.analytics.index') ? 'text-primary dark:text-primary' : '' }}">Analytics Dashboard</a></li>
                        <li><a href="{{ route('admin.analytics.sales') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.analytics.sales') ? 'text-primary dark:text-primary' : '' }}">Sales Reports</a></li>
                        <li><a href="{{ route('admin.analytics.customers') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.analytics.customers') ? 'text-primary dark:text-primary' : '' }}">Customer Insights</a></li>
                        <li><a href="{{ route('admin.analytics.products') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.analytics.products') ? 'text-primary dark:text-primary' : '' }}">Product Reports</a></li>
                        <li><a href="{{ route('admin.analytics.revenue') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.analytics.revenue') ? 'text-primary dark:text-primary' : '' }}">Revenue Reports</a></li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- Sidebar Menu -->
    </div>
</aside>
<!-- Sidebar End -->