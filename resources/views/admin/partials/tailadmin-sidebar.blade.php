<aside
    :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"
>
    <!-- SIDEBAR HEADER -->
    <div
        :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 pt-8 sidebar-header pb-7"
    >
        <a href="{{ admin_route('dashboard') }}">
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
                <img class="dark:hidden h-8" src="{{ asset('admin/images/logo/logo.svg') }}" alt="NeoCommerce" />
                <img class="hidden dark:block h-8" src="{{ asset('admin/images/logo/logo-dark.svg') }}" alt="NeoCommerce" />
            </span>
            <img
                class="logo-icon h-8"
                :class="sidebarToggle ? 'lg:block' : 'hidden'"
                src="{{ asset('admin/images/logo/logo-icon.svg') }}"
                alt="NeoCommerce"
            />
        </a>
    </div>

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <!-- Sidebar Menu -->
        <nav x-data="{selected: $persist('{{ request()->route()->getName() ?? 'Dashboard' }}'), openAccordion: null}" 
             @click="if ($event.target.closest('[data-accordion]')) { openAccordion = openAccordion === $event.target.closest('[data-accordion]').dataset.accordion ? null : $event.target.closest('[data-accordion]').dataset.accordion; selected = openAccordion; }">
            <!-- Menu Group -->
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">MENU</span>
                    <svg
                        :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="mx-auto fill-current menu-group-icon"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill=""
                        />
                    </svg>
                </h3>

                <ul class="flex flex-col gap-4 mb-6">
                    <!-- Menu Item Dashboard -->
                    <li>
                        <a
                            href="{{ admin_route('dashboard') }}"
                            data-accordion="Dashboard"
                            class="menu-item group"
                            :class=" (selected === 'Dashboard') || (page === 'ecommerce' || page === 'analytics' || page === 'marketing' || page === 'crm' || page === 'stocks') ? 'menu-item-active' : 'menu-item-inactive'"
                        >
                            <svg
                                :class="(selected === 'Dashboard') || (page === 'ecommerce' || page === 'analytics' || page === 'marketing' || page === 'crm' || page === 'stocks') ? 'menu-item-icon-active'  :'menu-item-icon-inactive'"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z"
                                    fill=""
                                />
                            </svg>

                            <span
                                class="menu-item-text"
                                :class="sidebarToggle ? 'lg:hidden' : ''"
                            >
                                Dashboard
                            </span>

                            <svg
                                class="menu-item-arrow"
                                :class="[(openAccordion === 'Dashboard') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : '' ]"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                                    stroke=""
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div
                            class="overflow-hidden transform translate"
                            :class="(openAccordion === 'Dashboard') ? 'block' :'hidden'"
                        >
                            <ul
                                :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9"
                            >
                                <li>
                                    <a
                                        href="{{ admin_route('dashboard') }}"
                                        class="menu-dropdown-item group"
                                        :class="page === 'ecommerce' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        eCommerce
                        </a>
                    </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Dashboard -->

                    <!-- Menu Item Products -->
                    <li>
                        <a
                            href="{{ admin_route('products.index') }}"
                            data-accordion="Products"
                            class="menu-item group"
                            :class=" (selected === 'Products') || (page === 'products' || page === 'productCategories' || page === 'productInventory') ? 'menu-item-active' : 'menu-item-inactive'"
                        >
                            <svg
                                :class="(selected === 'Products') || (page === 'products' || page === 'productCategories' || page === 'productInventory') ? 'menu-item-icon-active'  :'menu-item-icon-inactive'"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M15.7499 2.9812H14.2874V2.36245C14.2874 2.02495 14.0062 1.71558 13.6405 1.71558C13.2749 1.71558 12.9937 1.99683 12.9937 2.36245V2.9812H4.97803V2.36245C4.97803 2.02495 4.69678 1.71558 4.33115 1.71558C3.96553 1.71558 3.68428 1.99683 3.68428 2.36245V2.9812H2.2499C1.29365 2.9812 0.478027 3.7968 0.478027 4.75305V14.5968C0.478027 15.553 1.29365 16.3687 2.2499 16.3687H15.7499C16.7062 16.3687 17.5218 15.553 17.5218 14.5968V4.72495C17.5218 3.7968 16.7062 2.9812 15.7499 2.9812ZM1.77178 8.21245H4.1624V10.9968H1.77178V8.21245ZM5.42803 8.21245H8.38115V10.9968H5.42803V8.21245ZM8.38115 12.2906V15.0749H5.42803V12.2906H8.38115ZM9.64678 8.21245H12.5999V10.9968H9.64678V8.21245ZM9.64678 12.2906H12.5999V15.0749H9.64678V12.2906ZM13.8624 8.21245H16.2531V10.9968H13.8624V8.21245ZM2.2499 4.24683H3.7124V4.83745C3.7124 5.17495 3.99365 5.48433 4.35928 5.48433C4.7249 5.48433 5.00615 5.20308 5.00615 4.83745V4.24683H13.0218V4.83745C13.0218 5.17495 13.3031 5.48433 13.6687 5.48433C14.0343 5.48433 14.3156 5.20308 14.3156 4.83745V4.24683H15.7781C16.0593 4.24683 16.2843 4.47183 16.2843 4.75308V6.94683H1.77178V4.75308C1.77178 4.47183 1.96865 4.24683 2.2499 4.24683Z"
                                    fill=""
                                />
                            </svg>

                            <span
                                class="menu-item-text"
                                :class="sidebarToggle ? 'lg:hidden' : ''"
                            >
                                Products
                            </span>

                            <svg
                                class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(openAccordion === 'Products') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : '' ]"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                                    stroke=""
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div
                            class="overflow-hidden transform translate"
                            :class="(openAccordion === 'Products') ? 'block' :'hidden'"
                        >
                            <ul
                                :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9"
                            >
                                <li>
                                    <a
                                        href="{{ admin_route('products.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="page === 'products' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        All Products
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="menu-dropdown-item group"
                                        :class="page === 'productCategories' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        Categories
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="menu-dropdown-item group"
                                        :class="page === 'productInventory' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        Inventory
                        </a>
                    </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Products -->

                    <!-- Menu Item Customers -->
                    <li>
                        <a
                            href="{{ admin_route('users.index') }}"
                            data-accordion="Customers"
                            class="menu-item group"
                            :class=" (selected === 'Customers') || (page === 'customers' || page === 'customerGroups') ? 'menu-item-active' : 'menu-item-inactive'"
                        >
                            <svg
                                :class="(selected === 'Customers') || (page === 'customers' || page === 'customerGroups') ? 'menu-item-icon-active'  :'menu-item-icon-inactive'"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z"
                                    fill=""
                                />
                            </svg>

                            <span
                                class="menu-item-text"
                                :class="sidebarToggle ? 'lg:hidden' : ''"
                            >
                                Customers
                            </span>

                            <svg
                                class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(openAccordion === 'Customers') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : '' ]"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                                    stroke=""
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div
                            class="overflow-hidden transform translate"
                            :class="(openAccordion === 'Customers') ? 'block' :'hidden'"
                        >
                            <ul
                                :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9"
                            >
                                <li>
                                    <a
                                        href="{{ admin_route('users.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="page === 'customers' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        All Customers
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="menu-dropdown-item group"
                                        :class="page === 'customerGroups' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        Customer Groups
                        </a>
                    </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Customers -->

                    <!-- Menu Item Orders -->
                    <li>
                        <a
                            href="{{ admin_route('orders.index') }}"
                            data-accordion="Orders"
                            class="menu-item group"
                            :class=" (selected === 'Orders') || (page === 'orders' || page === 'orderStatus') ? 'menu-item-active' : 'menu-item-inactive'"
                        >
                            <svg
                                :class="(selected === 'Orders') || (page === 'orders' || page === 'orderStatus') ? 'menu-item-icon-active'  :'menu-item-icon-inactive'"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M16.8754 11.6719C16.5379 11.6719 16.2285 11.9531 16.2285 12.3187V14.8219C16.2285 15.075 16.0316 15.2719 15.7785 15.2719H2.22227C1.96914 15.2719 1.77227 15.075 1.77227 14.8219V12.3187C1.77227 11.9812 1.49102 11.6719 1.12539 11.6719C0.759766 11.6719 0.478516 11.9531 0.478516 12.3187V14.8219C0.478516 15.7781 1.23789 16.5375 2.19414 16.5375H15.7785C16.7348 16.5375 17.4941 15.7781 17.4941 14.8219V12.3187C17.5223 11.9531 17.2129 11.6719 16.8754 11.6719Z"
                                    fill=""
                                />
                                <path
                                    d="M8.55074 12.3469C8.66324 12.4594 8.83199 12.5156 9.00074 12.5156C9.16949 12.5156 9.33824 12.4594 9.45074 12.3469L13.4726 8.43752C13.7257 8.1844 13.7257 7.79065 13.5007 7.53752C13.2476 7.2844 12.8539 7.2844 12.6007 7.5094L9.64762 10.4063V2.1094C9.64762 1.7719 9.36637 1.46252 9.00074 1.46252C8.66324 1.46252 8.35387 1.74377 8.35387 2.1094V10.4063L5.40074 7.53752C5.14762 7.2844 4.75387 7.31252 4.50074 7.53752C4.24762 7.79065 4.27574 8.1844 4.50074 8.43752L8.55074 12.3469Z"
                                    fill=""
                                />
                            </svg>

                            <span
                                class="menu-item-text"
                                :class="sidebarToggle ? 'lg:hidden' : ''"
                            >
                                Orders
                            </span>

                            <svg
                                class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(openAccordion === 'Orders') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : '' ]"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                                    stroke=""
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div
                            class="overflow-hidden transform translate"
                            :class="(openAccordion === 'Orders') ? 'block' :'hidden'"
                        >
                            <ul
                                :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9"
                            >
                                <li>
                                    <a
                                        href="{{ admin_route('orders.index') }}"
                                        class="menu-dropdown-item group"
                                        :class="page === 'orders' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        All Orders
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="menu-dropdown-item group"
                                        :class="page === 'orderStatus' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        Order Status
                        </a>
                    </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Orders -->

                    <!-- Menu Item Analytics -->
                    <li>
                        <a
                            href="{{ admin_route('analytics') }}"
                            data-accordion="Analytics"
                            class="menu-item group"
                            :class=" (selected === 'Analytics') || (page === 'analytics' || page === 'reports') ? 'menu-item-active' : 'menu-item-inactive'"
                        >
                            <svg
                                :class="(selected === 'Analytics') || (page === 'analytics' || page === 'reports') ? 'menu-item-icon-active'  :'menu-item-icon-inactive'"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M12 2C11.5858 2 11.25 2.33579 11.25 2.75V12C11.25 12.4142 11.5858 12.75 12 12.75H21.25C21.6642 12.75 22 12.4142 22 12C22 6.47715 17.5228 2 12 2ZM12.75 11.25V3.53263C13.2645 3.57761 13.7659 3.66843 14.25 3.80098V3.80099C15.6929 4.19606 16.9827 4.96184 18.0104 5.98959C19.0382 7.01734 19.8039 8.30707 20.199 9.75C20.3316 10.2341 20.4224 10.7355 20.4674 11.25H12.75ZM2 12C2 7.25083 5.31065 3.27489 9.75 2.25415V3.80099C6.14748 4.78734 3.5 8.0845 3.5 12C3.5 16.6944 7.30558 20.5 12 20.5C15.9155 20.5 19.2127 17.8525 20.199 14.25H21.7459C20.7251 18.6894 16.7492 22 12 22C6.47715 22 2 17.5229 2 12Z"
                                    fill=""
                                />
                            </svg>

                            <span
                                class="menu-item-text"
                                :class="sidebarToggle ? 'lg:hidden' : ''"
                            >
                                Analytics
                            </span>

                            <svg
                                class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(openAccordion === 'Analytics') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : '' ]"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                                    stroke=""
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div
                            class="overflow-hidden transform translate"
                            :class="(openAccordion === 'Analytics') ? 'block' :'hidden'"
                        >
                            <ul
                                :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9"
                            >
                                <li>
                                    <a
                                        href="{{ admin_route('analytics') }}"
                                        class="menu-dropdown-item group"
                                        :class="page === 'analytics' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="menu-dropdown-item group"
                                        :class="page === 'reports' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        Reports
                        </a>
                    </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Analytics -->
                </ul>
            </div>

            <!-- Others Group -->
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span
                        class="menu-group-title"
                        :class="sidebarToggle ? 'lg:hidden' : ''"
                    >
                        others
                    </span>

                    <svg
                        :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="mx-auto fill-current menu-group-icon"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill=""
                        />
                    </svg>
                </h3>

                <ul class="flex flex-col gap-4 mb-6">
                    <!-- Menu Item Settings -->
                    <li>
                        <a
                            href="#"
                            data-accordion="Settings"
                            class="menu-item group"
                            :class=" (selected === 'Settings') || (page === 'settings' || page === 'profile') ? 'menu-item-active' : 'menu-item-inactive'"
                        >
                            <svg
                                :class="(selected === 'Settings') || (page === 'settings' || page === 'profile') ? 'menu-item-icon-active'  :'menu-item-icon-inactive'"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M12 1.5C6.20101 1.5 1.5 6.20101 1.5 12C1.5 17.799 6.20101 22.5 12 22.5C17.799 22.5 22.5 17.799 22.5 12C22.5 6.20101 17.799 1.5 12 1.5ZM12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3ZM12 6.75C9.1005 6.75 6.75 9.1005 6.75 12C6.75 14.8995 9.1005 17.25 12 17.25C14.8995 17.25 17.25 14.8995 17.25 12C17.25 9.1005 14.8995 6.75 12 6.75ZM12 8.25C10.2051 8.25 8.75 9.70507 8.75 12C8.75 14.2949 10.2051 15.75 12 15.75C13.7949 15.75 15.25 14.2949 15.25 12C15.25 9.70507 13.7949 8.25 12 8.25Z"
                                    fill=""
                                />
                            </svg>

                            <span
                                class="menu-item-text"
                                :class="sidebarToggle ? 'lg:hidden' : ''"
                            >
                                Settings
                            </span>

                            <svg
                                class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(openAccordion === 'Settings') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : '' ]"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                                    stroke=""
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div
                            class="overflow-hidden transform translate"
                            :class="(openAccordion === 'Settings') ? 'block' :'hidden'"
                        >
                            <ul
                                :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9"
                            >
                                <li>
                                    <a
                                        href="#"
                                        class="menu-dropdown-item group"
                                        :class="page === 'settings' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        General Settings
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="menu-dropdown-item group"
                                        :class="page === 'profile' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                    >
                                        Profile
                        </a>
                    </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Settings -->

                    <!-- Menu Item Logout -->
                    <li>
                        <form method="POST" action="{{ admin_route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="menu-item group w-full text-left"
                            >
                                <svg
                                    class="menu-item-icon-inactive"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M14 2.75C14 2.33579 14.3358 2 14.75 2C15.1642 2 15.5 2.33579 15.5 2.75V5.73291L17.75 5.73291H19C19.4142 5.73291 19.75 6.0687 19.75 6.48291C19.75 6.89712 19.4142 7.23291 19 7.23291H18.5L18.5 12.2329C18.5 15.5691 15.9866 18.3183 12.75 18.6901V21.25C12.75 21.6642 12.4142 22 12 22C11.5858 22 11.25 21.6642 11.25 21.25V18.6901C8.01342 18.3183 5.5 15.5691 5.5 12.2329L5.5 7.23291H5C4.58579 7.23291 4.25 6.89712 4.25 6.48291C4.25 6.0687 4.58579 5.73291 5 5.73291L6.25 5.73291L8.5 5.73291L8.5 2.75C8.5 2.33579 8.83579 2 9.25 2C9.66421 2 10 2.33579 10 2.75L10 5.73291L14 5.73291V2.75ZM7 7.23291L7 12.2329C7 14.9943 9.23858 17.2329 12 17.2329C14.7614 17.2329 17 14.9943 17 12.2329L17 7.23291L7 7.23291Z"
                                        fill=""
                                    />
                                </svg>

                                <span
                                    class="menu-item-text"
                                    :class="sidebarToggle ? 'lg:hidden' : ''"
                                >
                                    Logout
                                </span>
                            </button>
                        </form>
                    </li>
                    <!-- Menu Item Logout -->
                </ul>
            </div>
        </nav>
    </div>
</aside>
