@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Dashboard
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ route('admin.dashboard') }}">Dashboard /</a>
            </li>
            <li class="font-medium text-primary">eCommerce</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards Start -->
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5">
    <!-- Card Item Start -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <i data-lucide="users" class="w-6 h-6 text-primary dark:text-white"></i>
        </div>

        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($totalUsers) }}
                </h4>
                <span class="text-sm font-medium">Total Customers</span>
            </div>

            <span class="flex items-center gap-1 text-sm font-medium text-meta-3">
                0.43%
                <i data-lucide="trending-up" class="w-4 h-4"></i>
            </span>
        </div>
    </div>
    <!-- Card Item End -->

    <!-- Card Item Start -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <i data-lucide="shopping-cart" class="w-6 h-6 text-primary dark:text-white"></i>
        </div>

        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($totalOrders) }}
                </h4>
                <span class="text-sm font-medium">Total Orders</span>
            </div>

            <span class="flex items-center gap-1 text-sm font-medium text-meta-5">
                4.35%
                <i data-lucide="trending-down" class="w-4 h-4"></i>
            </span>
        </div>
    </div>
    <!-- Card Item End -->

    <!-- Card Item Start -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <i data-lucide="package" class="w-6 h-6 text-primary dark:text-white"></i>
        </div>

        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    {{ number_format($totalProducts) }}
                </h4>
                <span class="text-sm font-medium">Total Products</span>
            </div>

            <span class="flex items-center gap-1 text-sm font-medium text-meta-3">
                2.59%
                <i data-lucide="trending-up" class="w-4 h-4"></i>
            </span>
        </div>
    </div>
    <!-- Card Item End -->

    <!-- Card Item Start -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-meta-2 dark:bg-meta-4">
            <i data-lucide="dollar-sign" class="w-6 h-6 text-primary dark:text-white"></i>
        </div>

        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-black dark:text-white">
                    ${{ number_format($totalRevenue, 2) }}
                </h4>
                <span class="text-sm font-medium">Total Revenue</span>
            </div>

            <span class="flex items-center gap-1 text-sm font-medium text-meta-3">
                0.95%
                <i data-lucide="trending-up" class="w-4 h-4"></i>
            </span>
        </div>
    </div>
    <!-- Card Item End -->
</div>
<!-- Stats Cards End -->

