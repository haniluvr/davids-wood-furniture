import { products } from './data/products.js';

// ── Generic Component Loader ──
async function loadComponent(url, targetId, initCallback = null) {
    const container = document.getElementById(targetId);
    if (!container) return;

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error(`Failed to load ${url}`);

        container.innerHTML = await response.text();

        // Re-init Lucide & Feather
        if (typeof lucide !== 'undefined') lucide.createIcons();
        if (typeof feather !== 'undefined') feather.replace();

        // Run optional init logic
        if (initCallback && typeof initCallback === 'function') {
            initCallback();
        }

    } catch (error) {
        console.error(`Error loading component from ${url}:`, error);
    }
}

// ── Initialize Tailwind Offcanvas ──
function initOffcanvas(id) {
    const el = document.getElementById(id); // acts as backdrop container
    const panel = document.getElementById(id + '-panel');
    const simpleName = id.replace('offcanvas-', '').replace(/-/g, ''); // e.g. cart, wishlist
    const idBasedName = id.replace(/-/g, ''); // e.g. offcanvascart
    const closeBtn = document.getElementById('close-' + simpleName + '-offcanvas');

    if (el && panel) {
        const show = function() {
            el.classList.remove('hidden');
            // allow paint before sliding in
            setTimeout(() => {
                panel.classList.remove('translate-x-full');
                panel.classList.add('translate-x-0');
            }, 10);
        };

        const hide = function() {
            panel.classList.remove('translate-x-0');
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                el.classList.add('hidden');
            }, 300);
        };

        // Expose both aliases: showcart and showoffcanvascart
        window['show' + simpleName] = show;
        window['hide' + simpleName] = hide;
        window['show' + idBasedName] = show;
        window['hide' + idBasedName] = hide;
        

        if (closeBtn) {
            closeBtn.addEventListener('click', hide);
        }

        // Backdrop click (click outside the panel)
        el.addEventListener('click', function (event) {
            if (event.target === el) hide();
        });
    }
}

// ── Initialize Tailwind Modal ──
function initModal(id) {
    const el = document.getElementById(id);
    const simpleName = id.replace('modal-', '').replace(/-/g, ''); // e.g. search, quickview
    const idBasedName = id.replace(/-/g, ''); // e.g. modalsearch, modalquickview
    const closeBtn = document.getElementById('close-' + simpleName + '-modal');

    if (el) {
        const show = function() { 
            el.classList.remove('hidden');
            // Trigger fade in animation
            setTimeout(() => {
                el.classList.add('show');
            }, 10);
        };
        const hide = function() { 
            el.classList.remove('show');
            // Wait for animation to complete before hiding
            setTimeout(() => {
                el.classList.add('hidden');
            }, 300);
        };

        // Expose both aliases: showsearch and showmodalsearch
        window['show' + simpleName] = show;
        window['hide' + simpleName] = hide;
        window['show' + idBasedName] = show;
        window['hide' + idBasedName] = hide;

        if (closeBtn) {
            closeBtn.addEventListener('click', hide);
        }

        // Backdrop click to close
        el.addEventListener('click', function(e) {
            if (e.target === el) hide();
        });
    }
}

