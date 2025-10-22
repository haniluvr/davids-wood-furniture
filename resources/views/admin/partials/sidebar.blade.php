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
        messagesOpen: false,
        shippingOpen: false,
        contentOpen: false,
        reportsOpen: false,
        settingsPopoverOpen: false
    }"
    x-init="
        // Function to save accordion states to session storage
        window.saveAccordionStates = function() {
            const states = {
                ordersOpen: ordersOpen,
                productsOpen: productsOpen,
                inventoryOpen: inventoryOpen,
                customersOpen: customersOpen,
                contactOpen: contactOpen,
                messagesOpen: messagesOpen,
                shippingOpen: shippingOpen,
                contentOpen: contentOpen,
                reportsOpen: reportsOpen
            };
            sessionStorage.setItem('sidebarAccordionStates', JSON.stringify(states));
            console.log('Sidebar states saved:', states);
        }
        
        // Function to close all accordions except the one being opened
        window.closeAllAccordions = function(except = null) {
            if (except !== 'orders') ordersOpen = false;
            if (except !== 'products') productsOpen = false;
            if (except !== 'inventory') inventoryOpen = false;
            if (except !== 'customers') customersOpen = false;
            if (except !== 'contact') contactOpen = false;
            if (except !== 'messages') messagesOpen = false;
            if (except !== 'shipping') shippingOpen = false;
            if (except !== 'content') contentOpen = false;
            if (except !== 'reports') reportsOpen = false;
        }
        
        // Restore accordion states from session storage
        const savedStates = JSON.parse(sessionStorage.getItem('sidebarAccordionStates') || '{}');
        console.log('Restoring sidebar states:', savedStates);
        ordersOpen = savedStates.ordersOpen || false;
        productsOpen = savedStates.productsOpen || false;
        inventoryOpen = savedStates.inventoryOpen || false;
        customersOpen = savedStates.customersOpen || false;
        contactOpen = savedStates.contactOpen || false;
        messagesOpen = savedStates.messagesOpen || false;
        shippingOpen = savedStates.shippingOpen || false;
        contentOpen = savedStates.contentOpen || false;
        reportsOpen = savedStates.reportsOpen || false;
        
        // Auto-collapse on mobile
        if (window.innerWidth < 1024) {
            if (typeof $parent !== 'undefined' && $parent.sidebarCollapsed !== undefined) {
                $parent.sidebarCollapsed = false;
            }
        }
        
        // Save accordion states to session storage when they change
        $watch('ordersOpen', val => window.saveAccordionStates());
        $watch('productsOpen', val => window.saveAccordionStates());
        $watch('inventoryOpen', val => window.saveAccordionStates());
        $watch('customersOpen', val => window.saveAccordionStates());
        $watch('contactOpen', val => window.saveAccordionStates());
        $watch('messagesOpen', val => window.saveAccordionStates());
        $watch('shippingOpen', val => window.saveAccordionStates());
        $watch('contentOpen', val => window.saveAccordionStates());
        $watch('reportsOpen', val => window.saveAccordionStates());
        
        // Close all menus when sidebar is collapsed (with safety check)
        if (typeof $parent !== 'undefined') {
            $watch('$parent.sidebarCollapsed', val => {
                if (val) {
                    ordersOpen = false;
                    productsOpen = false;
                    inventoryOpen = false;
                    customersOpen = false;
                    contactOpen = false;
                    messagesOpen = false;
                    shippingOpen = false;
                    contentOpen = false;
                    reportsOpen = false;
                    settingsPopoverOpen = false;
                }
            });
        }
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
                        @click="!sidebarCollapsed && (ordersOpen = !ordersOpen, window.closeAllAccordions('orders'))"
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
                        <li><a href="{{ route('admin.orders.pending-approval') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.orders.pending-approval') ? 'text-primary dark:text-primary' : '' }}">Pending Approval</a></li>
                        <li><a href="{{ route('admin.orders.fulfillment') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.orders.fulfillment*') ? 'text-primary dark:text-primary' : '' }}">Fulfillment</a></li>
                        <li><a href="{{ route('admin.orders.returns-repairs.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.orders.returns-repairs*') ? 'text-primary dark:text-primary' : '' }}">Returns & Repairs</a></li>
                    </ul>
                </li>

                <!-- Products Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (productsOpen = !productsOpen, window.closeAllAccordions('products'))"
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
                    </ul>
                </li>

                <!-- Inventory Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (inventoryOpen = !inventoryOpen, window.closeAllAccordions('inventory'))"
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
                    </ul>
                </li>

                <!-- Customers Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (customersOpen = !customersOpen, window.closeAllAccordions('customers'))"
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
                    </ul>
                </li>

                <!-- Messages Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (messagesOpen = !messagesOpen, window.closeAllAccordions('messages'))"
                        class="group relative flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3 font-medium text-gray-700 duration-300 ease-in-out hover:bg-primary/5 hover:text-primary dark:text-bodydark1 dark:hover:bg-graydark/50 dark:hover:text-primary {{ request()->routeIs('admin.messages.*') ? 'bg-primary/10 text-primary shadow-sm dark:bg-graydark/50 dark:text-primary' : '' }}"
                        :title="sidebarCollapsed ? 'Messages' : ''"
                    >
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="message-square" class="w-5 h-5 flex-shrink-0"></i>
                            <span x-show="!sidebarCollapsed" x-transition>Messages</span>
                            @php
                                $newMessagesCount = \App\Models\ContactMessage::where('status', 'new')->count();
                            @endphp
                            @if($newMessagesCount > 0)
                                <span class="ml-auto inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full" x-show="!sidebarCollapsed">{{ $newMessagesCount }}</span>
                            @endif
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="messagesOpen ? 'rotate-180' : ''" x-show="!sidebarCollapsed"></i>
                    </button>
                    <ul x-show="messagesOpen && !sidebarCollapsed" x-transition class="mt-2 ml-6 space-y-1">
                        <li><a href="{{ route('admin.messages.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request()->routeIs('admin.messages.index') && !request('status') ? 'text-primary dark:text-primary' : '' }}">All Messages</a></li>
                        <li><a href="{{ route('admin.messages.index', ['status' => 'new']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request('status') == 'new' ? 'text-primary dark:text-primary' : '' }}">New Messages</a></li>
                        <li><a href="{{ route('admin.messages.index', ['status' => 'read']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request('status') == 'read' ? 'text-primary dark:text-primary' : '' }}">Read</a></li>
                        <li><a href="{{ route('admin.messages.index', ['status' => 'responded']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary {{ request('status') == 'responded' ? 'text-primary dark:text-primary' : '' }}">Responded</a></li>
                    </ul>
                </li>

                <!-- Shipping & Logistics Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (shippingOpen = !shippingOpen, window.closeAllAccordions('shipping'))"
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
                        @click="!sidebarCollapsed && (contentOpen = !contentOpen, window.closeAllAccordions('content'))"
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


                <!-- Reports Accordion -->
                <li>
                    <button
                        @click="!sidebarCollapsed && (reportsOpen = !reportsOpen, window.closeAllAccordions('reports'))"
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
    
    <!-- Fixed Settings Button at Bottom -->
    <div class="mt-auto p-4 border-t border-stroke dark:border-strokedark">
        <div class="relative" x-data="{ settingsPopoverOpen: false }">
            <!-- Settings Button -->
            <button
                @click="settingsPopoverOpen = !settingsPopoverOpen"
                class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors duration-200"
                :title="sidebarCollapsed ? 'Settings' : ''"
            >
                <span x-show="!sidebarCollapsed" x-transition class="text-sm">Settings</span>
                <i data-lucide="settings" class="w-5 h-5"></i>
            </button>
            
            <!-- Settings Popover -->
            <div
                x-show="settingsPopoverOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.outside="settingsPopoverOpen = false"
                class="absolute bottom-full right-0 mb-2 w-64 rounded-lg border border-stroke bg-white shadow-lg dark:border-strokedark dark:bg-boxdark z-50"
                :class="sidebarCollapsed ? 'right-0' : 'right-0'"
            >
                <div class="p-2">
                    <div class="space-y-1">
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('admin.settings.index') ? 'bg-primary/10 text-primary' : '' }}">
                            <i data-lucide="store" class="w-4 h-4"></i>
                            Store Settings
                        </a>
                        <a href="{{ route('admin.shipping-methods.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('admin.shipping-methods*') ? 'bg-primary/10 text-primary' : '' }}">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                            Shipping Methods
                        </a>
                        <a href="{{ route('admin.payment-gateways.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('admin.payment-gateways*') ? 'bg-primary/10 text-primary' : '' }}">
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                            Payment Gateways
                        </a>
                        <a href="{{ route('admin.users.admins') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('admin.users.admins') ? 'bg-primary/10 text-primary' : '' }}">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            Manage Admins
                        </a>
                        <a href="{{ route('admin.permissions.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('admin.permissions*') ? 'bg-primary/10 text-primary' : '' }}">
                            <i data-lucide="shield" class="w-4 h-4"></i>
                            Permissions
                        </a>
                        <hr class="my-2 border-stroke dark:border-strokedark">
                        <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                            <i data-lucide="leaf" class="w-4 h-4"></i>
                            Sustainability Settings
                        </a>
                        <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                            <i data-lucide="plug" class="w-4 h-4"></i>
                            Integrations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
<!-- Sidebar End -->