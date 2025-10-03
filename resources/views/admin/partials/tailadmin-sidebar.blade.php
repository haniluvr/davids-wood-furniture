<aside
    :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"
>
    <!-- SIDEBAR HEADER -->
    <div
        :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 pt-8 sidebar-header pb-7"
    >
        <a href="{{ route('admin.dashboard') }}">
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
        <nav x-data="{selected: $persist('{{ request()->route()->getName() ?? 'Dashboard' }}')}">
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
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </h3>

                <ul class="mb-6 flex flex-col gap-1.5">
                    <!-- Dashboard -->
                    <li>
                        <a
                            href="{{ route('admin.dashboard') }}"
                            @click="selected = 'Dashboard'"
                            :class="selected === 'Dashboard' || '{{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }}' === 'true' ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white'"
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium duration-300 ease-in-out"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path d="M6.10322 0.956299H2.53135C1.5751 0.956299 0.787598 1.7438 0.787598 2.70005V6.27192C0.787598 7.22817 1.5751 8.01567 2.53135 8.01567H6.10322C7.05947 8.01567 7.84697 7.22817 7.84697 6.27192V2.72817C7.8751 1.7438 7.0876 0.956299 6.10322 0.956299ZM6.60947 6.30005C6.60947 6.5813 6.38447 6.8063 6.10322 6.8063H2.53135C2.2501 6.8063 2.0251 6.5813 2.0251 6.30005V2.72817C2.0251 2.44692 2.2501 2.22192 2.53135 2.22192H6.10322C6.38447 2.22192 6.60947 2.44692 6.60947 2.72817V6.30005Z"/>
                                <path d="M15.4689 0.956299H11.8971C10.9408 0.956299 10.1533 1.7438 10.1533 2.70005V6.27192C10.1533 7.22817 10.9408 8.01567 11.8971 8.01567H15.4689C16.4252 8.01567 17.2127 7.22817 17.2127 6.27192V2.72817C17.2408 1.7438 16.4533 0.956299 15.4689 0.956299ZM15.9752 6.30005C15.9752 6.5813 15.7502 6.8063 15.4689 6.8063H11.8971C11.6158 6.8063 11.3908 6.5813 11.3908 6.30005V2.72817C11.3908 2.44692 11.6158 2.22192 11.8971 2.22192H15.4689C15.7502 2.22192 15.9752 2.44692 15.9752 2.72817V6.30005Z"/>
                                <path d="M6.10322 9.92822H2.53135C1.5751 9.92822 0.787598 10.7157 0.787598 11.672V15.2438C0.787598 16.2001 1.5751 16.9876 2.53135 16.9876H6.10322C7.05947 16.9876 7.84697 16.2001 7.84697 15.2438V11.7001C7.8751 10.7157 7.0876 9.92822 6.10322 9.92822ZM6.60947 15.272C6.60947 15.5532 6.38447 15.7782 6.10322 15.7782H2.53135C2.2501 15.7782 2.0251 15.5532 2.0251 15.272V11.7001C2.0251 11.4188 2.2501 11.1938 2.53135 11.1938H6.10322C6.38447 11.1938 6.60947 11.4188 6.60947 11.7001V15.272Z"/>
                                <path d="M15.4689 9.92822H11.8971C10.9408 9.92822 10.1533 10.7157 10.1533 11.672V15.2438C10.1533 16.2001 10.9408 16.9876 11.8971 16.9876H15.4689C16.4252 16.9876 17.2127 16.2001 17.2127 15.2438V11.7001C17.2408 10.7157 16.4533 9.92822 15.4689 9.92822ZM15.9752 15.272C15.9752 15.5532 15.7502 15.7782 15.4689 15.7782H11.8971C11.6158 15.7782 11.3908 15.5532 11.3908 15.272V11.7001C11.3908 11.4188 11.6158 11.1938 11.8971 11.1938H15.4689C15.7502 11.1938 15.9752 11.4188 15.9752 11.7001V15.272Z"/>
                            </svg>
                            <span :class="sidebarToggle ? 'lg:hidden' : ''">Dashboard</span>
                        </a>
                    </li>

                    <!-- Products -->
                    <li>
                        <a
                            href="{{ route('admin.products') }}"
                            @click="selected = 'Products'"
                            :class="selected === 'Products' || '{{ request()->routeIs('admin.products') ? 'true' : 'false' }}' === 'true' ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white'"
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium duration-300 ease-in-out"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path d="M15.7499 2.9812H14.2874V2.36245C14.2874 2.02495 14.0062 1.71558 13.6405 1.71558C13.2749 1.71558 12.9937 1.99683 12.9937 2.36245V2.9812H4.97803V2.36245C4.97803 2.02495 4.69678 1.71558 4.33115 1.71558C3.96553 1.71558 3.68428 1.99683 3.68428 2.36245V2.9812H2.2499C1.29365 2.9812 0.478027 3.7968 0.478027 4.75305V14.5968C0.478027 15.553 1.29365 16.3687 2.2499 16.3687H15.7499C16.7062 16.3687 17.5218 15.553 17.5218 14.5968V4.72495C17.5218 3.7968 16.7062 2.9812 15.7499 2.9812ZM1.77178 8.21245H4.1624V10.9968H1.77178V8.21245ZM5.42803 8.21245H8.38115V10.9968H5.42803V8.21245ZM8.38115 12.2906V15.0749H5.42803V12.2906H8.38115ZM9.64678 8.21245H12.5999V10.9968H9.64678V8.21245ZM9.64678 12.2906H12.5999V15.0749H9.64678V12.2906ZM13.8624 8.21245H16.2531V10.9968H13.8624V8.21245ZM2.2499 4.24683H3.7124V4.83745C3.7124 5.17495 3.99365 5.48433 4.35928 5.48433C4.7249 5.48433 5.00615 5.20308 5.00615 4.83745V4.24683H13.0218V4.83745C13.0218 5.17495 13.3031 5.48433 13.6687 5.48433C14.0343 5.48433 14.3156 5.20308 14.3156 4.83745V4.24683H15.7781C16.0593 4.24683 16.2843 4.47183 16.2843 4.75308V6.94683H1.77178V4.75308C1.77178 4.47183 1.96865 4.24683 2.2499 4.24683Z"/>
                            </svg>
                            <span :class="sidebarToggle ? 'lg:hidden' : ''">Products</span>
                        </a>
                    </li>

                    <!-- Customers -->
                    <li>
                        <a
                            href="{{ route('admin.customers') }}"
                            @click="selected = 'Customers'"
                            :class="selected === 'Customers' || '{{ request()->routeIs('admin.customers') ? 'true' : 'false' }}' === 'true' ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white'"
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium duration-300 ease-in-out"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path d="M9.0002 7.79065C11.0814 7.79065 12.7689 6.1594 12.7689 4.1344C12.7689 2.1094 11.0814 0.478149 9.0002 0.478149C6.91895 0.478149 5.23145 2.1094 5.23145 4.1344C5.23145 6.1594 6.91895 7.79065 9.0002 7.79065ZM9.0002 1.7719C10.3783 1.7719 11.5033 2.84065 11.5033 4.16252C11.5033 5.4844 10.3783 6.55315 9.0002 6.55315C7.62207 6.55315 6.49707 5.4844 6.49707 4.16252C6.49707 2.84065 7.62207 1.7719 9.0002 1.7719Z"/>
                                <path d="M10.8283 9.05627H7.17207C4.16269 9.05627 1.71582 11.5031 1.71582 14.5125V16.875C1.71582 17.2125 1.99707 17.5219 2.36269 17.5219C2.72832 17.5219 3.00957 17.2407 3.00957 16.875V14.5125C3.00957 12.2188 4.87832 10.35 7.17207 10.35H10.8283C13.1221 10.35 14.9908 12.2188 14.9908 14.5125V16.875C14.9908 17.2125 15.2721 17.5219 15.6377 17.5219C16.0033 17.5219 16.2846 17.2407 16.2846 16.875V14.5125C16.2846 11.5031 13.8377 9.05627 10.8283 9.05627Z"/>
                            </svg>
                            <span :class="sidebarToggle ? 'lg:hidden' : ''">Customers</span>
                        </a>
                    </li>

                    <!-- Orders -->
                    <li>
                        <a
                            href="{{ route('admin.orders') }}"
                            @click="selected = 'Orders'"
                            :class="selected === 'Orders' || '{{ request()->routeIs('admin.orders') ? 'true' : 'false' }}' === 'true' ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white'"
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium duration-300 ease-in-out"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path d="M16.8754 11.6719C16.5379 11.6719 16.2285 11.9531 16.2285 12.3187V14.8219C16.2285 15.075 16.0316 15.2719 15.7785 15.2719H2.22227C1.96914 15.2719 1.77227 15.075 1.77227 14.8219V12.3187C1.77227 11.9812 1.49102 11.6719 1.12539 11.6719C0.759766 11.6719 0.478516 11.9531 0.478516 12.3187V14.8219C0.478516 15.7781 1.23789 16.5375 2.19414 16.5375H15.7785C16.7348 16.5375 17.4941 15.7781 17.4941 14.8219V12.3187C17.5223 11.9531 17.2129 11.6719 16.8754 11.6719Z"/>
                                <path d="M8.55074 12.3469C8.66324 12.4594 8.83199 12.5156 9.00074 12.5156C9.16949 12.5156 9.33824 12.4594 9.45074 12.3469L13.4726 8.43752C13.7257 8.1844 13.7257 7.79065 13.5007 7.53752C13.2476 7.2844 12.8539 7.2844 12.6007 7.5094L9.64762 10.4063V2.1094C9.64762 1.7719 9.36637 1.46252 9.00074 1.46252C8.66324 1.46252 8.35387 1.74377 8.35387 2.1094V10.4063L5.40074 7.53752C5.14762 7.2844 4.75387 7.31252 4.50074 7.53752C4.24762 7.79065 4.27574 8.1844 4.50074 8.43752L8.55074 12.3469Z"/>
                            </svg>
                            <span :class="sidebarToggle ? 'lg:hidden' : ''">Orders</span>
                        </a>
                    </li>

                    <!-- Analytics -->
                    <li>
                        <a
                            href="{{ route('admin.analytics') }}"
                            @click="selected = 'Analytics'"
                            :class="selected === 'Analytics' || '{{ request()->routeIs('admin.analytics') ? 'true' : 'false' }}' === 'true' ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white'"
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium duration-300 ease-in-out"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path d="M15.7499 2.9812H14.2874V2.36245C14.2874 2.02495 14.0062 1.71558 13.6405 1.71558C13.2749 1.71558 12.9937 1.99683 12.9937 2.36245V2.9812H4.97803V2.36245C4.97803 2.02495 4.69678 1.71558 4.33115 1.71558C3.96553 1.71558 3.68428 1.99683 3.68428 2.36245V2.9812H2.2499C1.29365 2.9812 0.478027 3.7968 0.478027 4.75305V14.5968C0.478027 15.553 1.29365 16.3687 2.2499 16.3687H15.7499C16.7062 16.3687 17.5218 15.553 17.5218 14.5968V4.72495C17.5218 3.7968 16.7062 2.9812 15.7499 2.9812ZM1.77178 8.21245H4.1624V10.9968H1.77178V8.21245ZM5.42803 8.21245H8.38115V10.9968H5.42803V8.21245ZM8.38115 12.2906V15.0749H5.42803V12.2906H8.38115ZM9.64678 8.21245H12.5999V10.9968H9.64678V8.21245ZM9.64678 12.2906H12.5999V15.0749H9.64678V12.2906ZM13.8624 8.21245H16.2531V10.9968H13.8624V8.21245ZM2.2499 4.24683H3.7124V4.83745C3.7124 5.17495 3.99365 5.48433 4.35928 5.48433C4.7249 5.48433 5.00615 5.20308 5.00615 4.83745V4.24683H13.0218V4.83745C13.0218 5.17495 13.3031 5.48433 13.6687 5.48433C14.0343 5.48433 14.3156 5.20308 14.3156 4.83745V4.24683H15.7781C16.0593 4.24683 16.2843 4.47183 16.2843 4.75308V6.94683H1.77178V4.75308C1.77178 4.47183 1.96865 4.24683 2.2499 4.24683Z"/>
                            </svg>
                            <span :class="sidebarToggle ? 'lg:hidden' : ''">Analytics</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Settings Group -->
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">OTHERS</span>
                </h3>

                <ul class="mb-6 flex flex-col gap-1.5">
                    <!-- Settings -->
                    <li>
                        <a
                            href="#"
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-600 duration-300 ease-in-out hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path d="M6.10322 0.956299H2.53135C1.5751 0.956299 0.787598 1.7438 0.787598 2.70005V6.27192C0.787598 7.22817 1.5751 8.01567 2.53135 8.01567H6.10322C7.05947 8.01567 7.84697 7.22817 7.84697 6.27192V2.72817C7.8751 1.7438 7.0876 0.956299 6.10322 0.956299ZM6.60947 6.30005C6.60947 6.5813 6.38447 6.8063 6.10322 6.8063H2.53135C2.2501 6.8063 2.0251 6.5813 2.0251 6.30005V2.72817C2.0251 2.44692 2.2501 2.22192 2.53135 2.22192H6.10322C6.38447 2.22192 6.60947 2.44692 6.60947 2.72817V6.30005Z"/>
                            </svg>
                            <span :class="sidebarToggle ? 'lg:hidden' : ''">Settings</span>
                        </a>
                    </li>

                    <!-- Logout -->
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="group relative flex w-full items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-600 duration-300 ease-in-out hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white"
                            >
                                <svg
                                    class="fill-current"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 18 18"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path d="M10.1718 2.67692H15.4312C15.7687 2.67692 16.0499 2.95817 16.0499 3.29567V14.7019C16.0499 15.0394 15.7687 15.3207 15.4312 15.3207H10.1718C9.83431 15.3207 9.55306 15.0394 9.55306 14.7019C9.55306 14.3644 9.83431 14.0832 10.1718 14.0832H14.8124V3.91442H10.1718C9.83431 3.91442 9.55306 3.63317 9.55306 3.29567C9.55306 2.95817 9.83431 2.67692 10.1718 2.67692Z"/>
                                    <path d="M11.4312 8.38129L8.22184 5.17192C7.99684 4.94692 7.60309 4.94692 7.37809 5.17192C7.15309 5.39692 7.15309 5.79067 7.37809 6.01567L9.94684 8.58442H2.36559C2.02809 8.58442 1.74684 8.86567 1.74684 9.20317C1.74684 9.54067 2.02809 9.82192 2.36559 9.82192H9.94684L7.37809 12.3907C7.15309 12.6157 7.15309 13.0094 7.37809 13.2344C7.49059 13.3469 7.63184 13.4032 7.80059 13.4032C7.96934 13.4032 8.11059 13.3469 8.22309 13.2344L11.4325 10.0251C11.6575 9.8001 11.6575 9.40635 11.4312 8.38129Z"/>
                                </svg>
                                <span :class="sidebarToggle ? 'lg:hidden' : ''">Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</aside>