// ── Initialize Products Section ──
function initProductsSection() {
    const grid = document.getElementById('product-grid');
    if (!grid) return;

    let displayedProducts = [];

    // Initial render
    renderProductsWithFilter(products.slice(0, 8));

    // Filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.getAttribute('data-filter');
            const filtered = products.filter(p => filter === 'all' || p.category === filter);
            renderProductsWithFilter(filtered.slice(0, 8));
        });
    });

    // Sort dropdown
    document.getElementById('sort-select').addEventListener('change', () => {
        const sort = document.getElementById('sort-select').value;
        let sorted = [...products];

        switch (sort) {
            case 'price-low':
                sorted.sort((a, b) => a.price - b.price);
                break;
            case 'price-high':
                sorted.sort((a, b) => b.price - a.price);
                break;
            case 'newest':
                sorted.sort((a, b) => b.id - a.id);
                break;
            default: // popularity
                sorted.sort((a, b) => b.rating - a.rating);
        }

        renderProductsWithFilter(sorted.slice(0, 8));
    });

    // Render function
    function renderProductsWithFilter(products) {
        grid.innerHTML = '';
        displayedProducts = products;

        products.forEach(product => {
            const col = document.createElement('div');
            col.className = 'w-full';

            col.innerHTML = `
                <div class="card product-card flex flex-col h-full rounded-2xl border bg-white">
                    <img src="${product.image}" class="w-full h-64 object-cover" alt="${product.name}">
                    <div class="absolute inset-0 flex p-4 h-full">
                        <div class="flex-1">
                            <div class="rounded-full stock-badge ${product.stock === 'low' ? 'low' : 'in-stock'} px-3 py-1 text-xs font-medium">
                            ${product.stock === 'low' ? 'Low stock' : 'In stock'}
                        </div>
                        <div class="text-right text-white">
                            <span class="rating flex items-center">
                                <i data-lucide="star" class="lucide-small mr-1"></i> ${product.rating}
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-end items-center">
                            <button class="heart" onclick="event.stopPropagation();">
                                <i data-lucide="heart"></i>
                            </button>
                        </div>
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        <div class="md:col-span-2">
                            <h6 class="product-title text-lg font-semibold">${product.name}</h6>
                            <p class="product-desc text-sm text-gray-600">${product.desc}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-gray-500 text-sm">Price</div>
                            <div class="price text-xl font-bold">₱${Math.floor(product.price).toLocaleString('en-US')}</div>
                        </div>
                    </div>
                </div>
                <div class="mt-auto flex p-4 justify-between">
                    <button class="btn btn-quick-view max-w-[45%] shrink flex items-center justify-center py-2 px-0" data-product-id="${product.id}">
                        <i data-lucide="proportions" class="lucide-small"></i> 
                        <span class="font-medium ml-2">Quick view</span>
                    </button>
                    <button class="btn btn-add-to-cart max-w-[45%] shrink flex items-center justify-center py-2 px-0" data-product-id="${product.id}">
                        <i data-lucide="shopping-cart" class="lucide-small"></i> 
                        <span class="font-medium ml-2">Add to cart</span>
                    </button>
                </div>
            </div>
            `;
            grid.appendChild(col);
        });

        // Re-init icons
        if (typeof lucide !== 'undefined') lucide.createIcons();
        if (typeof feather !== 'undefined') feather.replace();

        // Attach Quick View handlers
        initQuickViewModals();
    }
}

// ── Initialize Quick View Modals ──
function initQuickViewModals() {
    const buttons = document.querySelectorAll('.btn-quick-view');
    buttons.forEach(btn => {
        btn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const productId = parseInt(this.getAttribute('data-product-id'));
            const product = products.find(p => p.id === productId);
            if (!product) return;

            // Fill modal
            document.getElementById('quickViewLabel').textContent = product.name;
            document.getElementById('quick-view-image').src = product.image;
            document.getElementById('quick-view-desc').textContent = product.desc;
            document.getElementById('quick-view-rating').textContent = product.rating;
            document.getElementById('quick-view-price').textContent = `₱${Math.floor(product.price).toLocaleString('en-US')}`;
            document.getElementById('quick-view-material').textContent = product.material;
            document.getElementById('quick-view-dimensions').textContent = product.dimensions;

            // Show modal
            if (typeof window.showmodalquickview === 'function') {
                window.showmodalquickview();
            }

            // Re-init icons after modal opens
            setTimeout(() => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }, 100);
        });
    });
}

// ── Load Quick View Modal Component ──
async function loadQuickViewModal() {
    const container = document.getElementById('quick-view-container');
    if (!container) return;

    try {
        const response = await fetch('components/modal-quick-view.html');
        if (!response.ok) throw new Error('Failed to load quick view modal');

        container.innerHTML = await response.text();

        // Initialize Tailwind Modal
        const modalEl = document.getElementById('modal-quick-view');
        if (modalEl) {
            initModal('modal-quick-view');
        }

        // Re-init Lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

    } catch (error) {
        console.error('Error loading quick view modal:', error);
    }
}

