@extends('admin.layouts.tailadmin')

@section('title', 'eCommerce Dashboard')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
    <!-- Metric Cards Row -->
    <div class="col-span-12 space-y-6 xl:col-span-7">
        <!-- Metric Group -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4">
            <!-- Total Products -->
            <div class="rounded-sm border border-gray-200 bg-white px-7.5 py-6 shadow-default dark:border-gray-700 dark:bg-gray-800">
                <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                    <svg class="fill-primary dark:fill-white" width="22" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 15.1156C4.19376 15.1156 0.825012 8.61876 0.687512 8.34376C0.584387 8.13751 0.584387 7.86251 0.687512 7.65626C0.825012 7.38126 4.19376 0.918762 11 0.918762C17.8063 0.918762 21.175 7.38126 21.3125 7.65626C21.4156 7.86251 21.4156 8.13751 21.3125 8.34376C21.175 8.61876 17.8063 15.1156 11 15.1156ZM2.26876 8.00001C3.02501 9.27189 5.98126 13.5688 11 13.5688C16.0188 13.5688 18.975 9.27189 19.7313 8.00001C18.975 6.72814 16.0188 2.43126 11 2.43126C5.98126 2.43126 3.02501 6.72814 2.26876 8.00001Z"/>
                        <path d="M11 10.9219C9.38438 10.9219 8.07812 9.61562 8.07812 8C8.07812 6.38438 9.38438 5.07812 11 5.07812C12.6156 5.07812 13.9219 6.38438 13.9219 8C13.9219 9.61562 12.6156 10.9219 11 10.9219ZM11 6.625C10.2437 6.625 9.625 7.24375 9.625 8C9.625 8.75625 10.2437 9.375 11 9.375C11.7563 9.375 12.375 8.75625 12.375 8C12.375 7.24375 11.7563 6.625 11 6.625Z"/>
                    </svg>
                </div>

                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <h4 class="text-title-md font-bold text-black dark:text-white">{{ number_format($stats['total_products']) }}</h4>
                        <span class="text-sm font-medium">Total Products</span>
                    </div>

                    <span class="flex items-center gap-1 text-sm font-medium text-green-500">
                        0.43%
                        <svg class="fill-green-500" width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="rounded-sm border border-gray-200 bg-white px-7.5 py-6 shadow-default dark:border-gray-700 dark:bg-gray-800">
                <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                    <svg class="fill-primary dark:fill-white" width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.7531 16.4312C10.3781 16.4312 9.27808 17.5312 9.27808 18.9062C9.27808 20.2812 10.3781 21.3812 11.7531 21.3812C13.1281 21.3812 14.2281 20.2812 14.2281 18.9062C14.2281 17.5656 13.1281 16.4312 11.7531 16.4312ZM11.7531 19.8687C11.2375 19.8687 10.825 19.4562 10.825 18.9406C10.825 18.425 11.2375 18.0125 11.7531 18.0125C12.2687 18.0125 12.6812 18.425 12.6812 18.9406C12.6812 19.4562 12.2343 19.8687 11.7531 19.8687Z"/>
                        <path d="M5.22183 16.4312C3.84683 16.4312 2.74683 17.5312 2.74683 18.9062C2.74683 20.2812 3.84683 21.3812 5.22183 21.3812C6.59683 21.3812 7.69683 20.2812 7.69683 18.9062C7.69683 17.5656 6.59683 16.4312 5.22183 16.4312ZM5.22183 19.8687C4.7062 19.8687 4.2937 19.4562 4.2937 18.9406C4.2937 18.425 4.7062 18.0125 5.22183 18.0125C5.73745 18.0125 6.14995 18.425 6.14995 18.9406C6.14995 19.4562 5.70308 19.8687 5.22183 19.8687Z"/>
                        <path d="M19.0062 0.618744H17.15C16.325 0.618744 15.6031 1.23749 15.5 2.06249L14.95 6.01562H1.37185C1.0281 6.01562 0.684353 6.18749 0.478103 6.46249C0.271853 6.73749 0.237478 7.11562 0.378103 7.45937L2.45623 11.9375C2.68435 12.4187 3.1656 12.725 3.68123 12.725H12.8656L12.1656 15.2562H14.1219L15.2219 10.4187C15.325 9.59374 14.7062 8.97499 13.8812 8.97499H4.9906L3.3781 6.01562H14.2219L14.8219 1.82812C14.8219 1.82812 14.8219 1.82812 14.8219 1.79374C14.8562 1.58749 15.0281 1.45312 15.2344 1.45312H19.0062C19.4187 1.45312 19.7281 1.14374 19.7281 0.731244C19.7281 0.353119 19.4187 0.618744 19.0062 0.618744Z"/>
                    </svg>
                </div>

                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <h4 class="text-title-md font-bold text-black dark:text-white">{{ number_format($stats['total_orders']) }}</h4>
                        <span class="text-sm font-medium">Total Orders</span>
                    </div>

                    <span class="flex items-center gap-1 text-sm font-medium text-green-500">
                        4.35%
                        <svg class="fill-green-500" width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="rounded-sm border border-gray-200 bg-white px-7.5 py-6 shadow-default dark:border-gray-700 dark:bg-gray-800">
                <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                    <svg class="fill-primary dark:fill-white" width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.18418 8.03751C9.31543 8.03751 11.0686 6.35313 11.0686 4.25626C11.0686 2.15938 9.31543 0.475006 7.18418 0.475006C5.05293 0.475006 3.2998 2.15938 3.2998 4.25626C3.2998 6.35313 5.05293 8.03751 7.18418 8.03751ZM7.18418 2.05626C8.45605 2.05626 9.52168 3.05313 9.52168 4.29063C9.52168 5.52813 8.49043 6.52501 7.18418 6.52501C5.87793 6.52501 4.84668 5.52813 4.84668 4.29063C4.84668 3.05313 5.9123 2.05626 7.18418 2.05626Z"/>
                        <path d="M15.8124 9.6875C17.6687 9.6875 19.1468 8.24375 19.1468 6.42188C19.1468 4.6 17.6343 3.15625 15.8124 3.15625C13.9905 3.15625 12.478 4.6 12.478 6.42188C12.478 8.24375 13.9905 9.6875 15.8124 9.6875ZM15.8124 4.7375C16.8093 4.7375 17.5999 5.49375 17.5999 6.45625C17.5999 7.41875 16.8093 8.175 15.8124 8.175C14.8155 8.175 14.0249 7.41875 14.0249 6.45625C14.0249 5.49375 14.8155 4.7375 15.8124 4.7375Z"/>
                        <path d="M15.9843 10.0313H15.6749C14.6437 10.0313 13.6468 10.3406 12.7781 10.8563C11.8593 9.61876 10.3812 8.79376 8.73115 8.79376H5.67178C2.85303 8.82814 0.618652 11.0625 0.618652 13.8469V16.3219C0.618652 17.0313 1.13428 17.5469 1.8999 17.5469H20.2124C20.9781 17.5469 21.4937 17.0313 21.4937 16.3219V15.4531C21.4937 12.8406 19.3499 10.0313 15.9843 10.0313ZM2.16553 15.9656V13.8469C2.16553 11.9219 3.74678 10.3406 5.67178 10.3406H8.73115C10.6562 10.3406 12.2374 11.9219 12.2374 13.8469V15.9656H2.16553ZM19.8687 15.9656H13.7499V13.8469C13.7499 13.2969 13.6468 12.7813 13.4749 12.2969C14.0937 11.7813 14.8499 11.5781 15.6405 11.5781H15.9499C18.0812 11.5781 19.8343 13.3313 19.8343 15.4625V15.9656H19.8687Z"/>
                    </svg>
                </div>

                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <h4 class="text-title-md font-bold text-black dark:text-white">{{ number_format($stats['total_customers']) }}</h4>
                        <span class="text-sm font-medium">Total Customers</span>
                    </div>

                    <span class="flex items-center gap-1 text-sm font-medium text-green-500">
                        2.59%
                        <svg class="fill-green-500" width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="rounded-sm border border-gray-200 bg-white px-7.5 py-6 shadow-default dark:border-gray-700 dark:bg-gray-800">
                <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                    <svg class="fill-primary dark:fill-white" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.1063 18.0469L19.3875 3.23126C19.2157 1.71876 17.9438 0.584381 16.3969 0.584381H5.56878C4.05628 0.584381 2.78441 1.71876 2.57816 3.23126L0.859406 18.0469C0.756281 18.9063 1.03128 19.7313 1.61566 20.3844C2.20003 21.0375 2.99066 21.3813 3.85003 21.3813H18.1157C18.975 21.3813 19.8 21.0031 20.35 20.3844C20.9344 19.7313 21.2094 18.9063 21.1063 18.0469ZM19.2157 19.3531C18.9407 19.6625 18.5625 19.8344 18.15 19.8344H3.85003C3.43753 19.8344 3.05941 19.6625 2.78441 19.3531C2.50941 19.0438 2.37503 18.6313 2.44066 18.2188L4.12503 3.43751C4.19066 2.71563 4.81253 2.16563 5.56878 2.16563H16.4313C17.1875 2.16563 17.8094 2.71563 17.875 3.43751L19.5594 18.2188C19.6594 18.6313 19.4906 19.0438 19.2157 19.3531Z"/>
                        <path d="M14.3345 5.29375C13.922 5.39688 13.647 5.80938 13.7501 6.22188C13.7845 6.42813 13.8189 6.63438 13.8189 6.80625C13.8189 8.35313 12.547 9.625 11.0001 9.625C9.45327 9.625 8.18140 8.35313 8.18140 6.80625C8.18140 6.6 8.21577 6.42813 8.25015 6.22188C8.35327 5.80938 8.07827 5.39688 7.66577 5.29375C7.25327 5.19063 6.84077 5.46563 6.73765 5.87813C6.6689 6.1875 6.63452 6.49688 6.63452 6.80625C6.63452 9.2125 8.59390 11.1719 11.0001 11.1719C13.4064 11.1719 15.3658 9.2125 15.3658 6.80625C15.3658 6.49688 15.3314 6.1875 15.2626 5.87813C15.1595 5.46563 14.747 5.225 14.3345 5.29375Z"/>
                    </svg>
                </div>

                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <h4 class="text-title-md font-bold text-black dark:text-white">${{ number_format($stats['total_revenue'], 2) }}</h4>
                        <span class="text-sm font-medium">Total Revenue</span>
                    </div>

                    <span class="flex items-center gap-1 text-sm font-medium text-green-500">
                        18.7%
                        <svg class="fill-green-500" width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z"/>
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <!-- Chart One -->
        <div class="rounded-sm border border-gray-200 bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-gray-700 dark:bg-gray-800 sm:px-7.5">
            <div class="flex flex-wrap items-start justify-between gap-3 sm:flex-nowrap">
                <div class="flex w-full flex-wrap gap-3 sm:gap-5">
                    <div class="flex min-w-47.5">
                        <span class="mr-2 mt-1 flex h-4 w-full max-w-4 items-center justify-center rounded-full border border-primary">
                            <span class="block h-2.5 w-full max-w-2.5 rounded-full bg-primary"></span>
                        </span>
                        <div class="w-full">
                            <p class="font-semibold text-primary">Total Revenue</p>
                            <p class="text-sm font-medium">12.04.2022 - 12.05.2022</p>
                        </div>
                    </div>
                    <div class="flex min-w-47.5">
                        <span class="mr-2 mt-1 flex h-4 w-full max-w-4 items-center justify-center rounded-full border border-secondary">
                            <span class="block h-2.5 w-full max-w-2.5 rounded-full bg-secondary"></span>
                        </span>
                        <div class="w-full">
                            <p class="font-semibold text-secondary">Total Sales</p>
                            <p class="text-sm font-medium">12.04.2022 - 12.05.2022</p>
                        </div>
                    </div>
                </div>
                <div class="flex w-full max-w-45 justify-end">
                    <div class="inline-flex items-center rounded-md bg-whiter p-1.5 dark:bg-gray-700">
                        <button class="rounded bg-white px-3 py-1 text-xs font-medium text-black shadow-card hover:bg-white hover:shadow-card dark:bg-gray-800 dark:text-white dark:hover:bg-gray-800">
                            Day
                        </button>
                        <button class="rounded px-3 py-1 text-xs font-medium text-black hover:bg-white hover:shadow-card dark:text-white dark:hover:bg-gray-800">
                            Week
                        </button>
                        <button class="rounded px-3 py-1 text-xs font-medium text-black hover:bg-white hover:shadow-card dark:text-white dark:hover:bg-gray-800">
                            Month
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <div id="chartOne" class="-ml-5"></div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-span-12 xl:col-span-5">
        <!-- Chart Two -->
        <div class="rounded-sm border border-gray-200 bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-gray-700 dark:bg-gray-800 sm:px-7.5">
            <div class="mb-3 justify-between gap-4 sm:flex">
                <div>
                    <h5 class="text-xl font-semibold text-black dark:text-white">
                        Visitors Analytics
                    </h5>
                </div>
                <div>
                    <div class="relative z-20 inline-block">
                        <select name="" id="" class="relative z-20 inline-flex appearance-none bg-transparent py-1 pl-3 pr-8 text-sm font-medium outline-none">
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                        <span class="absolute right-3 top-1/2 z-10 -translate-y-1/2">
                            <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.47072 1.08816C0.47072 1.02932 0.500141 0.955772 0.54427 0.911642C0.647241 0.808672 0.809051 0.808672 0.912022 0.896932L4.85431 4.60386C4.92785 4.67741 5.15785 4.67741 5.23140 4.60386L9.17369 0.896932C9.27666 0.793962 9.43847 0.808672 9.54144 0.911642C9.6444 1.01461 9.6444 1.17642 9.54144 1.27939L5.50140 5.08816C5.22785 5.36171 4.80785 5.36171 4.53431 5.08816L0.47072 1.08816C0.50014 1.05874 0.47072 0.998901 0.47072 1.08816Z" fill="#637381"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <div id="chartTwo" class="mx-auto flex justify-center"></div>
            </div>

            <div class="-mx-8 flex flex-wrap items-center justify-center gap-y-3">
                <div class="w-full px-8 sm:w-1/2">
                    <div class="flex w-full items-center">
                        <span class="mr-2 block h-3 w-full max-w-3 rounded-full bg-primary"></span>
                        <p class="flex w-full justify-between text-sm font-medium text-black dark:text-white">
                            <span> Desktop </span>
                            <span> 65% </span>
                        </p>
                    </div>
                </div>
                <div class="w-full px-8 sm:w-1/2">
                    <div class="flex w-full items-center">
                        <span class="mr-2 block h-3 w-full max-w-3 rounded-full bg-[#6577F3]"></span>
                        <p class="flex w-full justify-between text-sm font-medium text-black dark:text-white">
                            <span> Tablet </span>
                            <span> 34% </span>
                        </p>
                    </div>
                </div>
                <div class="w-full px-8 sm:w-1/2">
                    <div class="flex w-full items-center">
                        <span class="mr-2 block h-3 w-full max-w-3 rounded-full bg-[#8FD0EF]"></span>
                        <p class="flex w-full justify-between text-sm font-medium text-black dark:text-white">
                            <span> Mobile </span>
                            <span> 45% </span>
                        </p>
                    </div>
                </div>
                <div class="w-full px-8 sm:w-1/2">
                    <div class="flex w-full items-center">
                        <span class="mr-2 block h-3 w-full max-w-3 rounded-full bg-[#0FADCF]"></span>
                        <p class="flex w-full justify-between text-sm font-medium text-black dark:text-white">
                            <span> Unknown </span>
                            <span> 12% </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="col-span-12">
        <div class="rounded-sm border border-gray-200 bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-gray-700 dark:bg-gray-800 sm:px-7.5 xl:pb-1">
            <h4 class="mb-6 text-xl font-semibold text-black dark:text-white">
                Top Products
            </h4>

            <div class="flex flex-col">
                <div class="grid grid-cols-3 rounded-sm bg-gray-100 dark:bg-gray-700 sm:grid-cols-5">
                    <div class="p-2.5 xl:p-5">
                        <h5 class="text-sm font-medium uppercase xsm:text-base">
                            Product Name
                        </h5>
                    </div>
                    <div class="p-2.5 text-center xl:p-5">
                        <h5 class="text-sm font-medium uppercase xsm:text-base">
                            Category
                        </h5>
                    </div>
                    <div class="p-2.5 text-center xl:p-5">
                        <h5 class="text-sm font-medium uppercase xsm:text-base">
                            Price
                        </h5>
                    </div>
                    <div class="hidden p-2.5 text-center sm:block xl:p-5">
                        <h5 class="text-sm font-medium uppercase xsm:text-base">
                            Stock
                        </h5>
                    </div>
                    <div class="hidden p-2.5 text-center sm:block xl:p-5">
                        <h5 class="text-sm font-medium uppercase xsm:text-base">
                            Status
                        </h5>
                    </div>
                </div>

                @foreach($top_products->take(5) as $product)
                <div class="grid grid-cols-3 border-b border-gray-200 dark:border-gray-700 sm:grid-cols-5">
                    <div class="flex items-center gap-3 p-2.5 xl:p-5">
                        <div class="flex-shrink-0">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded object-cover" />
                            @else
                                <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-xs">📦</span>
                                </div>
                            @endif
                        </div>
                        <p class="hidden text-black dark:text-white sm:block">
                            {{ $product->name }}
                        </p>
                    </div>

                    <div class="flex items-center justify-center p-2.5 xl:p-5">
                        <p class="text-black dark:text-white">{{ $product->category->name ?? 'N/A' }}</p>
                    </div>

                    <div class="flex items-center justify-center p-2.5 xl:p-5">
                        <p class="text-green-500">${{ number_format($product->current_price, 2) }}</p>
                    </div>

                    <div class="hidden items-center justify-center p-2.5 sm:flex xl:p-5">
                        <p class="text-black dark:text-white">{{ $product->stock_quantity }}</p>
                    </div>

                    <div class="hidden items-center justify-center p-2.5 sm:flex xl:p-5">
                        @if($product->is_active)
                            <p class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-600 dark:bg-green-900 dark:text-green-300">
                                Active
                            </p>
                        @else
                            <p class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-600 dark:bg-red-900 dark:text-red-300">
                                Inactive
                            </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/components/charts/chart-01.js') }}"></script>
<script src="{{ asset('admin/js/components/charts/chart-02.js') }}"></script>
@endpush
