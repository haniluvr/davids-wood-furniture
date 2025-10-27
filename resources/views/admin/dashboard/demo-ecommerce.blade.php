@extends('admin.layouts.demo-tailadmin')

@section('title', 'eCommerce Dashboard')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        eCommerce Dashboard
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li class="font-medium text-primary">eCommerce</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<!-- ====== Cards Section Start -->
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5">
    <!-- Customers Card -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <svg class="fill-primary dark:fill-white" width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.18418 8.03751C9.31543 8.03751 11.0686 6.35313 11.0686 4.25626C11.0686 2.15938 9.31543 0.475006 7.18418 0.475006C5.05293 0.475006 3.2998 2.15938 3.2998 4.25626C3.2998 6.35313 5.05293 8.03751 7.18418 8.03751ZM7.18418 2.05626C8.45605 2.05626 9.52168 3.05313 9.52168 4.29063C9.52168 5.52813 8.49043 6.52501 7.18418 6.52501C5.87793 6.52501 4.84668 5.52813 4.84668 4.29063C4.84668 3.05313 5.9123 2.05626 7.18418 2.05626Z" fill=""/>
                <path d="M15.8124 9.6875C17.6687 9.6875 19.1468 8.24375 19.1468 6.42188C19.1468 4.6 17.6343 3.15625 15.8124 3.15625C13.9905 3.15625 12.478 4.6 12.478 6.42188C12.478 8.24375 13.9905 9.6875 15.8124 9.6875ZM15.8124 4.7375C16.8093 4.7375 17.5999 5.49375 17.5999 6.45625C17.5999 7.41875 16.8093 8.175 15.8124 8.175C14.8155 8.175 14.0249 7.41875 14.0249 6.45625C14.0249 5.49375 14.8155 4.7375 15.8124 4.7375Z" fill=""/>
                <path d="M15.9843 10.0313H15.6749C14.6437 10.0313 13.6468 10.3906 12.7781 11.0781C11.2312 9.32812 8.93431 8.21875 6.35681 8.21875H7.92431C5.42431 8.25 3.28431 10.4219 3.28431 12.9844V16.3125C3.28431 16.6719 3.59056 16.9781 3.94994 16.9781C4.30931 16.9781 4.61556 16.6719 4.61556 16.3125V12.9844C4.61556 11.2031 6.07806 9.78125 7.92431 9.78125H15.2624C16.0343 9.78125 16.6999 10.4469 16.6999 11.2188V15.125C16.6999 15.4844 17.0062 15.7906 17.3656 15.7906C17.7249 15.7906 18.0312 15.4844 18.0312 15.125V11.2188C18.0312 9.78125 16.8093 8.625 15.2624 8.625H15.9843V10.0313Z" fill=""/>
            </svg>
        </div>

        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    3,782
                </h4>
                <span class="text-sm font-medium">Customers</span>
            </div>

            <span class="flex items-center gap-1 text-sm font-medium text-meta-3">
                11.01%
                <svg class="fill-meta-3" width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z" fill=""/>
                </svg>
            </span>
        </div>
    </div>

    <!-- Orders Card -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <svg class="fill-primary dark:fill-white" width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.7531 16.4312C10.3781 16.4312 9.27808 17.5312 9.27808 18.9062C9.27808 20.2812 10.3781 21.3812 11.7531 21.3812C13.1281 21.3812 14.2281 20.2812 14.2281 18.9062C14.2281 17.5656 13.1281 16.4312 11.7531 16.4312ZM11.7531 19.8687C11.2375 19.8687 10.825 19.4562 10.825 18.9406C10.825 18.425 11.2375 18.0125 11.7531 18.0125C12.2687 18.0125 12.6812 18.425 12.6812 18.9406C12.6812 19.4562 12.2343 19.8687 11.7531 19.8687Z" fill=""/>
                <path d="M5.22183 16.4312C3.84683 16.4312 2.74683 17.5312 2.74683 18.9062C2.74683 20.2812 3.84683 21.3812 5.22183 21.3812C6.59683 21.3812 7.69683 20.2812 7.69683 18.9062C7.69683 17.5656 6.59683 16.4312 5.22183 16.4312ZM5.22183 19.8687C4.7062 19.8687 4.2937 19.4562 4.2937 18.9406C4.2937 18.425 4.7062 18.0125 5.22183 18.0125C5.73745 18.0125 6.14995 18.425 6.14995 18.9406C6.14995 19.4562 5.73745 19.8687 5.22183 19.8687Z" fill=""/>
                <path d="M19.0062 0.618744H17.15C16.325 0.618744 15.6031 1.23749 15.5 2.06249L14.95 6.01562H2.37185C1.51873 6.01562 0.837477 6.6625 0.837477 7.51562C0.837477 7.83749 0.906227 8.15937 1.07810 8.44687L4.29685 14.1969C4.87185 15.1156 5.93123 15.6312 7.02498 15.6312H14.95C15.8031 15.6312 16.5562 15.0812 16.7281 14.2281L19.3687 2.78124C19.4375 2.49374 19.2656 2.20624 18.9781 2.13749C18.6906 2.06874 18.4031 2.24062 18.3344 2.52812L15.6937 13.9750C15.6594 14.1469 15.5219 14.2844 15.35 14.2844H7.02498C6.55935 14.2844 6.12810 14.0312 5.90623 13.6406L2.68748 7.89062C2.61873 7.78437 2.61873 7.64687 2.68748 7.54062C2.75623 7.43437 2.89373 7.36562 3.03123 7.36562H15.2844C15.5719 7.36562 15.8594 7.11249 15.9281 6.82499L16.5406 2.49374C16.5406 2.28749 16.7125 2.11562 16.9187 2.11562H18.7750C19.0625 2.11562 19.35 1.82812 19.35 1.54062C19.35 1.25312 19.0625 0.618744 19.0062 0.618744Z" fill=""/>
            </svg>
        </div>

        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    5,359
                </h4>
                <span class="text-sm font-medium">Orders</span>
            </div>

            <span class="flex items-center gap-1 text-sm font-medium text-meta-1">
                9.05%
                <svg class="fill-meta-1" width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.64284 7.69237L9.09102 4.33987L10 5.22362L5 10.0849L-8.98488e-07 5.22362L0.908973 4.33987L4.35716 7.69237L4.35716 0.0848701L5.64284 0.0848701L5.64284 7.69237Z" fill=""/>
                </svg>
            </span>
        </div>
    </div>

    <!-- Monthly Target Card -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark xl:col-span-2">
        <div class="flex items-start justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white mb-2">
                    Monthly Target
                </h4>
                <p class="text-sm font-medium mb-4">Target you've set for each month</p>
                
                <div class="flex items-center mb-4">
                    <span class="text-meta-3 text-sm mr-2">+10%</span>
                    <p class="text-sm">You earn ₱3287 today, it's higher than last month. Keep up your good work!</p>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Target</p>
                        <p class="text-lg font-bold text-black dark:text-white">₱20K</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Revenue</p>
                        <p class="text-lg font-bold text-black dark:text-white">₱20K</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Today</p>
                        <p class="text-lg font-bold text-black dark:text-white">₱20K</p>
                    </div>
                </div>
            </div>

            <div class="relative h-32 w-32">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">75.55%</div>
                        <div class="text-xs text-meta-3">+10%</div>
                    </div>
                </div>
                <svg class="h-32 w-32 transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-gray-200" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                    <path class="text-primary" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="75.55, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                </svg>
            </div>
        </div>
    </div>