// ── Initialize Navbar Buttons (after all components are loaded) ──
function initNavbarButtons() {
    // Search Modal Button
    const openSearchBtn = document.getElementById('openSearchModal');
    if (openSearchBtn) {
        openSearchBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            if (typeof window.showmodalsearch === 'function') {
                window.showmodalsearch();
            }
        });
    }

    // Mobile Search Modal Button
    const openSearchBtnMobile = document.getElementById('openSearchModalMobile');
    if (openSearchBtnMobile) {
        openSearchBtnMobile.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            if (typeof window.showmodalsearch === 'function') {
                window.showmodalsearch();
            }
        });
    }

    // Wishlist Offcanvas Button
    const openWishlistBtn = document.getElementById('openOffcanvas');
    if (openWishlistBtn) {
        openWishlistBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            if (typeof window.showoffcanvaswishlist === 'function') {
                window.showoffcanvaswishlist();
            }
        });
    }

    // Cart Offcanvas Button - handled in app.js with cart loading
    // Removed duplicate handler to prevent conflicts

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Account dropdown toggle with jQuery UI blind effect
    const accountDropdown = document.getElementById('account-dropdown');
    const accountMenu = document.getElementById('account-menu');
    if (accountDropdown && accountMenu) {
        // Initialize jQuery UI blind effect
        $(accountMenu).hide();
        
        accountDropdown.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            if ($(accountMenu).is(':visible')) {
                $(accountMenu).hide('blind', { direction: 'up' }, 300);
            } else {
                $(accountMenu).show('blind', { direction: 'up' }, 300);
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!accountDropdown.contains(event.target) && !accountMenu.contains(event.target)) {
                $(accountMenu).hide('blind', { direction: 'up' }, 300);
            }
        });
    }

    // Login and Signup Modal Buttons
    const openLoginBtn = document.getElementById('open-login-modal');
    const openSignupBtn = document.getElementById('open-signup-modal');
    
    if (openLoginBtn) {
        openLoginBtn.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            if (typeof window.showmodallogin === 'function') {
                window.showmodallogin();
            }
            // Close dropdown after opening modal
            if (accountMenu) {
                $(accountMenu).hide('blind', { direction: 'up' }, 300);
            }
        });
    }
    
    if (openSignupBtn) {
        openSignupBtn.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            if (typeof window.showmodalsignup === 'function') {
                window.showmodalsignup();
            }
            // Close dropdown after opening modal
            if (accountMenu) {
                $(accountMenu).hide('blind', { direction: 'up' }, 300);
            }
        });
    }
}

// ── Initialize Hero Slider (jQuery) ──
function initHeroSlider() {
    let currentIndex = 0;
    const $slides = $('.slide');
    const $indicators = $('.indicator');
    let autoSlideInterval;

    function showSlide(index) {
        $slides.removeClass('active');
        $indicators.removeClass('active');

        $slides.eq(index).addClass('active');
        $indicators.eq(index).addClass('active');

        // Update text
        const currentSlide = $slides.eq(index);
        $('#slide-title').text(currentSlide.data('name'));
        $('#slide-desc').text(currentSlide.data('price'));
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % $slides.length;
        showSlide(currentIndex);
    }

    // Auto-slide
    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 2000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    // Start auto-slide
    startAutoSlide();

    // Pause on hover
    $('.hero-slider').hover(
        function() { stopAutoSlide(); },
        function() { startAutoSlide(); }
    );

    // Dot click
    $indicators.click(function() {
        currentIndex = $(this).index();
        showSlide(currentIndex);
    });

    // Initialize
    showSlide(currentIndex);
}

// ── Preline Strong Password Integration ──
function initPrelineStrongPassword() {
    // Wait for Preline to be available
    const initPreline = () => {
        if (typeof HSCore !== 'undefined' && HSCore.components && HSCore.components.HSStrongPassword) {
            HSCore.components.HSStrongPassword.autoInit();
            console.log('Preline Strong Password initialized');
        } else {
            // Retry after a short delay
            setTimeout(initPreline, 100);
        }
    };
    
    initPreline();
}

