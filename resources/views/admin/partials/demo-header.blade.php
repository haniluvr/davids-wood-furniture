<header class="sticky top-0 z-999 flex w-full bg-white drop-shadow-1 dark:bg-boxdark dark:drop-shadow-none">
    <div class="flex flex-grow items-center justify-between px-4 py-4 shadow-2 md:px-6 2xl:px-11">
        <div class="flex items-center gap-2 sm:gap-4 lg:hidden">
            <!-- Hamburger Toggle BTN -->
            <button
                class="z-99999 block rounded-sm border border-stroke bg-white p-1.5 shadow-sm dark:border-strokedark dark:bg-boxdark lg:hidden"
                @click.stop="sidebarToggle = !sidebarToggle"
            >
                <span class="relative block h-5.5 w-5.5 cursor-pointer">
                    <span class="du-block absolute right-0 h-full w-full">
                        <span
                            class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-[0] duration-200 ease-in-out dark:bg-white"
                            :class="{ '!w-full delay-300': !sidebarToggle }"
                        ></span>
                        <span
                            class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-150 duration-200 ease-in-out dark:bg-white"
                            :class="{ '!w-full delay-400': !sidebarToggle }"
                        ></span>
                        <span
                            class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-200 duration-200 ease-in-out dark:bg-white"
                            :class="{ '!w-full delay-500': !sidebarToggle }"
                        ></span>
                    </span>
                    <span class="absolute right-0 h-full w-full rotate-45">
                        <span
                            class="absolute left-2.5 top-0 block h-full w-0.5 rounded-sm bg-black delay-300 duration-200 ease-in-out dark:bg-white"
                            :class="{ '!h-0 !delay-[0]': !sidebarToggle }"
                        ></span>
                        <span
                            class="delay-400 absolute left-0 top-2.5 block h-0.5 w-full rounded-sm bg-black duration-200 ease-in-out dark:bg-white"
                            :class="{ '!h-0 !delay-200': !sidebarToggle }"
                        ></span>
                    </span>
                </span>
            </button>
            <!-- Hamburger Toggle BTN -->

            <a class="block flex-shrink-0 lg:hidden" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('admin/images/logo/logo-icon.svg') }}" alt="Logo" />
            </a>
        </div>

        <div class="hidden sm:block">
            <form action="#" method="POST">
                <div class="relative">
                    <button class="absolute left-0 top-1/2 -translate-y-1/2">
                        <svg
                            class="fill-body hover:fill-primary dark:fill-bodydark dark:hover:fill-primary"
                            width="20"
                            height="20"
                            viewBox="0 0 20 20"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M9.16666 3.33332C5.945 3.33332 3.33332 5.945 3.33332 9.16666C3.33332 12.3883 5.945 15 9.16666 15C12.3883 15 15 12.3883 15 9.16666C15 5.945 12.3883 3.33332 9.16666 3.33332ZM1.66666 9.16666C1.66666 5.02452 5.02452 1.66666 9.16666 1.66666C13.3088 1.66666 16.6667 5.02452 16.6667 9.16666C16.6667 13.3088 13.3088 16.6667 9.16666 16.6667C5.02452 16.6667 1.66666 13.3088 1.66666 9.16666Z"
                                fill=""
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M13.2857 13.2857C13.6112 12.9603 14.1388 12.9603 14.4642 13.2857L18.0892 16.9107C18.4147 17.2362 18.4147 17.7638 18.0892 18.0892C17.7638 18.4147 17.2362 18.4147 16.9107 18.0892L13.2857 14.4642C12.9603 14.1388 12.9603 13.6112 13.2857 13.2857Z"
                                fill=""
                            />
                        </svg>
                    </button>

                    <input
                        type="text"
                        placeholder="Search data"
                        class="w-full bg-transparent pl-9 pr-4 text-black focus:outline-none dark:text-white xl:w-125"
                    />
                </div>
            </form>
        </div>

        <div class="flex items-center gap-3 2xsm:gap-7">
            <ul class="flex items-center gap-2 2xsm:gap-4">
                <!-- Dark Mode Toggler -->
                <li>
                    <label
                        :class="darkMode ? 'bg-primary' : 'bg-stroke'"
                        class="relative m-0 block h-7.5 w-14 rounded-full"
                    >
                        <input
                            type="checkbox"
                            :value="darkMode"
                            @change="darkMode = !darkMode"
                            class="dur absolute top-0 z-50 m-0 h-full w-full cursor-pointer opacity-0"
                        />
                        <span
                            :class="darkMode && '!right-1 !translate-x-full !bg-white dark:!bg-white'"
                            class="absolute left-1 top-1/2 flex h-6 w-6 -translate-y-1/2 translate-x-0 items-center justify-center rounded-full bg-white shadow-switcher duration-75 ease-linear"
                        >
                            <span class="dark:hidden">
                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 16 16"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M7.99992 12.6666C10.9455 12.6666 13.3333 10.2789 13.3333 7.33329C13.3333 4.38767 10.9455 1.99996 7.99992 1.99996C5.05431 1.99996 2.6666 4.38767 2.6666 7.33329C2.6666 10.2789 5.05431 12.6666 7.99992 12.6666Z"
                                        fill="#969AA1"
                                    />
                                </svg>
                            </span>
                            <span class="hidden dark:inline-block">
                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 16 16"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M14.3533 10.62C14.2466 10.44 13.9466 10.16 13.1999 10.2933C12.7866 10.3667 12.3666 10.4 11.9466 10.38C10.3933 10.3133 8.98659 9.6 8.00659 8.5C7.13993 7.53333 6.60659 6.27333 6.59993 4.91333C6.59993 4.15333 6.74659 3.42 7.04659 2.72666C7.33993 2.05333 7.13326 1.7 6.98659 1.55333C6.83326 1.4 6.47326 1.18666 5.76659 1.48C3.03993 2.62666 1.35326 5.36 1.55326 8.28666C1.75326 11.04 3.68659 13.3933 6.24659 14.28C6.85993 14.4933 7.50659 14.6067 8.17326 14.6067C8.27993 14.6067 8.38659 14.6067 8.49326 14.6C10.7266 14.4867 12.8199 13.4067 14.2199 11.7133C14.5866 11.2933 14.4666 10.8 14.3533 10.62Z"
                                        fill="#969AA1"
                                    />
                                </svg>
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

                        <svg
                            class="fill-current duration-300 ease-in-out"
                            width="18"
                            height="18"
                            viewBox="0 0 18 18"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M16.1999 14.9343L15.6374 14.0624C15.5249 13.8937 15.4687 13.7249 15.4687 13.528V7.67803C15.4687 6.01865 14.7655 4.47178 13.4718 3.31865C12.4312 2.39053 11.0812 1.7999 9.64678 1.6874V1.1249C9.64678 0.787402 9.35303 0.493652 8.99678 0.493652C8.64053 0.493652 8.34678 0.787402 8.34678 1.1249V1.65928C8.29678 1.65928 8.24678 1.65928 8.19678 1.6874C6.42178 1.90615 4.90303 2.78428 3.84678 4.05303C2.90303 5.17178 2.37178 6.54053 2.37178 7.67803V13.528C2.37178 13.7249 2.31553 13.8937 2.20303 14.0624L1.64053 14.9343C1.44678 15.2155 1.44678 15.5249 1.57178 15.8062C1.69678 16.0874 1.94678 16.2562 2.22803 16.2562H8.79678V16.8749C8.79678 17.2124 9.09053 17.5062 9.44678 17.5062C9.80303 17.5062 10.0968 17.2124 10.0968 16.8749V16.2562H16.6655C16.9468 16.2562 17.1968 16.0874 17.3218 15.8062C17.4468 15.5249 17.4468 15.2155 17.2530 14.9343H16.1999ZM3.23428 14.9905L3.43428 14.653C3.65303 14.2874 3.76553 13.8937 3.76553 13.5V7.67803C3.76553 5.52178 5.02803 3.59053 6.89678 2.84365C7.42803 2.65928 7.95928 2.56553 8.49053 2.56553H8.79678C9.32803 2.56553 9.85928 2.65928 10.3905 2.84365C12.2593 3.59053 13.5218 5.52178 13.5218 7.67803V13.5C13.5218 13.8937 13.6343 14.2874 13.853 14.653L14.053 14.9905H3.23428Z"
                                fill=""
                            />
                        </svg>
                    </a>

                    <!-- Dropdown Start -->
                    <div
                        x-show="dropdownOpen"
                        @click.outside="dropdownOpen = false"
                        class="absolute -right-27 mt-2.5 flex h-90 w-75 flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark sm:right-0 sm:w-80"
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
                                        <span class="text-black dark:text-white">User Terry Franci</span>
                                        requests permission to change<span class="text-black dark:text-white">Project - Nganter App Project</span>
                                    </p>

                                    <p class="text-xs">5 min ago</p>
                                </a>
                            </li>
                            <li>
                                <a
                                    class="flex flex-col gap-2.5 border-t border-stroke px-4.5 py-3 hover:bg-gray-2 dark:border-strokedark dark:hover:bg-meta-4"
                                    href="#"
                                >
                                    <p class="text-sm">
                                        <span class="text-black dark:text-white">User Alena Franci</span>
                                        requests permission to change<span class="text-black dark:text-white">Project - Nganter App Project</span>
                                    </p>

                                    <p class="text-xs">8 min ago</p>
                                </a>
                            </li>
                            <li>
                                <a
                                    class="flex flex-col gap-2.5 border-t border-stroke px-4.5 py-3 hover:bg-gray-2 dark:border-strokedark dark:hover:bg-meta-4"
                                    href="#"
                                >
                                    <p class="text-sm">
                                        <span class="text-black dark:text-white">User Jocelyn Kenter</span>
                                        requests permission to change<span class="text-black dark:text-white">Project - Nganter App Project</span>
                                    </p>

                                    <p class="text-xs">15 min ago</p>
                                </a>
                            </li>
                            <li>
                                <a
                                    class="flex flex-col gap-2.5 border-t border-stroke px-4.5 py-3 hover:bg-gray-2 dark:border-strokedark dark:hover:bg-meta-4"
                                    href="#"
                                >
                                    <p class="text-sm">
                                        <span class="text-black dark:text-white">User Brandon Philips</span>
                                        requests permission to change<span class="text-black dark:text-white">Project - Nganter App Project</span>
                                    </p>

                                    <p class="text-xs">1 hr ago</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- Dropdown End -->
                </li>
                <!-- Notification Menu Area -->

                <!-- Chat Notification Area -->
                <li class="relative" x-data="{ dropdownOpen: false }">
                    <a
                        class="relative flex h-8.5 w-8.5 items-center justify-center rounded-full border-[0.5px] border-stroke bg-gray hover:text-primary dark:border-strokedark dark:bg-meta-4 dark:text-white"
                        href="#"
                        @click.prevent="dropdownOpen = ! dropdownOpen"
                    >
                        <svg
                            class="fill-current duration-300 ease-in-out"
                            width="18"
                            height="18"
                            viewBox="0 0 18 18"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M10.9688 1.57495H7.03135C3.43135 1.57495 0.506348 4.41558 0.506348 7.90308C0.506348 11.3906 2.75635 14.2031 6.00010 14.8749V16.8749C6.00010 17.2124 6.18760 17.5218 6.46885 17.6906C6.58135 17.7499 6.69385 17.7812 6.80635 17.7812C7.03135 17.7812 7.25635 17.6906 7.42510 17.5312L9.64385 15.4687H10.9688C14.5688 15.4687 17.4938 12.6281 17.4938 9.14058C17.4938 5.65308 14.5688 1.57495 10.9688 1.57495ZM10.9688 14.0624H9.3751C9.2251 14.0624 9.07510 14.1218 8.9626 14.2124L7.5001 15.5624V13.9687C7.5001 13.6312 7.21885 13.3499 6.88135 13.3499C3.9376 13.3499 1.9501 11.0624 1.9501 7.90308C1.9501 5.20933 4.23760 3.01870 7.03135 3.01870H10.9688C13.7626 3.01870 16.0501 5.20933 16.0501 9.14058C16.0501 11.8343 13.7626 14.0624 10.9688 14.0624Z"
                                fill=""
                            />
                        </svg>
                    </a>
                </li>
                <!-- Chat Notification Area -->
            </ul>

            <!-- User Area -->
            <div class="relative" x-data="{ dropdownOpen: false }">
                <a
                    class="flex items-center gap-4"
                    href="#"
                    @click.prevent="dropdownOpen = ! dropdownOpen"
                >
                    <span class="hidden text-right lg:block">
                        <span class="block text-sm font-medium text-black dark:text-white">{{ Auth::user()->first_name ?? 'Musharof' }}</span>
                        <span class="block text-xs">{{ Auth::user()->email ?? 'musharof@tailadmin.com' }}</span>
                    </span>

                    <span class="h-12 w-12 rounded-full">
                        <img src="https://via.placeholder.com/150/0000FF/FFFFFF?text={{ substr(Auth::user()->first_name ?? 'M', 0, 1) }}" alt="User" />
                    </span>

                    <svg
                        class="hidden fill-current sm:block"
                        width="12"
                        height="8"
                        viewBox="0 0 12 8"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M0.410765 0.910734C0.736202 0.585297 1.26384 0.585297 1.58928 0.910734L6.00002 5.32148L10.4108 0.910734C10.7362 0.585297 11.2638 0.585297 11.5893 0.910734C11.9147 1.23617 11.9147 1.76381 11.5893 2.08924L6.58928 7.08924C6.26384 7.41468 5.7362 7.41468 5.41077 7.08924L0.410765 2.08924C0.0853277 1.76381 0.0853277 1.23617 0.410765 0.910734Z"
                            fill=""
                        />
                    </svg>
                </a>

                <!-- Dropdown Start -->
                <div
                    x-show="dropdownOpen"
                    @click.outside="dropdownOpen = false"
                    class="absolute right-0 mt-4 flex w-62.5 flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
                >
                    <ul class="flex flex-col gap-5 border-b border-stroke px-6 py-7.5 dark:border-strokedark">
                        <li>
                            <a
                                href="#"
                                class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base"
                            >
                                <svg
                                    class="fill-current"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 22 22"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M11 9.62499C8.42188 9.62499 6.35938 7.59687 6.35938 5.12187C6.35938 2.64687 8.42188 0.618744 11 0.618744C13.5781 0.618744 15.6406 2.64687 15.6406 5.12187C15.6406 7.59687 13.5781 9.62499 11 9.62499ZM11 2.16562C9.28125 2.16562 7.90625 3.50624 7.90625 5.12187C7.90625 6.73749 9.28125 8.07812 11 8.07812C12.7188 8.07812 14.0938 6.73749 14.0938 5.12187C14.0938 3.50624 12.7188 2.16562 11 2.16562Z"
                                        fill=""
                                    />
                                    <path
                                        d="M17.7719 21.4156H4.2281C3.5406 21.4156 2.9906 20.8656 2.9906 20.1781V17.0844C2.9906 13.7156 5.7406 10.9656 9.10935 10.9656H12.8906C16.2594 10.9656 19.0094 13.7156 19.0094 17.0844V20.1781C19.0094 20.8656 18.4594 21.4156 17.7719 21.4156ZM4.53748 19.8687H17.4625V17.0844C17.4625 14.575 15.4 12.5125 12.8906 12.5125H9.10935C6.6 12.5125 4.53748 14.575 4.53748 17.0844V19.8687Z"
                                        fill=""
                                    />
                                </svg>
                                Edit profile
                            </a>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base"
                            >
                                <svg
                                    class="fill-current"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 22 22"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M17.6687 1.44374C17.1187 0.893744 16.4312 0.618744 15.675 0.618744H7.42498C6.25623 0.618744 5.25935 1.58124 5.25935 2.78437V4.12499H4.29685C3.88435 4.12499 3.50623 4.46874 3.50623 4.84687C3.50623 5.22499 3.84998 5.56874 4.29685 5.56874H5.25935V10.2781H4.29685C3.88435 10.2781 3.50623 10.6219 3.50623 11C3.50623 11.3781 3.84998 11.7219 4.29685 11.7219H5.25935V16.4312H4.29685C3.88435 16.4312 3.50623 16.775 3.50623 17.1531C3.50623 17.5312 3.84998 17.875 4.29685 17.875H5.25935V19.2156C5.25935 20.4187 6.22185 21.3812 7.42498 21.3812H15.675C17.2218 21.3812 18.4937 20.1437 18.4937 18.5969V3.40311C18.4937 2.64686 18.2187 1.95936 17.6687 1.44374ZM16.9469 18.5969C16.9469 19.2844 16.3625 19.8344 15.675 19.8344H7.42498C7.0031 19.8344 6.66873 19.5 6.66873 19.0781V17.875H8.6781C9.0906 17.875 9.46873 17.5312 9.46873 17.1531C9.46873 16.775 9.12498 16.4312 8.6781 16.4312H6.66873V11.7219H8.6781C9.0906 11.7219 9.46873 11.3781 9.46873 11C9.46873 10.6219 9.12498 10.2781 8.6781 10.2781H6.66873V5.56874H8.6781C9.0906 5.56874 9.46873 5.22499 9.46873 4.84687C9.46873 4.46874 9.12498 4.12499 8.6781 4.12499H6.66873V2.78437C6.66873 2.36249 7.00310 2.02812 7.42498 2.02812H15.675C16.0969 2.02812 16.4656 2.19374 16.7406 2.46874C17.0156 2.74374 17.1812 3.11249 17.1812 3.40311V18.5969H16.9469Z"
                                        fill=""
                                    />
                                </svg>
                                Account settings
                            </a>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base"
                            >
                                <svg
                                    class="fill-current"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 22 22"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M17.6687 1.44374C17.1187 0.893744 16.4312 0.618744 15.675 0.618744H7.42498C6.25623 0.618744 5.25935 1.58124 5.25935 2.78437V4.12499H4.29685C3.88435 4.12499 3.50623 4.46874 3.50623 4.84687C3.50623 5.22499 3.84998 5.56874 4.29685 5.56874H5.25935V10.2781H4.29685C3.88435 10.2781 3.50623 10.6219 3.50623 11C3.50623 11.3781 3.84998 11.7219 4.29685 11.7219H5.25935V16.4312H4.29685C3.88435 16.4312 3.50623 16.775 3.50623 17.1531C3.50623 17.5312 3.84998 17.875 4.29685 17.875H5.25935V19.2156C5.25935 20.4187 6.22185 21.3812 7.42498 21.3812H15.675C17.2218 21.3812 18.4937 20.1437 18.4937 18.5969V3.40311C18.4937 2.64686 18.2187 1.95936 17.6687 1.44374ZM16.9469 18.5969C16.9469 19.2844 16.3625 19.8344 15.675 19.8344H7.42498C7.0031 19.8344 6.66873 19.5 6.66873 19.0781V17.875H8.6781C9.0906 17.875 9.46873 17.5312 9.46873 17.1531C9.46873 16.775 9.12498 16.4312 8.6781 16.4312H6.66873V11.7219H8.6781C9.0906 11.7219 9.46873 11.3781 9.46873 11C9.46873 10.6219 9.12498 10.2781 8.6781 10.2781H6.66873V5.56874H8.6781C9.0906 5.56874 9.46873 5.22499 9.46873 4.84687C9.46873 4.46874 9.12498 4.12499 8.6781 4.12499H6.66873V2.78437C6.66873 2.36249 7.00310 2.02812 7.42498 2.02812H15.675C16.0969 2.02812 16.4656 2.19374 16.7406 2.46874C17.0156 2.74374 17.1812 3.11249 17.1812 3.40311V18.5969H16.9469Z"
                                        fill=""
                                    />
                                </svg>
                                Support
                            </a>
                        </li>
                    </ul>
                    <form action="{{ route('admin.logout') }}" method="POST" class="flex items-center gap-3.5 px-6 py-4 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base">
                        @csrf
                        <svg
                            class="fill-current"
                            width="22"
                            height="22"
                            viewBox="0 0 22 22"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M15.5375 0.618744H11.6531C10.7594 0.618744 10.0031 1.37499 10.0031 2.26874V4.64062C10.0031 5.05312 10.3469 5.39687 10.7594 5.39687C11.1719 5.39687 11.5156 5.05312 11.5156 4.64062V2.23437C11.5156 2.16562 11.5844 2.13124 11.6531 2.13124H15.5375C16.3625 2.13124 17.0156 2.78437 17.0156 3.60937V18.3906C17.0156 19.2156 16.3625 19.8687 15.5375 19.8687H11.6531C11.5844 19.8687 11.5156 19.8344 11.5156 19.7656V17.3594C11.5156 16.9469 11.1719 16.6031 10.7594 16.6031C10.3469 16.6031 10.0031 16.9469 10.0031 17.3594V19.7312C10.0031 20.625 10.7594 21.3812 11.6531 21.3812H15.5375C17.2219 21.3812 18.5281 20.075 18.5281 18.3906V3.60937C18.5281 1.925 17.2219 0.618744 15.5375 0.618744Z"
                                fill=""
                            />
                            <path
                                d="M6.05001 11.7563H12.2031C12.6156 11.7563 12.9594 11.4125 12.9594 11C12.9594 10.5875 12.6156 10.2438 12.2031 10.2438H6.08439L8.21564 8.07813C8.52501 7.76875 8.52501 7.2875 8.21564 6.97812C7.90626 6.66875 7.42501 6.66875 7.11564 6.97812L3.67814 10.4156C3.36876 10.725 3.36876 11.2063 3.67814 11.5156L7.11564 14.9531C7.27189 15.1094 7.45939 15.1875 7.64689 15.1875C7.83439 15.1875 8.02189 15.1094 8.17814 14.9531C8.48751 14.6438 8.48751 14.1625 8.17814 13.8531L6.05001 11.7563Z"
                                fill=""
                            />
                        </svg>
                        <button type="submit">Sign out</button>
                    </form>
                </div>
                <!-- Dropdown End -->
            </div>
            <!-- User Area -->
        </div>
    </div>
</header>
