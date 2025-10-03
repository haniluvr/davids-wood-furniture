<aside
    :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
    class="absolute left-0 top-0 z-9999 flex h-screen w-72 flex-col overflow-y-hidden bg-black duration-300 ease-linear dark:bg-boxdark lg:static lg:translate-x-0 sidebar"
    @click.outside="sidebarToggle = false"
>
    <!-- SIDEBAR HEADER -->
    <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('admin/images/logo/logo.svg') }}" alt="Logo" class="h-8" />
        </a>

        <button
            class="block lg:hidden"
            @click.stop="sidebarToggle = !sidebarToggle"
        >
            <svg
                class="fill-current"
                width="20"
                height="18"
                viewBox="0 0 20 18"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M19 8.175H2.98748L9.36248 1.6875C9.69998 1.35 9.69998 0.825 9.36248 0.4875C9.02498 0.15 8.49998 0.15 8.16248 0.4875L0.399976 8.3625C0.0624756 8.7 0.0624756 9.225 0.399976 9.5625L8.16248 17.4375C8.31248 17.5875 8.53748 17.7 8.76248 17.7C8.98748 17.7 9.17498 17.625 9.36248 17.475C9.69998 17.1375 9.69998 16.6125 9.36248 16.275L3.02498 9.8625H19C19.45 9.8625 19.825 9.4875 19.825 9.0375C19.825 8.55 19.45 8.175 19 8.175Z"
                    fill=""
                />
            </svg>
        </button>
    </div>
    <!-- SIDEBAR HEADER -->

    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
        <!-- Sidebar Menu -->
        <nav class="mt-5 px-4 py-4 lg:mt-9 lg:px-6" x-data="{selected: 'Dashboard'}">
            <!-- Menu Group -->
            <div>
                <h3 class="mb-4 ml-4 text-sm font-semibold text-bodydark2">MENU</h3>

                <ul class="mb-6 flex flex-col gap-1.5">
                    <!-- Dashboard -->
                    <li>
                        <a
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                            href="#"
                            @click="selected = (selected === 'Dashboard' ? '':'Dashboard')"
                            :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'Dashboard') || (page === 'ecommerce' || page === 'analytics' || page === 'stocks' || page === 'crm' || page === 'marketing') }"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M6.10322 0.956299H2.53135C1.5751 0.956299 0.787598 1.7438 0.787598 2.70005V6.27192C0.787598 7.22817 1.5751 8.01567 2.53135 8.01567H6.10322C7.05947 8.01567 7.84697 7.22817 7.84697 6.27192V2.72817C7.8751 1.7438 7.0876 0.956299 6.10322 0.956299ZM6.60947 6.30005C6.60947 6.5813 6.38447 6.8063 6.10322 6.8063H2.53135C2.2501 6.8063 2.0251 6.5813 2.0251 6.30005V2.72817C2.0251 2.44692 2.2501 2.22192 2.53135 2.22192H6.10322C6.38447 2.22192 6.60947 2.44692 6.60947 2.72817V6.30005Z"
                                    fill=""
                                />
                                <path
                                    d="M15.4689 0.956299H11.8971C10.9408 0.956299 10.1533 1.7438 10.1533 2.70005V6.27192C10.1533 7.22817 10.9408 8.01567 11.8971 8.01567H15.4689C16.4252 8.01567 17.2127 7.22817 17.2127 6.27192V2.72817C17.2408 1.7438 16.4533 0.956299 15.4689 0.956299ZM15.9752 6.30005C15.9752 6.5813 15.7502 6.8063 15.4689 6.8063H11.8971C11.6158 6.8063 11.3908 6.5813 11.3908 6.30005V2.72817C11.3908 2.44692 11.6158 2.22192 11.8971 2.22192H15.4689C15.7502 2.22192 15.9752 2.44692 15.9752 2.72817V6.30005Z"
                                    fill=""
                                />
                                <path
                                    d="M6.10322 9.92822H2.53135C1.5751 9.92822 0.787598 10.7157 0.787598 11.672V15.2438C0.787598 16.2001 1.5751 16.9876 2.53135 16.9876H6.10322C7.05947 16.9876 7.84697 16.2001 7.84697 15.2438V11.7001C7.8751 10.7157 7.0876 9.92822 6.10322 9.92822ZM6.60947 15.272C6.60947 15.5532 6.38447 15.7782 6.10322 15.7782H2.53135C2.2501 15.7782 2.0251 15.5532 2.0251 15.272V11.7001C2.0251 11.4188 2.2501 11.1938 2.53135 11.1938H6.10322C6.38447 11.1938 6.60947 11.4188 6.60947 11.7001V15.272Z"
                                    fill=""
                                />
                                <path
                                    d="M15.4689 9.92822H11.8971C10.9408 9.92822 10.1533 10.7157 10.1533 11.672V15.2438C10.1533 16.2001 10.9408 16.9876 11.8971 16.9876H15.4689C16.4252 16.9876 17.2127 16.2001 17.2127 15.2438V11.7001C17.2408 10.7157 16.4533 9.92822 15.4689 9.92822ZM15.9752 15.272C15.9752 15.5532 15.7502 15.7782 15.4689 15.7782H11.8971C11.6158 15.7782 11.3908 15.5532 11.3908 15.272V11.7001C11.3908 11.4188 11.6158 11.1938 11.8971 11.1938H15.4689C15.7502 11.1938 15.9752 11.4188 15.9752 11.7001V15.272Z"
                                    fill=""
                                />
                            </svg>

                            Dashboard

                            <svg
                                class="absolute right-4 top-1/2 -translate-y-1/2 fill-current"
                                :class="{
                                    'rotate-180': (selected === 'Dashboard')
                                }"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M4.41107 6.9107C4.73651 6.58527 5.26414 6.58527 5.58958 6.9107L10.0003 11.3214L14.4111 6.91071C14.7365 6.58527 15.2641 6.58527 15.5896 6.91071C15.915 7.23614 15.915 7.76378 15.5896 8.08922L10.5896 13.0892C10.2641 13.4147 9.73651 13.4147 9.41107 13.0892L4.41107 8.08922C4.08563 7.76378 4.08563 7.23614 4.41107 6.9107Z"
                                    fill=""
                                />
                            </svg>
                        </a>
                        <!-- Dropdown Menu Start -->
                        <div
                            class="translate transform overflow-hidden"
                            :class="(selected === 'Dashboard') ? 'block' :'hidden'"
                        >
                            <ul class="mb-5.5 mt-4 flex flex-col gap-2.5 pl-6">
                                <li>
                                    <a
                                        class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                                        href="{{ route('admin.dashboard') }}"
                                        :class="page === 'ecommerce' && 'text-white'"
                                    >eCommerce</a>
                                </li>
                                <li>
                                    <a
                                        class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                                        href="#"
                                    >Analytics</a>
                                </li>
                                <li>
                                    <a
                                        class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                                        href="#"
                                    >Marketing</a>
                                </li>
                                <li>
                                    <a
                                        class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                                        href="#"
                                    >CRM</a>
                                </li>
                                <li>
                                    <a
                                        class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                                        href="#"
                                    >Stocks</a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>

                    <!-- E-commerce -->
                    <li>
                        <a
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                            href="#"
                            @click="selected = (selected === 'E-commerce' ? '':'E-commerce')"
                            :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'E-commerce') }"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="19"
                                viewBox="0 0 18 19"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g clip-path="url(#clip0_130_9763)">
                                    <path
                                        d="M10.8563 0.55835C10.5188 0.55835 10.2095 0.8396 10.2095 1.20522V6.83022C10.2095 7.16773 10.4907 7.4771 10.8563 7.4771H16.8751C17.0438 7.4771 17.2126 7.39272 17.3251 7.28022C17.4376 7.1396 17.4938 6.97085 17.4938 6.8021C17.2688 3.28647 14.3438 0.55835 10.8563 0.55835ZM11.4751 6.15522V1.8521C13.8095 2.13335 15.6938 3.8771 16.1438 6.18335H11.4751V6.15522Z"
                                        fill=""
                                    />
                                    <path
                                        d="M15.3845 8.7427H9.1126V2.69582C9.1126 2.35832 8.83135 2.07707 8.49385 2.07707C8.40947 2.07707 8.3251 2.07707 8.24072 2.07707C3.96572 2.04895 0.506348 5.53645 0.506348 9.81145C0.506348 14.0864 3.99385 17.5739 8.26885 17.5739C12.5438 17.5739 16.0313 14.0864 16.0313 9.81145C16.0313 9.6427 16.0313 9.47395 16.0032 9.33332C16.0032 8.99582 15.722 8.7427 15.3845 8.7427ZM8.26885 16.3083C4.66885 16.3083 1.77197 13.4114 1.77197 9.81145C1.77197 6.3802 4.47197 3.53957 7.8751 3.3427V9.36145C7.8751 9.69895 8.15635 10.0083 8.52197 10.0083H14.7938C14.6813 13.4958 11.7845 16.3083 8.26885 16.3083Z"
                                        fill=""
                                    />
                                </g>
                                <defs>
                                    <clipPath id="clip0_130_9763">
                                        <rect width="18" height="18" fill="white" transform="translate(0 0.052124)" />
                                    </clipPath>
                                </defs>
                            </svg>
                            E-commerce
                            <span class="absolute right-4 top-1/2 -translate-y-1/2">
                                <span class="rounded bg-primary px-2 py-0.5 text-xs font-medium text-white">New</span>
                            </span>
                        </a>
                        <!-- Dropdown Menu Start -->
                        <div
                            class="translate transform overflow-hidden"
                            :class="(selected === 'E-commerce') ? 'block' :'hidden'"
                        >
                            <ul class="mb-5.5 mt-4 flex flex-col gap-2.5 pl-6">
                                <li>
                                    <a
                                        class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                                        href="{{ route('admin.products') }}"
                                    >Products</a>
                                </li>
                                <li>
                                    <a
                                        class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                                        href="#"
                                    >Add Product</a>
                                </li>
                                <li>
                                    <a
                                        class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                                        href="#"
                                    >Billing</a>
                                </li>
                                <li>
                                    <a
                                        class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                                        href="#"
                                    >Invoices</a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>

                    <!-- Calendar -->
                    <li>
                        <a
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                            href="#"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M15.7499 2.9812H14.2874V2.36245C14.2874 2.02495 14.0062 1.71558 13.6405 1.71558C13.2749 1.71558 12.9937 1.99683 12.9937 2.36245V2.9812H4.97803V2.36245C4.97803 2.02495 4.69678 1.71558 4.33115 1.71558C3.96553 1.71558 3.68428 1.99683 3.68428 2.36245V2.9812H2.2499C1.29365 2.9812 0.478027 3.7968 0.478027 4.75305V14.5968C0.478027 15.553 1.29365 16.3687 2.2499 16.3687H15.7499C16.7062 16.3687 17.5218 15.553 17.5218 14.5968V4.72495C17.5218 3.7968 16.7062 2.9812 15.7499 2.9812ZM1.77178 8.21245H4.1624V10.9968H1.77178V8.21245ZM5.42803 8.21245H8.38115V10.9968H5.42803V8.21245ZM8.38115 12.2906V15.0749H5.42803V12.2906H8.38115ZM9.64678 8.21245H12.5999V10.9968H9.64678V8.21245ZM9.64678 12.2906H12.5999V15.0749H9.64678V12.2906ZM13.8624 8.21245H16.2531V10.9968H13.8624V8.21245ZM2.2499 4.24683H3.7124V4.83745C3.7124 5.17495 3.99365 5.48433 4.35928 5.48433C4.7249 5.48433 5.00615 5.20308 5.00615 4.83745V4.24683H13.0218V4.83745C13.0218 5.17495 13.3031 5.48433 13.6687 5.48433C14.0343 5.48433 14.3156 5.20308 14.3156 4.83745V4.24683H15.7781C16.0593 4.24683 16.2843 4.47183 16.2843 4.75308V6.94683H1.77178V4.75308C1.77178 4.47183 1.96865 4.24683 2.2499 4.24683Z"
                                    fill=""
                                />
                            </svg>
                            Calendar
                        </a>
                    </li>

                    <!-- User Profile -->
                    <li>
                        <a
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                            href="#"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M9.0002 7.79065C11.0814 7.79065 12.7689 6.1594 12.7689 4.1344C12.7689 2.1094 11.0814 0.478149 9.0002 0.478149C6.91895 0.478149 5.23145 2.1094 5.23145 4.1344C5.23145 6.1594 6.91895 7.79065 9.0002 7.79065ZM9.0002 1.7719C10.3783 1.7719 11.5033 2.84065 11.5033 4.16252C11.5033 5.4844 10.3783 6.55315 9.0002 6.55315C7.62207 6.55315 6.49707 5.4844 6.49707 4.16252C6.49707 2.84065 7.62207 1.7719 9.0002 1.7719Z"
                                    fill=""
                                />
                                <path
                                    d="M10.8283 9.05627H7.17207C4.16269 9.05627 1.71582 11.5031 1.71582 14.5125V16.875C1.71582 17.2125 1.99707 17.5219 2.36269 17.5219C2.72832 17.5219 3.00957 17.2407 3.00957 16.875V14.5125C3.00957 12.2188 4.87832 10.35 7.17207 10.35H10.8283C13.1221 10.35 14.9908 12.2188 14.9908 14.5125V16.875C14.9908 17.2125 15.2721 17.5219 15.6377 17.5219C16.0033 17.5219 16.2846 17.2407 16.2846 16.875V14.5125C16.2846 11.5031 13.8377 9.05627 10.8283 9.05627Z"
                                    fill=""
                                />
                            </svg>
                            User Profile
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Others Group -->
            <div>
                <h3 class="mb-4 ml-4 text-sm font-semibold text-bodydark2">OTHERS</h3>

                <ul class="mb-6 flex flex-col gap-1.5">
                    <!-- Charts -->
                    <li>
                        <a
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                            href="#"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="19"
                                viewBox="0 0 18 19"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g clip-path="url(#clip0_130_9801)">
                                    <path
                                        d="M3.45928 0.984375H1.6874C1.04053 0.984375 0.478027 1.51875 0.478027 2.19375V15.8063C0.478027 16.4813 1.01240 17.0438 1.6874 17.0438H3.45928C4.10615 17.0438 4.66865 16.5094 4.66865 15.8063V2.16562C4.66865 1.51875 4.13428 0.984375 3.45928 0.984375ZM3.40303 15.7781H1.71553V2.25H3.40303V15.7781Z"
                                        fill=""
                                    />
                                    <path
                                        d="M10.1905 4.92188H8.41865C7.77178 4.92188 7.20928 5.45625 7.20928 6.13125V15.8063C7.20928 16.4813 7.74365 17.0438 8.41865 17.0438H10.1905C10.8374 17.0438 11.3999 16.5094 11.3999 15.8063V6.13125C11.3999 5.45625 10.8655 4.92188 10.1905 4.92188ZM10.1343 15.7781H8.44678V6.1875H10.1343V15.7781Z"
                                        fill=""
                                    />
                                    <path
                                        d="M16.9593 7.92188H15.1874C14.5405 7.92188 13.978 8.45625 13.978 9.13125V15.8063C13.978 16.4813 14.5124 17.0438 15.1874 17.0438H16.9593C17.6062 17.0438 18.1687 16.5094 18.1687 15.8063V9.13125C18.1687 8.45625 17.6343 7.92188 16.9593 7.92188ZM16.9031 15.7781H15.2156V9.1875H16.9031V15.7781Z"
                                        fill=""
                                    />
                                </g>
                                <defs>
                                    <clipPath id="clip0_130_9801">
                                        <rect width="18" height="18" fill="white" transform="translate(0 0.052124)" />
                                    </clipPath>
                                </defs>
                            </svg>
                            Charts
                        </a>
                    </li>

                    <!-- Settings -->
                    <li>
                        <a
                            class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
                            href="#"
                        >
                            <svg
                                class="fill-current"
                                width="18"
                                height="19"
                                viewBox="0 0 18 19"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g clip-path="url(#clip0_130_9814)">
                                    <path
                                        d="M17.0721 7.30835C16.7909 6.99397 16.3971 6.8221 15.9752 6.8221H15.8033V6.42397C15.8033 5.26585 14.8596 4.32210 13.7015 4.32210H12.2471V3.8721C12.2471 3.45022 11.9377 3.11272 11.5158 3.11272C11.094 3.11272 10.7846 3.42210 10.7846 3.8721V4.32210H7.18396V3.8721C7.18396 3.45022 6.87458 3.11272 6.45271 3.11272C6.03083 3.11272 5.72146 3.42210 5.72146 3.8721V4.32210H4.26708C3.10896 4.32210 2.16521 5.26585 2.16521 6.42397V6.8221H2.02146C1.59958 6.8221 1.29021 7.13147 1.29021 7.55335C1.29021 7.97522 1.59958 8.2846 2.02146 8.2846H2.16521V10.9971H2.02146C1.59958 10.9971 1.29021 11.3065 1.29021 11.7284C1.29021 12.1502 1.59958 12.4596 2.02146 12.4596H2.16521V12.8577C2.16521 14.0159 3.10896 14.9596 4.26708 14.9596H5.72146V15.4096C5.72146 15.8315 6.03083 16.169 6.45271 16.169C6.87458 16.169 7.18396 15.8596 7.18396 15.4096V14.9596H10.7846V15.4096C10.7846 15.8315 11.094 16.169 11.5158 16.169C11.9377 16.169 12.2471 15.8596 12.2471 15.4096V14.9596H13.7015C14.8596 14.9596 15.8033 14.0159 15.8033 12.8577V12.4596H15.9752C16.3971 12.4596 16.7065 12.1502 16.7065 11.7284C16.7065 11.3065 16.3971 10.9971 15.9752 10.9971H15.8033V8.2846H15.9752C16.3971 8.2846 16.7065 7.97522 16.7065 7.55335C16.7065 7.13147 16.3971 6.8221 15.9752 6.8221H15.8033V6.42397C15.8033 5.26585 14.8596 4.32210 13.7015 4.32210H12.2471V3.8721C12.2471 3.45022 11.9377 3.11272 11.5158 3.11272C11.094 3.11272 10.7846 3.42210 10.7846 3.8721V4.32210H7.18396V3.8721C7.18396 3.45022 6.87458 3.11272 6.45271 3.11272C6.03083 3.11272 5.72146 3.42210 5.72146 3.8721V4.32210H4.26708C3.10896 4.32210 2.16521 5.26585 2.16521 6.42397V6.8221H2.02146C1.59958 6.8221 1.29021 7.13147 1.29021 7.55335C1.29021 7.97522 1.59958 8.2846 2.02146 8.2846H2.16521V10.9971H2.02146C1.59958 10.9971 1.29021 11.3065 1.29021 11.7284C1.29021 12.1502 1.59958 12.4596 2.02146 12.4596H2.16521V12.8577C2.16521 14.0159 3.10896 14.9596 4.26708 14.9596H5.72146V15.4096C5.72146 15.8315 6.03083 16.169 6.45271 16.169C6.87458 16.169 7.18396 15.8596 7.18396 15.4096V14.9596H10.7846V15.4096C10.7846 15.8315 11.094 16.169 11.5158 16.169C11.9377 16.169 12.2471 15.8596 12.2471 15.4096V14.9596H13.7015C14.8596 14.9596 15.8033 14.0159 15.8033 12.8577V12.4596H15.9752C16.3971 12.4596 16.7065 12.1502 16.7065 11.7284C16.7065 11.3065 16.3971 10.9971 15.9752 10.9971H15.8033V8.2846H15.9752C16.3971 8.2846 16.7065 7.97522 16.7065 7.55335C16.7065 7.13147 16.3971 6.8221 15.9752 6.8221H15.8033V6.42397C15.8033 5.26585 14.8596 4.32210 13.7015 4.32210H12.2471V3.8721C12.2471 3.45022 11.9377 3.11272 11.5158 3.11272Z"
                                        fill=""
                                    />
                                </g>
                                <defs>
                                    <clipPath id="clip0_130_9814">
                                        <rect width="18" height="18" fill="white" transform="translate(0 0.052124)" />
                                    </clipPath>
                                </defs>
                            </svg>
                            Settings
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Sidebar Menu -->
    </div>
</aside>