</div>
<!-- ====== Cards Section End -->

<div class="mt-4 grid grid-cols-12 gap-4 md:mt-6 md:gap-6 2xl:mt-7.5 2xl:gap-7.5">
    <!-- ====== Monthly Sales Chart Start -->
    <div class="col-span-12 xl:col-span-8">
        <div class="rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">
            <div class="flex flex-wrap items-start justify-between gap-3 sm:flex-nowrap">
                <div class="flex w-full flex-wrap gap-3 sm:gap-5">
                    <div class="flex min-w-47.5">
                        <div class="w-full">
                            <p class="font-semibold text-primary text-xl">Monthly Sales</p>
                        </div>
                    </div>
                </div>
                <div class="flex w-full max-w-45 justify-end">
                    <div class="inline-flex items-center rounded-md bg-whiter p-1.5 dark:bg-meta-4">
                        <button class="rounded bg-white px-3 py-1 text-xs font-medium text-black shadow-card hover:bg-white hover:shadow-card dark:bg-boxdark dark:text-white dark:hover:bg-boxdark">
                            Day
                        </button>
                        <button class="rounded px-3 py-1 text-xs font-medium text-black hover:bg-white hover:shadow-card dark:text-white dark:hover:bg-boxdark">
                            Week
                        </button>
                        <button class="rounded px-3 py-1 text-xs font-medium text-black hover:bg-white hover:shadow-card dark:text-white dark:hover:bg-boxdark">
                            Month
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <div id="chartOne" class="-ml-5 h-[350px]"></div>
            </div>
        </div>
    </div>
    <!-- ====== Monthly Sales Chart End -->

    <!-- ====== Statistics Chart Start -->
    <div class="col-span-12 xl:col-span-4">
        <div class="rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">
            <div class="mb-3 justify-between gap-4 sm:flex">
                <div>
                    <h5 class="text-xl font-semibold text-black dark:text-white">
                        Statistics
                    </h5>
                    <p class="text-sm font-medium">Target you've set for each month</p>
                </div>
            </div>

            <div class="mb-2">
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm font-medium text-black dark:text-white">Overview</span>
                    <span class="text-sm font-medium text-black dark:text-white">Sales</span>
                    <span class="text-sm font-medium text-black dark:text-white">Revenue</span>
                </div>
            </div>

            <div>
                <div id="chartTwo" class="mx-auto flex justify-center h-[300px]"></div>
            </div>
        </div>
    </div>
    <!-- ====== Statistics Chart End -->

    <!-- ====== Customer Demographics Start -->
    <div class="col-span-12 xl:col-span-6">
        <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="mb-4 justify-between gap-4 sm:flex">
                <div>
                    <h5 class="text-xl font-semibold text-black dark:text-white">
                        Customers Demographic
                    </h5>
                    <p class="text-sm font-medium">Number of customer based on country</p>
                </div>
            </div>

            <!-- World Map -->
            <div class="mb-6">
                <div id="mapOne" class="mapOne map-btn h-80 bg-gray-100 rounded flex items-center justify-center">
                    <svg width="100%" height="100%" viewBox="0 0 800 400" class="text-gray-300">
                        <!-- Simplified world map SVG -->
                        <g fill="currentColor" stroke="#fff" stroke-width="0.5">
                            <!-- USA -->
                            <path d="M158 206c-1-3-4-3-6-1-2 1-4 0-5-2-1-1-3-1-4 1-2 2-4 1-6 0-1-1-3 0-3 2 0 2-2 3-4 2-2-1-4 1-3 3 1 2-1 4-3 3-2-1-4 0-4 2 0 3-3 4-5 2-2-2-5-1-6 2-1 2-4 2-5 0-2-2-5-1-6 1-1 3-4 3-6 1-2-2-5-1-6 1-1 2-3 1-4-1-1-2-4-2-5 0-2 2-5 1-6-1-1-3-4-2-5 1-1 2-3 1-4-1-1-2-4-1-4 1 0 3-3 4-5 2-2-2-5-1-6 1-1 3-4 3-6 1-2-2-5-1-6 1-1 2-3 1-4-1-1-2-4-1-4 1 0 2-2 1-3-1-1-2-4-1-4 1 0 3-3 3-5 1-2-2-5-1-6 1-1 2-3 1-4-1z" fill="#3C50E0"/>
                            <!-- Europe -->
                            <path d="M400 150c2-1 4 0 5 2 1 2 4 2 5 0 2-2 5-1 6 1 1 3 4 3 6 1 2-2 5-1 6 1 1 2 3 1 4-1 1-2 4-2 5 0 2 2 5 1 6-1 1-3 4-2 5 1 1 2 3 1 4-1 1-2 4-1 4 1 0 2 2 1 3-1 1-2 4-1 4 1 0 3 3 3 5 1 2-2 5-1 6 1 1 2 3 1 4-1z" fill="#80CAEE"/>
                            <!-- Asia -->
                            <path d="M500 120c3-1 6 1 7 4 1 3 5 4 7 1 2-3 6-2 8 1 2 4 6 4 8 0 3-4 7-3 9 1 2 3 5 2 7-1 2-3 6-2 7 1 1 4 5 5 8 2 3-3 7-1 8 2 1 4 5 4 7 0 3-4 7-3 9 1 2 3 5 2 7-1 2-3 6-2 7 1 1 3 4 2 6-1 2-3 6-2 7 1 1 4 5 4 7 0z" fill="#10B981"/>
                        </g>
                        <!-- Country markers -->
                        <circle cx="200" cy="180" r="4" fill="#3C50E0" class="animate-pulse"/>
                        <circle cx="420" cy="140" r="3" fill="#80CAEE" class="animate-pulse"/>
                        <circle cx="550" cy="160" r="3" fill="#10B981" class="animate-pulse"/>
                    </svg>
                </div>
            </div>

            <!-- Country Statistics -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="mr-3 h-4 w-6 bg-primary rounded"></div>
                        <span class="font-medium text-black dark:text-white">USA</span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-4 text-sm font-medium text-black dark:text-white">2,379 Customers</span>
                        <span class="text-sm font-medium text-primary">79%</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="mr-3 h-4 w-6 bg-secondary rounded"></div>
                        <span class="font-medium text-black dark:text-white">France</span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-4 text-sm font-medium text-black dark:text-white">589 Customers</span>
                        <span class="text-sm font-medium text-secondary">23%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ====== Customer Demographics End -->

    <!-- ====== Recent Orders Start -->
    <div class="col-span-12 xl:col-span-6">
        <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h4 class="text-xl font-semibold text-black dark:text-white">
                        Recent Orders
                    </h4>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="inline-flex items-center justify-center rounded-md border border-stroke px-3 py-1 text-center text-sm font-medium text-black hover:bg-opacity-90 dark:border-strokedark dark:text-white">
                        Filter
                    </button>
                    <button class="inline-flex items-center justify-center rounded-md bg-primary px-3 py-1 text-center text-sm font-medium text-white hover:bg-opacity-90">
                        See all
                    </button>
                </div>
            </div>

            <div class="flex flex-col">
                <div class="grid grid-cols-4 rounded-sm bg-gray-2 dark:bg-meta-4">
                    <div class="p-2.5 xl:p-4">
                        <h5 class="text-sm font-medium uppercase">Products</h5>
                    </div>
                    <div class="p-2.5 text-center xl:p-4">
                        <h5 class="text-sm font-medium uppercase">Category</h5>
                    </div>
                    <div class="p-2.5 text-center xl:p-4">
                        <h5 class="text-sm font-medium uppercase">Price</h5>
                    </div>
                    <div class="p-2.5 text-center xl:p-4">
                        <h5 class="text-sm font-medium uppercase">Status</h5>
                    </div>
                </div>

                <div class="grid grid-cols-4 border-b border-stroke dark:border-strokedark">
                    <div class="flex items-center gap-3 p-2.5 xl:p-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded object-cover" src="https://via.placeholder.com/40x40/8B5CF6/FFFFFF?text=MB" alt="Product" />
                        </div>
                        <div class="flex flex-col">
                            <p class="text-black dark:text-white font-medium text-sm">Macbook pro 13"</p>
                            <p class="text-xs text-gray-500">2 Variants</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <p class="text-black dark:text-white text-sm">Laptop</p>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <p class="text-black dark:text-white font-medium text-sm">₱2399.00</p>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <span class="inline-flex rounded-full bg-success bg-opacity-10 px-2 py-1 text-xs font-medium text-success">Delivered</span>
                    </div>
                </div>

                <div class="grid grid-cols-4 border-b border-stroke dark:border-strokedark">
                    <div class="flex items-center gap-3 p-2.5 xl:p-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded object-cover" src="https://via.placeholder.com/40x40/F97316/FFFFFF?text=AW" alt="Product" />
                        </div>
                        <div class="flex flex-col">
                            <p class="text-black dark:text-white font-medium text-sm">Apple Watch Ultra</p>
                            <p class="text-xs text-gray-500">1 Variants</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <p class="text-black dark:text-white text-sm">Watch</p>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <p class="text-black dark:text-white font-medium text-sm">₱879.00</p>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <span class="inline-flex rounded-full bg-warning bg-opacity-10 px-2 py-1 text-xs font-medium text-warning">Pending</span>
                    </div>
                </div>

                <div class="grid grid-cols-4 border-b border-stroke dark:border-strokedark">
                    <div class="flex items-center gap-3 p-2.5 xl:p-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded object-cover" src="https://via.placeholder.com/40x40/10B981/FFFFFF?text=IP" alt="Product" />
                        </div>
                        <div class="flex flex-col">
                            <p class="text-black dark:text-white font-medium text-sm">iPhone 15 Pro Max</p>
                            <p class="text-xs text-gray-500">2 Variants</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <p class="text-black dark:text-white text-sm">SmartPhone</p>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <p class="text-black dark:text-white font-medium text-sm">₱1869.00</p>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <span class="inline-flex rounded-full bg-success bg-opacity-10 px-2 py-1 text-xs font-medium text-success">Delivered</span>
                    </div>
                </div>

                <div class="grid grid-cols-4 border-b border-stroke dark:border-strokedark">
                    <div class="flex items-center gap-3 p-2.5 xl:p-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded object-cover" src="https://via.placeholder.com/40x40/EF4444/FFFFFF?text=IP" alt="Product" />
                        </div>
                        <div class="flex flex-col">
                            <p class="text-black dark:text-white font-medium text-sm">iPad Pro 3rd Gen</p>
                            <p class="text-xs text-gray-500">2 Variants</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <p class="text-black dark:text-white text-sm">Electronics</p>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <p class="text-black dark:text-white font-medium text-sm">₱1699.00</p>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <span class="inline-flex rounded-full bg-danger bg-opacity-10 px-2 py-1 text-xs font-medium text-danger">Canceled</span>
                    </div>
                </div>

                <div class="grid grid-cols-4">
                    <div class="flex items-center gap-3 p-2.5 xl:p-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded object-cover" src="https://via.placeholder.com/40x40/6B7280/FFFFFF?text=AP" alt="Product" />
                        </div>
                        <div class="flex flex-col">
                            <p class="text-black dark:text-white font-medium text-sm">Airpods Pro 2nd Gen</p>
                            <p class="text-xs text-gray-500">1 Variants</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <p class="text-black dark:text-white text-sm">Accessories</p>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <p class="text-black dark:text-white font-medium text-sm">₱240.00</p>
                    </div>
                    <div class="flex items-center justify-center p-2.5 xl:p-4">
                        <span class="inline-flex rounded-full bg-success bg-opacity-10 px-2 py-1 text-xs font-medium text-success">Delivered</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ====== Recent Orders End -->
