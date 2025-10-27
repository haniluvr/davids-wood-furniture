<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Sign In | DW Atelier</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/assets/favicon.png') }}">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loginForm', () => ({
                showOtpForm: false,
                otpCode: '',
                isLoading: false,
                errorMessage: '',
                successMessage: '',
                
                
                async sendOtp() {
                    this.isLoading = true;
                    this.errorMessage = '';
                    this.successMessage = '';
                    
                    try {
                        const formData = new FormData(document.getElementById('login-form'));
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        const response = await fetch('{{ admin_route("login") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            // Delay showing OTP form to let login form slide out first
                            setTimeout(() => {
                                this.showOtpForm = true;
                                // Focus on OTP input after form appears
                                this.$nextTick(() => {
                                    const otpInput = document.getElementById('otp-code');
                                    if (otpInput) {
                                        otpInput.focus();
                                    }
                                });
                            }, 300); // 300ms delay to match transition duration
                        } else {
                            const data = await response.json();
                            this.errorMessage = data.message || data.errors?.email?.[0] || 'Failed to send OTP. Please try again.';
                        }
                    } catch (error) {
                        console.error('Login error:', error);
                        this.errorMessage = 'An error occurred. Please try again.';
                    } finally {
                        this.isLoading = false;
                    }
                },
                
                async verifyOtp() {
                    this.isLoading = true;
                    this.errorMessage = '';
                    
                    try {
                        const response = await fetch('{{ admin_route("verify-otp.post") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ code: this.otpCode })
                        });
                        
                        if (response.ok) {
                            window.location.href = '{{ admin_route("dashboard") }}';
                        } else {
                            const data = await response.json();
                            this.errorMessage = data.message || 'Invalid verification code.';
                        }
                    } catch (error) {
                        this.errorMessage = 'An error occurred. Please try again.';
                    } finally {
                        this.isLoading = false;
                    }
                },
                
                async resendOtp() {
                    this.isLoading = true;
                    this.errorMessage = '';
                    
                    try {
                        const response = await fetch('{{ admin_route("resend-otp") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            // Code resent successfully - no message needed
                        } else {
                            this.errorMessage = 'Failed to resend code. Please try again.';
                        }
                    } catch (error) {
                        this.errorMessage = 'An error occurred. Please try again.';
                    } finally {
                        this.isLoading = false;
                    }
                },
                
                handleOtpInput(event) {
                    // Only allow numbers
                    this.otpCode = event.target.value.replace(/[^0-9]/g, '');
                    
                    // Auto-submit when 6 digits are entered
                    if (this.otpCode.length === 6) {
                        setTimeout(() => {
                            if (this.otpCode.length === 6) {
                                this.verifyOtp();
                            }
                        }, 100);
                    }
                }
            }));
        });
    </script>
    
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
        page: 'signin', 
        loaded: true, 
        darkMode: JSON.parse(localStorage.getItem('darkMode')) || false, 
        stickyMenu: false, 
        sidebarToggle: false, 
        scrollTop: false,
        showPassword: false,
        checkboxToggle: false,
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
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                        Back to main site
                    </a>
                </div>
                
                <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
                    <div>
                        <div class="mb-5 sm:mb-8">
                            <h1 class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                                Admin Sign In
                            </h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Enter your email and password to access the admin dashboard
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
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-400 mr-2"></i>
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Display error messages -->
                        @if (session('error'))
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex">
                                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 mr-2"></i>
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <div x-data="loginForm()">
                            <!-- Error Messages -->
                            <div x-show="errorMessage" x-transition class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex">
                                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 mr-2"></i>
                                    <p class="text-sm text-red-700" x-text="errorMessage"></p>
                                </div>
                            </div>

                            <!-- Form Container with relative positioning -->
                            <div class="relative overflow-hidden min-h-[400px] bg-transparent">
                                <!-- Login Form -->
                                <div x-show="!showOtpForm" 
                                     x-transition:enter="transition ease-in duration-300" 
                                     x-transition:enter-start="opacity-0 transform translate-x-0" 
                                     x-transition:enter-end="opacity-100 transform translate-x-0" 
                                     x-transition:leave="transition ease-in" 
                                     x-transition:leave-start="opacity-100 transform translate-x-0" 
                                     x-transition:leave-end="opacity-0 transform -translate-x-full"
                                     class="w-full bg-transparent">
                                <form id="login-form" @submit.prevent="sendOtp()">
                                @csrf
                                <div class="space-y-5">
                                    <!-- Email -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Email<span class="text-error-500">*</span>
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                                placeholder="hymarquez@dwatelier.co"
                                                value="{{ old('email', 'hymarquez@dwatelier.co') }}"
                                            required
                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-sm placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                        />
                                    </div>
                                    
                                    <!-- Password -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Password<span class="text-error-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input
                                                :type="showPassword ? 'text' : 'password'"
                                                id="password"
                                                name="password"
                                                placeholder="Enter your password"
                                                required
                                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-sm placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                            />
                                            <span
                                                @click="showPassword = !showPassword"
                                                class="absolute z-30 text-gray-500 -translate-y-1/2 cursor-pointer right-4 top-1/2 dark:text-gray-400"
                                            >
                                                <i x-show="!showPassword" data-lucide="eye" class="w-5 h-5"></i>
                                                <i x-show="showPassword" data-lucide="eye-off" class="w-5 h-5"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Remember Me and Forgot Password -->
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label for="remember" class="flex items-center text-sm font-normal text-gray-700 cursor-pointer select-none dark:text-gray-400">
                                                <div class="relative">
                                                    <input
                                                        type="checkbox"
                                                        id="remember"
                                                        name="remember"
                                                        class="sr-only"
                                                        @change="checkboxToggle = !checkboxToggle"
                                                    />
                                                    <div
                                                        :class="checkboxToggle ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                                        class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px]"
                                                    >
                                                        <span :class="checkboxToggle ? '' : 'opacity-0'">
                                                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                Keep me logged in
                                            </label>
                                        </div>
                                        <a href="{{ url('/forgot-password') }}" class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400">
                                            Forgot password?
                                        </a>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div>
                                        <button
                                            type="submit"
                                                :disabled="isLoading"
                                                class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                                <i data-lucide="mail" class="w-4 h-4 mr-2" x-show="!isLoading"></i>
                                                <i data-lucide="loader-2" class="w-4 h-4 mr-2 animate-spin" x-show="isLoading"></i>
                                                <span x-text="isLoading ? 'Sending...' : 'Send OTP'"></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                                </div>

                                <!-- OTP Form -->
                                <div x-show="showOtpForm" 
                                     x-transition:enter="transition ease-in duration-300" 
                                     x-transition:enter-start="opacity-0 transform translate-x-0" 
                                     x-transition:enter-end="opacity-100 transform translate-x-0" 
                                     x-transition:leave="transition ease-out duration-300" 
                                     x-transition:leave-start="opacity-100 transform translate-x-0" 
                                     x-transition:leave-end="opacity-0 transform translate-x-full"
                                     class="absolute top-0 left-0 w-full bg-transparent">
                                <div class="text-center mb-6">
                                    <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-brand-100 dark:bg-brand-900 mb-4">
                                        <i data-lucide="mail" class="h-6 w-6 text-brand-600 dark:text-brand-400"></i>
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                        Enter Verification Code
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        We've sent a 6-digit code to your email
                                    </p>
                                </div>

                                <form @submit.prevent="verifyOtp()">
                                    <div class="space-y-5">
                                        <!-- OTP Code Input -->
                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                Verification Code<span class="text-error-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                id="otp-code"
                                                x-model="otpCode"
                                                @input="handleOtpInput"
                                                maxlength="6"
                                                required
                                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-center text-2xl font-mono tracking-widest text-gray-800 shadow-sm placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                                placeholder="000000"
                                                autocomplete="one-time-code"
                                            />
                                        </div>

                                        <!-- Submit Button -->
                                        <div>
                                            <button
                                                type="submit"
                                                :disabled="isLoading || otpCode.length !== 6"
                                                class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                            >
                                                <i data-lucide="log-in" class="w-4 h-4 mr-2" x-show="!isLoading"></i>
                                                <i data-lucide="loader-2" class="w-4 h-4 mr-2 animate-spin" x-show="isLoading"></i>
                                                <span x-text="isLoading ? 'Verifying...' : 'Sign In'"></span>
                                            </button>
                                        </div>

                                        <div class="text-center space-y-2">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Didn't receive the code? 
                                                <button type="button" @click="resendOtp()" :disabled="isLoading" class="font-medium text-brand-600 hover:text-brand-500 dark:text-brand-400 dark:hover:text-brand-300 disabled:opacity-50">
                                                    Resend Code
                                                </button>
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <button type="button" @click="showOtpForm = false; otpCode = ''; errorMessage = ''; successMessage = ''" class="font-medium text-brand-600 hover:text-brand-500 dark:text-brand-400 dark:hover:text-brand-300">
                                                    ← Back to Login
                                                </button>
                                            </p>
                                        </div>
                                    </div>
                                </form>
                                </div>
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
                            Admin Dashboard for DW Atelier.<br>Furniture Management System
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