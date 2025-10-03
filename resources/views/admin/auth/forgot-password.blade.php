<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password | DW Atelier</title>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand': {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b'
                        },
                        'error': {
                            500: '#ef4444'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body
    x-data="{ 
        darkMode: JSON.parse(localStorage.getItem('darkMode')) || false,
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', JSON.stringify(this.darkMode));
            // Re-initialize icons after DOM update
            this.$nextTick(() => {
                lucide.createIcons();
            });
        }
    }"
    x-init="
        $watch('darkMode', value => {
            localStorage.setItem('darkMode', JSON.stringify(value));
            // Apply dark class to html element for better compatibility
            if (value) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
        // Initialize dark mode on page load
        if (darkMode) {
            document.documentElement.classList.add('dark');
        }"
    :class="{'dark': darkMode}"
>
    <!-- Page Wrapper Start -->
    <div class="relative p-6 bg-white z-1 dark:bg-gray-900 sm:p-0 transition-colors duration-300">
        <div class="relative flex flex-col justify-center w-full h-screen dark:bg-gray-900 sm:p-0 lg:flex-row transition-colors duration-300">
            
            <!-- Form Section -->
            <div class="flex flex-col flex-1 w-full lg:w-1/2">
                <div class="w-full max-w-md pt-10 mx-auto">
                    <a href="{{ route('admin.login') }}" class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                        Back to login
                    </a>
                </div>
                
                <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
                    <div>
                        <div class="mb-5 sm:mb-8">
                            <h1 class="mb-2 font-semibold text-gray-800 text-2xl dark:text-white/90 sm:text-3xl">
                                Reset Password
                            </h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Enter your email address and we'll send you a link to reset your password
                            </p>
                        </div>
                        
                        <!-- Display validation errors -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex">
                                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 mr-2"></i>
                                    <div class="text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Display success messages -->
                        @if (session('status'))
                            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-400 mr-2"></i>
                                    <p class="text-sm text-green-700">{{ session('status') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <div>
                            <!-- Reset Password Form -->
                            <form method="POST" action="{{ route('admin.forgot-password') }}">
                                @csrf
                                <div class="space-y-5">
                                    <!-- Email -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Email Address<span class="text-error-500">*</span>
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            placeholder="admin@dwatelier.com"
                                            value="{{ old('email') }}"
                                            required
                                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-sm placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                        />
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div>
                                        <button
                                            type="submit"
                                            class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500"
                                        >
                                            <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                                            Send Reset Link
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                            <!-- Back to Login -->
                            <div class="mt-5">
                                <p class="text-sm font-normal text-center text-gray-700 dark:text-gray-400">
                                    Remember your password?
                                    <a href="{{ route('admin.login') }}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400">
                                        Sign in instead
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Image/Branding Section -->
            <div class="relative items-center hidden w-full h-full bg-brand-950 dark:bg-white/5 lg:grid lg:w-1/2">
                <div class="flex items-center justify-center z-1">
                    <!-- Decorative Grid Background -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="grid grid-cols-12 gap-4 h-full">
                            @for ($i = 0; $i < 144; $i++)
                                <div class="bg-white/20 rounded"></div>
                            @endfor
                        </div>
                    </div>
                    
                    <!-- Logo and Text -->
                    <div class="flex flex-col items-center max-w-xs relative z-10">
                        <div class="block mb-4">
                            <div class="w-20 h-20 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm p-2">
                                <img src="{{ asset('admin/images/logo/favicon.png') }}" alt="DW Atelier Logo" class="w-full h-full object-contain" />
                            </div>
                        </div>
                        <p class="text-center text-gray-400 dark:text-white/60">
                            Admin Dashboard for DW Atelier - Wood Furniture Management System
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Dark Mode Toggle -->
            <div class="fixed z-50 bottom-6 right-6 sm:block">
                <button
                    class="inline-flex items-center justify-center text-white transition-all duration-200 rounded-full size-14 bg-brand-500 hover:bg-brand-600 hover:scale-105 shadow-lg"
                    @click.prevent="toggleDarkMode()"
                    :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'"
                >
                    <i x-show="!darkMode" data-lucide="moon" class="w-5 h-5 transition-opacity duration-200"></i>
                    <i x-show="darkMode" data-lucide="sun" class="w-5 h-5 transition-opacity duration-200"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Re-initialize icons when Alpine.js updates the DOM
        document.addEventListener('alpine:initialized', () => {
            lucide.createIcons();
        });
        
        // Watch for dark mode changes and re-initialize icons
        document.addEventListener('alpine:init', () => {
            Alpine.store('darkMode', {
                on: Alpine.$persist(false).as('darkMode'),
                toggle() {
                    this.on = !this.on;
                    // Re-initialize icons after DOM update
                    setTimeout(() => lucide.createIcons(), 10);
                }
            });
        });
    </script>
</body>
</html>