</div>

@endsection

@push('scripts')
<script>
// Monthly Sales Bar Chart
const chartOneOptions = {
    series: [{
        name: 'Sales',
        data: [150, 350, 200, 300, 180, 200, 300, 100, 200, 350, 280, 100]
    }],
    chart: {
        fontFamily: 'Inter, sans-serif',
        height: 350,
        type: 'bar',
        toolbar: {
            show: false,
        },
    },
    colors: ['#3C50E0'],
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded',
            borderRadius: 4,
        },
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
    },
    yaxis: {
        title: {
            style: {
                fontSize: '0px',
            },
        },
        min: 0,
        max: 400,
    },
    fill: {
        opacity: 1
    },
    grid: {
        yaxis: {
            lines: {
                show: true,
            },
        },
    },
};

const chartOne = new ApexCharts(document.querySelector('#chartOne'), chartOneOptions);
chartOne.render();

// Statistics Area Chart
const chartTwoOptions = {
    series: [
        {
            name: 'Overview',
            data: [180, 190, 175, 185, 170, 180, 190, 200, 210, 220, 230, 240]
        },
        {
            name: 'Sales', 
            data: [50, 45, 55, 50, 60, 55, 50, 45, 50, 55, 60, 65]
        }
    ],
    chart: {
        fontFamily: 'Inter, sans-serif',
        height: 300,
        type: 'area',
        toolbar: {
            show: false,
        },
    },
    colors: ['#3C50E0', '#80CAEE'],
    dataLabels: {
        enabled: false,
    },
    stroke: {
        curve: 'smooth',
        width: 2,
    },
    fill: {
        type: 'gradient',
        gradient: {
            opacityFrom: 0.4,
            opacityTo: 0.1,
        },
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
    },
    yaxis: {
        title: {
            style: {
                fontSize: '0px',
            },
        },
        min: 0,
        max: 250,
    },
    grid: {
        yaxis: {
            lines: {
                show: true,
            },
        },
    },
    legend: {
        show: false,
    },
};

const chartTwo = new ApexCharts(document.querySelector('#chartTwo'), chartTwoOptions);
chartTwo.render();
</script>
@endpush