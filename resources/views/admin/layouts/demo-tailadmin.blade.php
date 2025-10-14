<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'eCommerce Dashboard') | TailAdmin - Tailwind CSS Admin Dashboard Template</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('frontend/assets/favicon.png') }}">
    
    <!-- TailAdmin CSS -->
    <link href="{{ asset('admin/css/compiled.css') }}" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Alpine.js Components -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sidebar', () => ({
                sidebarToggle: false,
                init() {
                    // Initialize sidebar state
                }
            }));
            
            Alpine.data('dropdown', () => ({
                dropdownOpen: false,
                toggle() {
                    this.dropdownOpen = !this.dropdownOpen;
                },
                close() {
                    this.dropdownOpen = false;
                }
            }));
        });
    </script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    @stack('styles')
</head>
<body
    x-data="{ 
        page: 'ecommerce', 
        'loaded': true, 
        'darkMode': false, 
        'stickyMenu': false, 
        'sidebarToggle': false, 
        'scrollTop': false 
    }"
    x-init="
        darkMode = JSON.parse(localStorage.getItem('darkMode'));
        $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))
    "
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
>
    <!-- ===== Preloader Start ===== -->
    <div x-show="loaded" x-transition class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent"></div>
    </div>
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
        <!-- ===== Sidebar Start ===== -->
        @include('admin.partials.demo-sidebar')
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <!-- Small Device Overlay Start -->
            <div 
                x-show="sidebarToggle" 
                @click="sidebarToggle = false"
                class="fixed inset-0 z-99998 bg-black/20 lg:hidden"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>
            <!-- Small Device Overlay End -->

            <!-- ===== Header Start ===== -->
            @include('admin.partials.demo-header')
            <!-- ===== Header End ===== -->

            <!-- ===== Main Content Start ===== -->
            <main>
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    @yield('content')
                </div>
            </main>
            <!-- ===== Main Content End ===== -->
        </div>
        <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->

    <!-- TailAdmin JS -->
    <script src="{{ asset('admin/js/index.js') }}"></script>
    
    @stack('scripts')
    
    <script>
        // Initialize loaded state
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelector('[x-data]').__x.$data.loaded = false;
            }, 500);
        });
    </script>
</body>
</html>