// ── Password Strength Checker for Form Validation ──
function checkPasswordStrength(password) {
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };
    
    const validCount = Object.values(requirements).filter(Boolean).length;
    let strength = 'very-weak';
    
    if (validCount >= 5) {
        strength = 'very-strong';
    } else if (validCount >= 4) {
        strength = 'strong';
    } else if (validCount >= 3) {
        strength = 'medium';
    } else if (validCount >= 2) {
        strength = 'weak';
    }
    
    return { requirements, strength };
}

// ── Fallback Password Strength Indicator ──
function updatePasswordStrengthFallback(password, strength) {
    const indicator = document.getElementById('passwordStrengthIndicator');
    const bar = document.getElementById('password-strength-bar');
    const text = document.getElementById('password-strength-text');
    
    if (indicator && bar && text) {
        // Show indicator when user starts typing
        if (password.length > 0) {
            indicator.classList.remove('hidden');
            indicator.classList.add('opacity-100');
        } else {
            indicator.classList.add('hidden');
            indicator.classList.remove('opacity-100');
            return;
        }
        
        // Update progress bar and text based on strength
        let width = '0%';
        let bgColor = 'bg-gray-400';
        let textColor = 'text-gray-500';
        let strengthText = 'Very weak';
        
        switch (strength.strength) {
            case 'very-weak':
                width = '20%';
                bgColor = 'bg-red-500';
                textColor = 'text-red-500';
                strengthText = 'Very weak';
                break;
            case 'weak':
                width = '40%';
                bgColor = 'bg-orange-500';
                textColor = 'text-orange-500';
                strengthText = 'Weak';
                break;
            case 'medium':
                width = '60%';
                bgColor = 'bg-yellow-500';
                textColor = 'text-yellow-500';
                strengthText = 'Moderate';
                break;
            case 'strong':
                width = '80%';
                bgColor = 'bg-blue-500';
                textColor = 'text-blue-500';
                strengthText = 'Strong';
                break;
            case 'very-strong':
                width = '100%';
                bgColor = 'bg-green-500';
                textColor = 'text-green-500';
                strengthText = 'Very strong';
                break;
        }
        
        // Update the bar
        bar.style.width = width;
        bar.className = `h-2 rounded-full transition-all duration-300 ${bgColor}`;
        
        // Update the text
        text.textContent = strengthText;
        text.className = `text-sm ${textColor}`;
    }
}

