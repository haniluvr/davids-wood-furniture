<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard') | NeoCommerce - Admin Panel</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('frontend/assets/favicon.png') }}">
    
    <!-- TailAdmin CSS -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('styles')
</head>
<body
    x-data="{ 
        page: '{{ request()->route()->getName() ?? 'dashboard' }}', 
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
    :class="{'dark bg-gray-900': darkMode === true}"
>
    <!-- Preloader -->
    <div x-show="loaded" x-transition class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent"></div>
    </div>

    <!-- Page Wrapper -->
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('admin.partials.tailadmin-sidebar')

        <!-- Content Area -->
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Mobile Overlay -->
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

            <!-- Header -->
            @include('admin.partials.tailadmin-header')

            <!-- Main Content -->
            <main>
                <div class="p-4 mx-auto max-w-screen-2xl md:p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- TailAdmin JS -->
    <script src="{{ asset('admin/js/index.js') }}"></script>
    
    @stack('scripts')
    
    <script>
        // Initialize loaded state
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                Alpine.store('loaded', false);
            }, 500);
        });
    </script>
</body>
</html>
