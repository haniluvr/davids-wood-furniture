<!-- Sidebar Start -->
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="absolute left-0 top-0 z-9999 flex h-screen w-72.5 flex-col overflow-y-hidden bg-white border-r border-stroke duration-300 ease-linear dark:bg-boxdark dark:border-strokedark lg:static lg:translate-x-0"
    @click.outside="sidebarOpen = false"
    x-data="{ 
        ordersOpen: false,
        productsOpen: false,
        inventoryOpen: false,
        customersOpen: false,
        contactOpen: false,
        shippingOpen: false,
        salesOpen: false,
        contentOpen: false,
        settingsOpen: false,
        reportsOpen: false
    }"
>
    <!-- SIDEBAR HEADER -->
    <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5 border-b border-stroke dark:border-strokedark">
        <a href="{{ route('admin.dashboard') }}">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary">
                    <span class="text-white font-bold text-lg">DW</span>
                </div>
                <div>
                    <h1 class="text-black dark:text-white text-xl font-bold">David's Wood</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-xs">Admin Panel</p>
                </div>
            </div>
        </a>

        <button
            class="block lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
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
                        class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 dark:text-bodydark1 dark:hover:bg-graydark {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-primary dark:bg-graydark dark:text-white' : '' }}"
                        href="{{ route('admin.dashboard') }}"
                    >
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        Dashboard
                    </a>
                </li>

                <!-- Orders Accordion -->
                <li>
                    <button
                        @click="ordersOpen = !ordersOpen"
                        class="group relative flex w-full items-center justify-between gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 dark:text-bodydark1 dark:hover:bg-graydark {{ request()->routeIs('admin.orders.*') ? 'bg-gray-100 text-primary dark:bg-graydark dark:text-white' : '' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            Orders
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="ordersOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <ul x-show="ordersOpen" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request()->routeIs('admin.orders.index') ? 'text-primary dark:text-white' : '' }}">All Orders</a></li>
                        <li><a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Pending Approval</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Fulfillment</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Returns & Repairs</a></li>
                    </ul>
                </li>

                <!-- Products Accordion -->
                <li>
                    <button
                        @click="productsOpen = !productsOpen"
                        class="group relative flex w-full items-center justify-between gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 dark:text-bodydark1 dark:hover:bg-graydark {{ request()->routeIs('admin.products.*') ? 'bg-gray-100 text-primary dark:bg-graydark dark:text-white' : '' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="package" class="w-5 h-5"></i>
                            Products
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="productsOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <ul x-show="productsOpen" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.products.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request()->routeIs('admin.products.index') ? 'text-primary dark:text-white' : '' }}">All Products</a></li>
                        <li><a href="{{ route('admin.products.create') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request()->routeIs('admin.products.create') ? 'text-primary dark:text-white' : '' }}">Add New Product</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Categories</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Materials & Finishes</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Custom Orders</a></li>
                    </ul>
                </li>

                <!-- Inventory Accordion -->
                <li>
                    <button
                        @click="inventoryOpen = !inventoryOpen"
                        class="group relative flex w-full items-center justify-between gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 dark:text-bodydark1 dark:hover:bg-graydark {{ request()->routeIs('admin.inventory.*') ? 'bg-gray-100 text-primary dark:bg-graydark dark:text-white' : '' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="warehouse" class="w-5 h-5"></i>
                            Inventory
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="inventoryOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <ul x-show="inventoryOpen" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.inventory.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request()->routeIs('admin.inventory.index') ? 'text-primary dark:text-white' : '' }}">Stock Levels</a></li>
                        <li><a href="{{ route('admin.inventory.low-stock') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request()->routeIs('admin.inventory.low-stock') ? 'text-primary dark:text-white' : '' }}">Low Stock Alerts</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Wood Material Inventory</a></li>
                        <li><a href="{{ route('admin.inventory.movements') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request()->routeIs('admin.inventory.movements') ? 'text-primary dark:text-white' : '' }}">Inventory History</a></li>
                    </ul>
                </li>

                <!-- Customers Accordion -->
                <li>
                    <button
                        @click="customersOpen = !customersOpen"
                        class="group relative flex w-full items-center justify-between gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 dark:text-bodydark1 dark:hover:bg-graydark {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-primary dark:bg-graydark dark:text-white' : '' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="users" class="w-5 h-5"></i>
                            Customers
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="customersOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <ul x-show="customersOpen" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request()->routeIs('admin.users.index') ? 'text-primary dark:text-white' : '' }}">All Customers</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Customer Groups</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Wishlist Tracking</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Service Requests</a></li>
                    </ul>
                </li>

                <!-- Contact Messages Accordion -->
                <li>
                    <button
                        @click="contactOpen = !contactOpen"
                        class="group relative flex w-full items-center justify-between gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 dark:text-bodydark1 dark:hover:bg-graydark {{ request()->routeIs('admin.contact-messages.*') ? 'bg-gray-100 text-primary dark:bg-graydark dark:text-white' : '' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                            Contact Messages
                            @php
                                $newMessagesCount = \App\Models\ContactMessage::where('status', 'new')->count();
                            @endphp
                            @if($newMessagesCount > 0)
                                <span class="ml-auto inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $newMessagesCount }}</span>
                            @endif
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="contactOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <ul x-show="contactOpen" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.contact-messages.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request()->routeIs('admin.contact-messages.index') && !request('status') ? 'text-primary dark:text-white' : '' }}">All Messages</a></li>
                        <li><a href="{{ route('admin.contact-messages.index', ['status' => 'new']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request('status') == 'new' ? 'text-primary dark:text-white' : '' }}">New Messages</a></li>
                        <li><a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request('status') == 'read' ? 'text-primary dark:text-white' : '' }}">Read</a></li>
                        <li><a href="{{ route('admin.contact-messages.index', ['status' => 'responded']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request('status') == 'responded' ? 'text-primary dark:text-white' : '' }}">Responded</a></li>
                    </ul>
                </li>

                <!-- Shipping & Logistics Accordion -->
                <li>
                    <button
                        @click="shippingOpen = !shippingOpen"
                        class="group relative flex w-full items-center justify-between gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 dark:text-bodydark1 dark:hover:bg-graydark"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="truck" class="w-5 h-5"></i>
                            Shipping & Logistics
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="shippingOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <ul x-show="shippingOpen" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Shipping Zones</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Carriers & Rates</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Delivery Tracking</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">White-Glove Service</a></li>
                    </ul>
                </li>

                <!-- Sales & Promotions Accordion -->
                <li>
                    <button
                        @click="salesOpen = !salesOpen"
                        class="group relative flex w-full items-center justify-between gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 dark:text-bodydark1 dark:hover:bg-graydark"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="percent" class="w-5 h-5"></i>
                            Sales & Promotions
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="salesOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <ul x-show="salesOpen" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Discounts & Coupons</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Seasonal Campaigns</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">B2B Pricing</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Gift Cards</a></li>
                    </ul>
                </li>

                <!-- Content Accordion -->
                <li>
                    <button
                        @click="contentOpen = !contentOpen"
                        class="group relative flex w-full items-center justify-between gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 dark:text-bodydark1 dark:hover:bg-graydark"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                            Content
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="contentOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <ul x-show="contentOpen" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Pages</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Blogs</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Media Library</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Testimonials</a></li>
                    </ul>
                </li>

                <!-- Settings Accordion -->
                <li>
                    <button
                        @click="settingsOpen = !settingsOpen"
                        class="group relative flex w-full items-center justify-between gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 dark:text-bodydark1 dark:hover:bg-graydark {{ request()->routeIs('admin.settings.*') ? 'bg-gray-100 text-primary dark:bg-graydark dark:text-white' : '' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="settings" class="w-5 h-5"></i>
                            Settings
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="settingsOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <ul x-show="settingsOpen" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request()->routeIs('admin.settings.index') ? 'text-primary dark:text-white' : '' }}">Store Settings</a></li>
                        <li><a href="{{ route('admin.users.admins') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request()->routeIs('admin.users.admins') ? 'text-primary dark:text-white' : '' }}">User Roles</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Sustainability Settings</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Integrations</a></li>
                    </ul>
                </li>

                <!-- Reports Accordion -->
                <li>
                    <button
                        @click="reportsOpen = !reportsOpen"
                        class="group relative flex w-full items-center justify-between gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 dark:text-bodydark1 dark:hover:bg-graydark {{ request()->routeIs('admin.analytics') ? 'bg-gray-100 text-primary dark:bg-graydark dark:text-white' : '' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                            Reports
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="reportsOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <ul x-show="reportsOpen" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.analytics') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white {{ request()->routeIs('admin.analytics') ? 'text-primary dark:text-white' : '' }}">Sales Reports</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Inventory Reports</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Customer Insights</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-white">Custom Reports</a></li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- Sidebar Menu -->
    </div>
</aside>
<!-- Sidebar End -->