// ── Initialize Login and Signup Modals ──
function initAuthModals() {
    // Password toggle functionality
    const togglePassword = (inputId, buttonId) => {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        if (input && button) {
            button.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                const icon = button.querySelector('i');
                icon.setAttribute('data-lucide', type === 'password' ? 'eye' : 'eye-off');
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        }
    };
    
    // Initialize password toggles
    togglePassword('login-password', 'toggle-login-password');
    togglePassword('signup-password', 'toggle-signup-password');
    togglePassword('signup-confirm-password', 'toggle-confirm-password');
    
    // Password strength checker for form validation
    const signupPassword = document.getElementById('signup-password');
    const confirmPasswordGroup = document.getElementById('confirm-password-group');
    const signupSubmit = document.getElementById('signup-submit');
    
    if (signupPassword) {
        signupPassword.addEventListener('input', function() {
            const password = this.value;
            const strength = checkPasswordStrength(password);
            
            // Fallback password strength indicator if Preline doesn't work
            updatePasswordStrengthFallback(password, strength);
            
            // Show confirm password field when user starts typing
            if (password.length > 0) {
                confirmPasswordGroup.classList.add('show');
            } else {
                confirmPasswordGroup.classList.remove('show');
                signupSubmit.disabled = true;
            }
        });
    }
    
    // Confirm password validation
    const confirmPassword = document.getElementById('signup-confirm-password');
    if (confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            const password = signupPassword.value;
            const confirm = this.value;
            const errorElement = document.getElementById('password-match-error');
            const strength = checkPasswordStrength(password);
            
            if (confirm && password !== confirm) {
                this.classList.add('is-invalid');
                errorElement.textContent = 'Passwords do not match';
                signupSubmit.disabled = true;
            } else if (confirm && password === confirm && strength.strength !== 'very-weak' && strength.strength !== 'weak') {
                this.classList.remove('is-invalid');
                errorElement.textContent = '';
                signupSubmit.disabled = false;
            } else if (confirm && password === confirm) {
                this.classList.remove('is-invalid');
                errorElement.textContent = '';
                signupSubmit.disabled = true; // Still disabled if password is too weak
            }
        });
    }
    
    // Modal switching
    const switchToSignup = document.getElementById('switch-to-signup');
    const switchToLogin = document.getElementById('switch-to-login');
    
    if (switchToSignup) {
        switchToSignup.addEventListener('click', function(e) {
            e.preventDefault();
            if (typeof window.hidemodallogin === 'function') {
                window.hidemodallogin();
            }
            setTimeout(() => {
                if (typeof window.showmodalsignup === 'function') {
                    window.showmodalsignup();
                }
            }, 300);
        });
    }
    
    if (switchToLogin) {
        switchToLogin.addEventListener('click', function(e) {
            e.preventDefault();
            if (typeof window.hidemodalsignup === 'function') {
                window.hidemodalsignup();
            }
            setTimeout(() => {
                if (typeof window.showmodallogin === 'function') {
                    window.showmodallogin();
                }
            }, 300);
        });
    }
    
    // Form submissions
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Login functionality would be implemented here!');
        });
    }
    
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Account creation functionality would be implemented here!');
        });
    }
}

// ── DOM Ready Handler ──
document.addEventListener('DOMContentLoaded', function () {

    // ── 0. LOAD NAVBAR ──
    loadComponent(
        'components/block-navbar.html',
        'navbar-container',
        () => {
            // Re-init icons
            if (typeof lucide !== 'undefined') lucide.createIcons();
            if (typeof feather !== 'undefined') feather.replace();
        }
    );

    // ── 1. SEARCH MODAL ──
    loadComponent(
        'components/modal-search.html',
        'modal-search-container',
        () => initModal('modal-search')
    );

    // ── 2. WISHLIST OFFCANVAS ──
    loadComponent(
        'components/offcanvas-wishlist.html',
        'offcanvas-container',
        () => initOffcanvas('offcanvas-wishlist')
    );

    // ── 3. CART OFFCANVAS ──
    loadComponent(
        'components/offcanvas-cart.html',
        'offcanvas-cart-container',
        () => initOffcanvas('offcanvas-cart')
    );

    // ── 4. PRODUCTS SECTION ──
    if (document.getElementById('product-grid')) {
        initProductsSection();
    }

    // ── 5. QUICK VIEW MODAL ──
    loadQuickViewModal();

    // ── 6. LOGIN MODAL ──
    loadComponent(
        'components/modal-login.html',
        'modal-login-container',
        () => initModal('modal-login')
    );

    // ── 7. SIGNUP MODAL ──
    loadComponent(
        'components/modal-signup.html',
        'modal-signup-container',
        () => initModal('modal-signup')
    );

    // ── 8. HERO SLIDER (jQuery) ──
    if ($('.hero-slider').length) {
        initHeroSlider();
    }

    // ── 9. AUTH MODALS ──
    setTimeout(() => {
        initAuthModals();
        initPrelineStrongPassword();
    }, 1000); // Delay to ensure modals are loaded

    // ── 10. LOAD FOOTER ──
    loadComponent(
        'components/block-footer.html',
        'footer-container',
        () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const contactForm = document.getElementById('contact-form');
            if (contactForm) {
                contactForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    alert('Thank you for your message! We’ll respond soon.');
                });
            }

            const newsletterForm = document.querySelector('.newsletter-form');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    alert('Thank you for subscribing!');
                });
            }
        }
    );

    // ✅ Initialize Navbar Buttons (after all components are loaded)
    setTimeout(initNavbarButtons, 500); // Small delay to ensure components are ready
});