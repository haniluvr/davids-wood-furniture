<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@if(isset($isReset) && $isReset) Reset Password @else Set Up Password @endif | DW Atelier Admin</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/assets/favicon.png') }}">
    
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
                    },
                    fontSize: {
                        'title-sm': ['1.5rem', { lineHeight: '2rem' }],
                        'title-md': ['1.875rem', { lineHeight: '2.25rem' }]
                    }
                }
            }
        }
    </script>
</head>
<body
    x-data="{ 
        showPassword: false,
        showConfirmPassword: false,
        isLoading: false,
        passwordMismatch: false,
        darkMode: JSON.parse(localStorage.getItem('darkMode')) || false,
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', JSON.stringify(this.darkMode));
            this.$nextTick(() => {
                lucide.createIcons();
            });
        },
        checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            
            if (passwordConfirmation.length > 0) {
                this.passwordMismatch = password !== passwordConfirmation;
            } else {
                this.passwordMismatch = false;
            }
        }
    }"
    x-init="
        $watch('darkMode', value => {
            localStorage.setItem('darkMode', JSON.stringify(value));
            if (value) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
        if (darkMode) {
            document.documentElement.classList.add('dark');
        }
    "
    :class="{'dark': darkMode}"
>
    <!-- Page Wrapper Start -->
    <div class="relative p-6 bg-white z-1 dark:bg-gray-900 sm:p-0 transition-colors duration-300">
        <div class="relative flex flex-col justify-center w-full h-screen dark:bg-gray-900 sm:p-0 lg:flex-row transition-colors duration-300">
            
            <!-- Form Section -->
            <div class="flex flex-col flex-1 w-full lg:w-1/2">
                <div class="w-full max-w-md pt-10 mx-auto">
                    <a href="{{ admin_route('login') }}" class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                        Back to login
                    </a>
                </div>
                
                <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
                    <div>
                        <div class="mb-5 sm:mb-8">
                            <h1 class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                                @if(isset($isReset) && $isReset) Reset Password @else Set Up Password @endif
                            </h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if(isset($isReset) && $isReset)
                                    Create a new secure password for your account
                                @else
                                    Complete your account setup by creating a secure password
                                @endif
                            </p>
                        </div>

                        <!-- Welcome/Info Message -->
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg dark:bg-blue-900/20 dark:border-blue-800">
                            <div class="flex">
                                <i data-lucide="info" class="w-5 h-5 text-blue-400 mr-2 flex-shrink-0 mt-0.5"></i>
                                <div class="text-sm">
                                    @if(isset($isReset) && $isReset)
                                        <p class="text-blue-900 dark:text-blue-100 font-medium mb-1">Hello, {{ $admin->first_name }}!</p>
                                        <p class="text-blue-700 dark:text-blue-300">Reset your password for <strong>{{ $admin->email }}</strong></p>
                                    @else
                                        <p class="text-blue-900 dark:text-blue-100 font-medium mb-1">Welcome, {{ $admin->first_name }}!</p>
                                        <p class="text-blue-700 dark:text-blue-300">Your login email is <strong>{{ $admin->email }}</strong></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Display validation errors -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg dark:bg-red-900/20 dark:border-red-800">
                                <div class="flex">
                                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 mr-2"></i>
                                    <div class="text-sm text-red-700 dark:text-red-300">
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Display success messages -->
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:border-green-800">
                                <div class="flex">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-400 mr-2"></i>
                                    <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <form action="{{ isset($isReset) && $isReset ? admin_route('reset-password.post') : admin_route('setup-password.post') }}" method="POST" id="setupPasswordForm">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="space-y-5">
                                <!-- New Password -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        New Password<span class="text-error-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input
                                            :type="showPassword ? 'text' : 'password'"
                                            id="password"
                                            name="password"
                                            placeholder="Enter your new password (minimum 8 characters)"
                                            required
                                            minlength="8"
                                            autocomplete="new-password"
                                            x-on:input="checkPasswordMatch()"
                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('password') border-red-300 dark:border-red-700 @else border-gray-300 dark:border-gray-700 @enderror"
                                        />
                                        <span
                                            @click="showPassword = !showPassword"
                                            class="absolute z-30 text-gray-500 -translate-y-1/2 cursor-pointer right-4 top-1/2 dark:text-gray-400"
                                        >
                                            <i x-show="!showPassword" data-lucide="eye" class="w-5 h-5"></i>
                                            <i x-show="showPassword" data-lucide="eye-off" class="w-5 h-5"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimum 8 characters required</p>
                                </div>
                                
                                <!-- Confirm Password -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Confirm Password<span class="text-error-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input
                                            :type="showConfirmPassword ? 'text' : 'password'"
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            placeholder="Confirm your new password"
                                            required
                                            minlength="8"
                                            autocomplete="new-password"
                                            x-on:input="checkPasswordMatch()"
                                            :class="passwordMismatch ? 'border-red-300 dark:border-red-700' : 'border-gray-300 dark:border-gray-700'"
                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-sm placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                        />
                                        <span
                                            @click="showConfirmPassword = !showConfirmPassword"
                                            class="absolute z-30 text-gray-500 -translate-y-1/2 cursor-pointer right-4 top-1/2 dark:text-gray-400"
                                        >
                                            <i x-show="!showConfirmPassword" data-lucide="eye" class="w-5 h-5"></i>
                                            <i x-show="showConfirmPassword" data-lucide="eye-off" class="w-5 h-5"></i>
                                        </span>
                                    </div>
                                    <p x-show="passwordMismatch" x-transition class="mt-1 text-sm text-red-600 dark:text-red-400">Passwords do not match</p>
                                </div>
                                
                                <!-- Submit Button -->
                                <div>
                                    <button
                                        type="submit"
                                        :disabled="isLoading"
                                        class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <i data-lucide="lock" class="w-4 h-4 mr-2" x-show="!isLoading"></i>
                                        <i data-lucide="loader-2" class="w-4 h-4 mr-2 animate-spin" x-show="isLoading"></i>
                                        <span x-text="isLoading ? '{{ isset($isReset) && $isReset ? 'Resetting password...' : 'Setting up password...' }}' : '{{ isset($isReset) && $isReset ? 'Reset Password' : 'Set Up Password' }}'"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
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
                            Admin Dashboard for DW Atelier<br>Furniture Management System
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
        
        // Form validation
        document.getElementById('setupPasswordForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            
            if (password.length < 8) {
                e.preventDefault();
                // Show error message
                const passwordField = document.getElementById('password');
                passwordField.classList.add('border-red-300', 'dark:border-red-700');
                const errorMsg = document.createElement('p');
                errorMsg.className = 'mt-1 text-sm text-red-600 dark:text-red-400';
                errorMsg.textContent = 'Password must be at least 8 characters long.';
                if (!passwordField.nextElementSibling || !passwordField.nextElementSibling.classList.contains('text-red-600')) {
                    passwordField.parentElement.appendChild(errorMsg);
                }
                return false;
            }
            
            if (password !== passwordConfirmation) {
                e.preventDefault();
                // Trigger Alpine.js to show mismatch error
                const alpineData = Alpine.$data(document.querySelector('[x-data]'));
                if (alpineData) {
                    alpineData.passwordMismatch = true;
                }
                return false;
            }
            
            // Set loading state
            const alpineData = Alpine.$data(document.querySelector('[x-data]'));
            if (alpineData) {
                alpineData.isLoading = true;
            }
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>
