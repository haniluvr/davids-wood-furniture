<!-- Header Start -->
<header class="sticky top-0 z-999 flex w-full bg-white drop-shadow-1 dark:bg-boxdark dark:drop-shadow-none">
    <div class="flex flex-grow items-center justify-between px-4 py-4 shadow-2 md:px-6 2xl:px-11">
        <div class="flex items-center gap-4">
            <!-- Sidebar Toggle Button -->
            <button
                class="flex items-center justify-center w-8 h-8 rounded-lg border border-stroke bg-white text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:border-strokedark dark:bg-boxdark dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-meta-4"
                @click.stop="sidebarOpen = !sidebarOpen"
            >
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>

            <!-- Search Bar -->
            <div class="hidden sm:block">
                <form action="#" method="POST">
                    <div class="relative">
                        <button class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary dark:text-bodydark dark:hover:text-primary">
                            <i data-lucide="search" class="w-5 h-5"></i>
                        </button>

                        <input
                            type="text"
                            placeholder="Type to search..."
                            class="w-full bg-transparent pl-10 pr-4 py-2 text-black focus:outline-none dark:text-white xl:w-125 border border-stroke rounded-md dark:border-strokedark"
                        />
                    </div>
                </form>
            </div>
        </div>

        <div class="flex items-center gap-3 2xsm:gap-7">
            <ul class="flex items-center gap-2 2xsm:gap-4">
                <!-- Dark Mode Toggler -->
                <li>
                    <label
                        :class="darkMode ? 'bg-primary' : 'bg-stroke dark:bg-strokedark'"
                        class="relative m-0 block h-7.5 w-14 rounded-full cursor-pointer"
                    >
                        <input
                            type="checkbox"
                            :value="darkMode"
                            @change="darkMode = !darkMode"
                            class="absolute top-0 z-50 m-0 h-full w-full cursor-pointer opacity-0"
                        />
                        <span
                            :class="darkMode && '!right-1 !translate-x-full !bg-white dark:!bg-black'"
                            class="absolute left-1 top-1/2 flex h-6 w-6 -translate-y-1/2 translate-x-0 items-center justify-center rounded-full bg-white shadow-switcher duration-75 ease-linear dark:bg-boxdark"
                        >
                            <span class="dark:hidden">
                                <i data-lucide="sun" class="w-4 h-4 text-gray-600"></i>
                            </span>
                            <span class="hidden dark:inline-block">
                                <i data-lucide="moon" class="w-4 h-4 text-gray-300"></i>
                            </span>
                        </span>
                    </label>
                </li>
                <!-- Dark Mode Toggler -->

                <!-- Notification Menu Area -->
                <li class="relative" x-data="{ dropdownOpen: false, notifying: true }">
                    <a
                        class="relative flex h-8.5 w-8.5 items-center justify-center rounded-full border-[0.5px] border-stroke bg-gray hover:text-primary dark:border-strokedark dark:bg-meta-4 dark:text-white"
                        href="#"
                        @click.prevent="dropdownOpen = ! dropdownOpen; notifying = false"
                    >
                        <span
                            :class="!notifying && 'hidden'"
                            class="absolute -top-0.5 right-0 z-1 h-2 w-2 rounded-full bg-meta-1"
                        >
                            <span
                                class="absolute -z-1 inline-flex h-full w-full animate-ping rounded-full bg-meta-1 opacity-75"
                            ></span>
                        </span>

                        <i data-lucide="bell" class="w-4 h-4"></i>
                    </a>

                    <!-- Dropdown Start -->
                    <div
                        x-show="dropdownOpen"
                        @click.outside="dropdownOpen = false"
                        class="absolute -right-27 mt-2.5 flex h-90 w-75 flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark sm:right-0 sm:w-80"
                        x-cloak
                    >
                        <div class="px-4.5 py-3">
                            <h5 class="text-sm font-medium text-bodydark2">Notification</h5>
                        </div>

                        <ul class="flex h-auto flex-col overflow-y-auto">
                            <li>
                                <a
                                    class="flex flex-col gap-2.5 border-t border-stroke px-4.5 py-3 hover:bg-gray-2 dark:border-strokedark dark:hover:bg-meta-4"
                                    href="#"
                                >
                                    <p class="text-sm">
                                        <span class="text-black dark:text-white">Edit your information in a swipe</span>
                                        Sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim.
                                    </p>

                                    <p class="text-xs">12 May, 2025</p>
                                </a>
                            </li>
                            <li>
                                <a
                                    class="flex flex-col gap-2.5 border-t border-stroke px-4.5 py-3 hover:bg-gray-2 dark:border-strokedark dark:hover:bg-meta-4"
                                    href="#"
                                >
                                    <p class="text-sm">
                                        <span class="text-black dark:text-white">It is a long established fact</span>
                                        that a reader will be distracted by the readable content.
                                    </p>

                                    <p class="text-xs">24 Feb, 2025</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- Dropdown End -->
                </li>
                <!-- Notification Menu Area -->
            </ul>

            <!-- User Area -->
            <div class="relative" x-data="{ dropdownOpen: false }">
                <a
                    class="flex items-center gap-4"
                    href="#"
                    @click.prevent="dropdownOpen = ! dropdownOpen"
                >
                    <span class="hidden text-right lg:block">
                        <span class="block text-sm font-medium text-black dark:text-white">{{ auth('admin')->user()->first_name }} {{ auth('admin')->user()->last_name }}</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(auth('admin')->user()->role) }}</span>
                    </span>

                    <span class="h-12 w-12 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">
                            {{ substr(auth('admin')->user()->first_name, 0, 1) }}{{ substr(auth('admin')->user()->last_name, 0, 1) }}
                        </span>
                    </span>

                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500 dark:text-gray-400 hidden sm:block"></i>
                </a>

                <!-- Dropdown Start -->
                <div
                    x-show="dropdownOpen"
                    @click.outside="dropdownOpen = false"
                    class="absolute right-0 mt-4 flex w-62.5 flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
                    x-cloak
                >
                    <ul class="flex flex-col gap-5 border-b border-stroke px-6 py-7.5 dark:border-strokedark">
                        <li>
                            <a
                                href="#"
                                class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base"
                            >
                                <i data-lucide="user" class="w-5 h-5"></i>
                                My Profile
                            </a>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base"
                            >
                                <i data-lucide="contact" class="w-5 h-5"></i>
                                My Contacts
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ route('admin.settings.index') }}"
                                class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base"
                            >
                                <i data-lucide="settings" class="w-5 h-5"></i>
                                Account Settings
                            </a>
                        </li>
                    </ul>
                    <form method="POST" action="{{ route('admin.logout') }}" class="px-6 py-4">
                        @csrf
                        <button type="submit" class="flex items-center gap-3.5 px-6 py-4 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                            Log Out
                        </button>
                    </form>
                </div>
                <!-- Dropdown End -->
            </div>
            <!-- User Area -->
        </div>
    </div>
</header>
<!-- Header End -->