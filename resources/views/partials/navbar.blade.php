<nav class="navbar fixed top-0 left-0 right-0 h-16 z-50">
    <div class="w-full">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 logo">
                <a class="text-lg" href="{{ route('home') }}">DAVID'S WOOD</a>
            </div>
            
            <!-- Mobile menu button -->
            <button class="md:hidden flex-shrink-0" id="mobile-menu-button" type="button">
                <span class="block w-6 h-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </span>
            </button>
            
            <!-- Desktop navigation -->
            <div class="hidden md:flex items-center justify-center flex-1">
                <ul class="flex items-center space-x-8">
                    <li>
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page" href="{{ route('home') }}">
                            <button class="btn" type="button">Home</button>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}" href="{{ route('products') }}">
                            <button class="btn" type="button">Products</button>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('home') }}#about">
                            <button class="btn" type="button">About</button>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="#contact">
                            <button class="btn" type="button">Contact</button>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Right side actions -->
            <div class="hidden md:flex items-center space-x-4 flex-shrink-0">
                <ul class="flex items-center space-x-4">
                    <li>
                        <a class="nav-link">
                            <button class="btn px-3" id="openSearchModal" type="button">Search</button>
                        </a>
                    </li>
                    <li class="relative">
                        <div class="dropdown">
                            <button class="btn px-3 border border-gray-800" id="account-dropdown" type="button">
                                @auth
                                    {{ Auth::user()->username ?? 'Account' }}
                                @else
                                    Account
                                @endauth
                            </button>
                            @auth
                            <!-- Authenticated user menu -->
                            <ul class="dropdown-menu absolute top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg text-center hidden" id="account-menu" style="display: none;">
                                <li class="px-4 py-2">
                                    <a class="block text-gray-700 hover:bg-gray-100 flex items-center" href="{{ url('/account') }}" target="_blank" rel="noopener noreferrer">
                                        <i data-lucide="user" class="mr-2 w-4 h-4"></i>
                                        My Account
                                    </a>
                                </li>
                                <li class="border-t border-gray-200"></li>
                                <li class="px-4 py-2">
                                    <a class="block text-gray-700 hover:bg-gray-100 flex items-center" href="#" id="logout-btn">
                                        <i data-lucide="log-out" class="mr-2 w-4 h-4"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                            @else
                            <!-- Guest user menu -->
                            <ul class="dropdown-menu absolute top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg text-center hidden" id="account-menu" style="display: none;">
                                <li class="px-4 py-2">
                                    <a class="block text-gray-700 hover:bg-gray-100" href="#" id="open-login-modal">Login</a>
                                </li>
                                <li class="px-4 py-2">
                                    <a class="block text-gray-700 hover:bg-gray-100" href="#" id="open-signup-modal">Create account</a>
                                </li>
                                <li class="border-t border-gray-200"></li>
                                <li class="px-4 py-2">
                                    <a class="block text-gray-700 hover:bg-gray-100" href="{{ route('auth.google') }}?intended_url={{ urlencode(request()->fullUrl()) }}">Sign in with Google</a>
                                </li>
                            </ul>
                            @endauth
                        </div>
                    </li>
                    @auth
                    <li class="relative">
                        <button class="btn py-3 px-2" id="openNotificationOffcanvas" type="button">
                            <i class="lucide-small" data-lucide="bell"></i>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden" id="notification-count"></span>
                        </button>
                    </li>
                    @endauth
                    <li class="relative">
                        <button class="btn py-3 px-2" id="openOffcanvas" type="button">
                            <i class="lucide-small" data-lucide="heart"></i>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden" id="wishlist-count"></span>
                        </button>
                    </li>
                    <li class="relative">
                        <button class="btn py-3 px-2" id="openCartOffcanvas" type="button">
                            <i class="lucide-small" data-lucide="shopping-cart"></i>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden" id="cart-count"></span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Mobile navigation -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a class="block px-3 py-2" href="{{ route('home') }}#home">
                    <button class="btn w-full text-left" type="button">Home</button>
                </a>
                <a class="block px-3 py-2" href="{{ route('products') }}#products">
                    <button class="btn w-full text-left" type="button">Products</button>
                </a>
                <a class="block px-3 py-2" href="{{ route('home') }}#about">
                    <button class="btn w-full text-left" type="button">About</button>
                </a>
                <a class="block px-3 py-2" href="#contact">
                    <button class="btn w-full text-left" type="button">Contact</button>
                </a>
                <div class="border-t border-gray-200 pt-2">
                    <button class="btn px-3 w-full text-left" id="openSearchModalMobile" type="button">Search</button>
                </div>
            </div>
        </div>
    </div>
</nav>