<!-- Charts and Tables -->
<div class="mt-4 grid grid-cols-12 gap-4 md:mt-6 md:gap-6 2xl:mt-7.5 2xl:gap-7.5">
    <!-- Chart One -->
    <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:col-span-8">
        <div class="flex flex-wrap items-start justify-between gap-3 sm:flex-nowrap">
            <div class="flex w-full flex-wrap gap-3 sm:gap-5">
                <div class="flex min-w-47.5">
                    <span class="mr-2 mt-1 flex h-4 w-full max-w-4 items-center justify-center rounded-full border border-primary">
                        <span class="block h-2.5 w-full max-w-2.5 rounded-full bg-primary"></span>
                    </span>
                    <div class="w-full">
                        <p class="font-semibold text-primary">Total Revenue</p>
                        <p class="text-sm font-medium">${{ number_format($totalRevenue, 2) }}</p>
                    </div>
                </div>
                <div class="flex min-w-47.5">
                    <span class="mr-2 mt-1 flex h-4 w-full max-w-4 items-center justify-center rounded-full border border-secondary">
                        <span class="block h-2.5 w-full max-w-2.5 rounded-full bg-secondary"></span>
                    </span>
                    <div class="w-full">
                        <p class="font-semibold text-secondary">Total Sales</p>
                        <p class="text-sm font-medium">{{ number_format($totalOrders) }}</p>
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
            <div id="chartOne" class="-ml-5 h-[355px] w-[105%]"></div>
        </div>
    </div>
    <!-- Chart One -->

    <!-- Chart Three -->
    <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:col-span-4">
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
                            <path d="M0.47072 1.08816C0.47072 1.02932 0.500141 0.955772 0.54427 0.911642C0.647241 0.808672 0.809051 0.808672 0.912022 0.896932L4.85431 4.60386C4.92785 4.67741 5.15786 4.67741 5.2314 4.60386L9.17369 0.896932C9.27666 0.793962 9.43847 0.808672 9.54144 0.911642C9.6444 1.01461 9.6444 1.17642 9.54144 1.27939L5.50141 5.08816C5.22570 5.36387 4.80431 5.36387 4.52859 5.08816L0.47072 1.08816C0.47072 1.08816 0.47072 1.08816 0.47072 1.08816Z" fill="#637381"/>
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <div class="mb-2">
            <div id="chartThree" class="mx-auto flex justify-center"></div>
        </div>

        <div class="-mx-8 flex flex-wrap items-center justify-center gap-y-3">
            <div class="w-full px-8 sm:w-1/2">
                <div class="flex w-full items-center">
                    <span class="mr-2 block h-3 w-full max-w-3 rounded-full bg-primary"></span>
                    <p class="flex w-full justify-between text-sm font-medium text-black dark:text-white">
                        <span>Desktop</span>
                        <span>65%</span>
                    </p>
                </div>
            </div>
            <div class="w-full px-8 sm:w-1/2">
                <div class="flex w-full items-center">
                    <span class="mr-2 block h-3 w-full max-w-3 rounded-full bg-[#6577F3]"></span>
                    <p class="flex w-full justify-between text-sm font-medium text-black dark:text-white">
                        <span>Tablet</span>
                        <span>34%</span>
                    </p>
                </div>
            </div>
            <div class="w-full px-8 sm:w-1/2">
                <div class="flex w-full items-center">
                    <span class="mr-2 block h-3 w-full max-w-3 rounded-full bg-[#8FD0EF]"></span>
                    <p class="flex w-full justify-between text-sm font-medium text-black dark:text-white">
                        <span>Mobile</span>
                        <span>45%</span>
                    </p>
                </div>
            </div>
            <div class="w-full px-8 sm:w-1/2">
                <div class="flex w-full items-center">
                    <span class="mr-2 block h-3 w-full max-w-3 rounded-full bg-[#0FADCF]"></span>
                    <p class="flex w-full justify-between text-sm font-medium text-black dark:text-white">
                        <span>Unknown</span>
                        <span>12%</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Chart Three -->

    <!-- Recent Orders Table -->
    <div class="col-span-12 xl:col-span-8">
        <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h4 class="text-xl font-semibold text-black dark:text-white">
                        Recent Orders
                    </h4>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="inline-flex items-center justify-center rounded-md border border-stroke px-3 py-2 text-center font-medium text-black hover:bg-opacity-90 dark:border-strokedark dark:text-white">
                        Filter
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center rounded-md bg-primary px-3 py-2 text-center font-medium text-white hover:bg-opacity-90">
                        See all
                    </a>
                </div>
            </div>

            <div class="flex flex-col">
                <div class="grid grid-cols-3 rounded-sm bg-gray-2 dark:bg-meta-4 sm:grid-cols-5">
                    <div class="p-2.5 xl:p-5">
                        <h5 class="text-sm font-medium uppercase xsm:text-base">
                            Customer
                        </h5>
                    </div>
                    <div class="p-2.5 text-center xl:p-5">
                        <h5 class="text-sm font-medium uppercase xsm:text-base">
                            Date
                        </h5>
                    </div>
                    <div class="p-2.5 text-center xl:p-5">
                        <h5 class="text-sm font-medium uppercase xsm:text-base">
                            Amount
                        </h5>
                    </div>
                    <div class="hidden p-2.5 text-center sm:block xl:p-5">
                        <h5 class="text-sm font-medium uppercase xsm:text-base">
                            Status
                        </h5>
                    </div>
                    <div class="hidden p-2.5 text-center sm:block xl:p-5">
                        <h5 class="text-sm font-medium uppercase xsm:text-base">
                            Actions
                        </h5>
                    </div>
                </div>

                @forelse($recentOrders as $order)
                <div class="grid grid-cols-3 border-b border-stroke dark:border-strokedark sm:grid-cols-5">
                    <div class="flex items-center gap-3 p-2.5 xl:p-5">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-full object-cover" src="https://via.placeholder.com/48x48/3C50E0/FFFFFF?text={{ substr($order->user->first_name ?? 'G', 0, 1) }}" alt="Customer" />
                        </div>
                        <div class="flex flex-col">
                            <p class="text-black dark:text-white font-medium">{{ $order->user->first_name ?? 'Guest' }} {{ $order->user->last_name ?? '' }}</p>
                            <p class="text-sm text-gray-500">Order #{{ $order->order_number ?? $order->id }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-center p-2.5 xl:p-5">
                        <p class="text-black dark:text-white">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>

                    <div class="flex items-center justify-center p-2.5 xl:p-5">
                        <p class="text-black dark:text-white font-medium">${{ number_format($order->total_amount, 2) }}</p>
                    </div>

                    <div class="hidden items-center justify-center p-2.5 sm:flex xl:p-5">
                        <span class="inline-flex rounded-full bg-success bg-opacity-10 px-3 py-1 text-sm font-medium text-success">
                            {{ ucfirst($order->status ?? 'pending') }}
                        </span>
                    </div>

                    <div class="hidden items-center justify-center p-2.5 sm:flex xl:p-5">
                        <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-primary">
                            <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.99981 14.8219C3.43106 14.8219 0.674805 9.50624 0.562305 9.28124C0.47793 9.11249 0.47793 8.88749 0.562305 8.71874C0.674805 8.49374 3.43106 3.20624 8.99981 3.20624C14.5686 3.20624 17.3248 8.49374 17.4373 8.71874C17.5217 8.88749 17.5217 9.11249 17.4373 9.28124C17.3248 9.50624 14.5686 14.8219 8.99981 14.8219ZM1.85605 8.99999C2.4748 10.0406 4.89356 13.5562 8.99981 13.5562C13.1061 13.5562 15.5248 10.0406 16.1436 8.99999C15.5248 7.95936 13.1061 4.44374 8.99981 4.44374C4.89356 4.44374 2.4748 7.95936 1.85605 8.99999Z" fill=""/>
                                <path d="M9 11.3906C7.67812 11.3906 6.60938 10.3219 6.60938 9C6.60938 7.67813 7.67812 6.60938 9 6.60938C10.3219 6.60938 11.3906 7.67813 11.3906 9C11.3906 10.3219 10.3219 11.3906 9 11.3906ZM9 7.875C8.38125 7.875 7.875 8.38125 7.875 9C7.875 9.61875 8.38125 10.125 9 10.125C9.61875 10.125 10.125 9.61875 10.125 9C10.125 8.38125 9.61875 7.875 9 7.875Z" fill=""/>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-5 text-center">
                    <p class="text-gray-500">No recent orders found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="col-span-12 xl:col-span-4">
        <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="mb-3 justify-between gap-4 sm:flex">
                <div>
                    <h5 class="text-xl font-semibold text-black dark:text-white">
                        Low Stock Alert
                    </h5>
                    <p class="text-sm font-medium">Products running low on stock</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-primary hover:underline">View All</a>
                </div>
            </div>

            @forelse($lowStockProducts as $product)
            <div class="mb-2">
                <div class="flex items-center justify-between py-2">
                    <div class="flex items-center">
                        <div class="mr-3 h-12 w-12 rounded bg-gray-100 flex items-center justify-center">
                            @if($product->images && count($product->images) > 0)
                                <img class="h-10 w-10 object-cover rounded" src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" />
                            @else
                                <span class="text-gray-400 text-xs">IMG</span>
                            @endif
                        </div>
                        <div>
                            <span class="font-medium text-black dark:text-white">{{ $product->name }}</span>
                            <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-sm font-medium text-black dark:text-white">{{ $product->stock_quantity }} left</span>
                        <span class="text-sm font-medium text-meta-1">{{ number_format(($product->stock_quantity / ($product->low_stock_threshold ?: 10)) * 100) }}%</span>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-meta-1 h-2 rounded-full" style="width: {{ min(100, ($product->stock_quantity / ($product->low_stock_threshold ?: 10)) * 100) }}%"></div>
                </div>
            </div>
            @empty
            <div class="text-center py-4">
                <p class="text-sm text-gray-500">All products are well stocked</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Chart One - Revenue Chart
const chartOneOptions = {
    series: [
        {
            name: 'Revenue',
            data: @json(array_values($monthlyRevenue)),
        },
        {
            name: 'Orders',
            data: @json(array_values($monthlyOrders)),
        },
    ],
    legend: {
        show: false,
        position: 'top',
        horizontalAlign: 'left',
    },
    colors: ['#3C50E0', '#80CAEE'],
    chart: {
        fontFamily: 'Satoshi, sans-serif',
        height: 335,
        type: 'area',
        dropShadow: {
            enabled: true,
            color: '#623CEA14',
            top: 10,
            blur: 4,
            left: 0,
            opacity: 0.1,
        },
        toolbar: {
            show: false,
        },
    },
    responsive: [
        {
            breakpoint: 1024,
            options: {
                chart: {
                    height: 300,
                },
            },
        },
        {
            breakpoint: 1366,
            options: {
                chart: {
                    height: 350,
                },
            },
        },
    ],
    stroke: {
        width: [2, 2],
        curve: 'straight',
    },
    grid: {
        xaxis: {
            lines: {
                show: true,
            },
        },
        yaxis: {
            lines: {
                show: true,
            },
        },
    },
    dataLabels: {
        enabled: false,
    },
    markers: {
        size: 4,
        colors: '#fff',
        strokeColors: ['#3056D3', '#80CAEE'],
        strokeWidth: 3,
        strokeOpacity: 0.9,
        strokeDashArray: 0,
        fillOpacity: 1,
        discrete: [],
        hover: {
            size: undefined,
            sizeOffset: 5,
        },
    },
    xaxis: {
        type: 'category',
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
        max: Math.max(...@json(array_values($monthlyRevenue))) * 1.1,
    },
};

const chartOne = new ApexCharts(document.querySelector('#chartOne'), chartOneOptions);
chartOne.render();

// Chart Three - Donut Chart
const chartThreeOptions = {
    series: [65, 34, 45, 12],
    chart: {
        type: 'donut',
        width: 380,
        height: 335,
    },
    colors: ['#3C50E0', '#6577F3', '#8FD0EF', '#0FADCF'],
    labels: ['Desktop', 'Tablet', 'Mobile', 'Unknown'],
    legend: {
        show: false,
        position: 'bottom',
    },
    plotOptions: {
        pie: {
            donut: {
                size: '65%',
                background: 'transparent',
            },
        },
    },
    dataLabels: {
        enabled: false,
    },
    responsive: [
        {
            breakpoint: 2600,
            options: {
                chart: {
                    width: 380,
                },
            },
        },
        {
            breakpoint: 640,
            options: {
                chart: {
                    width: 200,
                },
            },
        },
    ],
};

const chartThree = new ApexCharts(document.querySelector('#chartThree'), chartThreeOptions);
chartThree.render();
</script>
@endpush