// Main Application JavaScript - Updated to use API
import { products } from '../data/products.js';

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
    const el = document.getElementById(id);
    const panel = document.getElementById(id + '-panel');
    
    if (!el) {
        return;
    }

    if (!panel) {
        return;
    }

    const simpleName = id.replace('offcanvas-', '').replace(/-/g, '');
    const idBasedName = id.replace(/-/g, '');
    const closeBtn = document.getElementById('close-' + simpleName + '-offcanvas');

    // Create global functions for show/hide
    const show = function() {
        el.style.display = 'block';
        el.classList.remove('hidden');
        
        // Add backdrop fade-in animation
        el.style.opacity = '0';
        el.style.transition = 'opacity 0.3s ease-in-out';
        
        requestAnimationFrame(() => {
            el.style.opacity = '1';
            
            setTimeout(() => {
                panel.classList.remove('translate-x-full');
                panel.classList.add('translate-x-0');
            }, 10);
        });
    };

    const hide = function() {
        // Add smooth slide-out animation
        panel.classList.remove('translate-x-0');
        panel.classList.add('translate-x-full');
        
        // Fade out backdrop
        el.style.opacity = '0';
        el.style.transition = 'opacity 0.3s ease-in-out';
        
        setTimeout(() => {
            el.style.opacity = '';
            el.style.transition = '';
            el.style.display = 'none';
            el.classList.add('hidden');
        }, 300);
    };

    // Create global function references
    window['show' + simpleName] = show;
    window['hide' + simpleName] = hide;
    window['show' + idBasedName] = show;
    window['hide' + idBasedName] = hide;

    // Bind close button
    if (closeBtn) {
        closeBtn.addEventListener('click', hide);
    }

    // Close when clicking on background
    el.addEventListener('click', function (event) {
        if (event.target === el) {
            hide();
        }
    });

}

// ── Initialize Tailwind Modal ──
function initModal(id) {
    const el = document.getElementById(id);
    if (!el) {
        return;
    }

    const simpleName = id.replace('modal-', '').replace(/-/g, '');
    const idBasedName = id.replace(/-/g, '');
    const closeBtn = document.getElementById('close-' + simpleName + '-modal');

    // Create global functions for show/hide
    const show = function() { 
        el.style.display = 'block';
        el.style.opacity = '0';
        
        // Small delay for CSS animations
        setTimeout(() => {
            el.style.transition = 'opacity 0.3s ease';
            el.style.opacity = '1';
        }, 10);
    };

    const hide = function() { 
        el.style.transition = 'opacity 0.3s ease';
        el.style.opacity = '0';
        
        setTimeout(() => {
            el.style.display = 'none';
        }, 300);
    };

    // Create global function references
    window['show' + simpleName] = show;
    window['hide' + simpleName] = hide;
    window['show' + idBasedName] = show;
    window['hide' + idBasedName] = hide;

    // Bind close button
    if (closeBtn) {
        closeBtn.addEventListener('click', hide);
    }

    // Close when clicking outside modal
    el.addEventListener('click', function(e) {
        if (e.target === el) {
            hide();
        }
    });

}

// ── Initialize Products Section with API ──
async function initProductsSection() {
    const grid = document.getElementById('product-grid');
    if (!grid) return;

    try {
        // Load products from API
        const response = await window.api.getProducts({ per_page: 8 });
        const apiProducts = response.data.data;

        // Fallback to local products if API fails
        const productsToUse = apiProducts.length > 0 ? apiProducts : products.slice(0, 8);

        // Initial render
        renderProductsWithFilter(productsToUse);

        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filter = btn.getAttribute('data-filter');
                
                try {
                    const filterParams = filter === 'all' ? {} : { category: filter };
                    const response = await window.api.getProducts({ ...filterParams, per_page: 8 });
                    const filteredProducts = response.data.data;
                    renderProductsWithFilter(filteredProducts);
                } catch (error) {
                    console.error('Filter error:', error);
                    // Show error message instead of fallback
                    showNotification('Unable to filter products. Please try again.', 'error');
                }
            });
        });

        // Sort dropdown
        document.getElementById('sort-select').addEventListener('change', async () => {
            const sort = document.getElementById('sort-select').value;
            
            try {
                const response = await window.api.getProducts({ sort, per_page: 8 });
                const sortedProducts = response.data.data;
                renderProductsWithFilter(sortedProducts);
            } catch (error) {
                console.error('Sort error:', error);
                // Show error message instead of fallback
                showNotification('Unable to sort products. Please try again.', 'error');
            }
        });

    } catch (error) {
        console.error('Error loading products:', error);
        // Fallback to local products
        renderProductsWithFilter(products.slice(0, 8));
    }

    // Render function
    function renderProductsWithFilter(products) {
        grid.innerHTML = '';

        products.forEach(product => {
            const col = document.createElement('div');
            col.className = 'w-full';

            // Handle both API and local product formats
            const productData = {
                id: product.id,
                name: product.name,
                description: product.description || product.desc,
                price: product.price,
                image: product.primary_image || product.images?.[0]?.url || product.image,
                rating: product.rating || 4.5,
                stock: product.stock_status || product.stock,
                material: product.material,
                dimensions: product.dimensions,
                slug: product.slug
            };

            col.innerHTML = `
                <div class="card product-card flex flex-col h-full rounded-2xl border bg-white">
                    <img src="${productData.image}" class="w-full h-64 object-cover" alt="${productData.name}">
                    <div class="absolute inset-0 flex p-4 h-full">
                        <div class="flex-1">
                            <div class="rounded-full stock-badge ${productData.stock === 'low' ? 'low' : 'in-stock'} px-3 py-1 text-xs font-medium">
                            ${productData.stock === 'low' ? 'Low stock' : 'In stock'}
                        </div>
                        <div class="text-right text-white">
                            <span class="rating flex items-center">
                                <i data-lucide="star" class="lucide-small mr-1"></i> ${productData.rating}
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-end items-center">
                            <button class="heart wishlist-btn" data-product-id="${productData.id}" onclick="event.stopPropagation();">
                                <i data-lucide="heart"></i>
                            </button>
                        </div>
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        <div class="md:col-span-2">
                            <h6 class="product-title text-lg font-semibold">${productData.name}</h6>
                            <p class="product-desc text-sm text-gray-600">${productData.description}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-gray-500 text-sm">Price</div>
                            <div class="price text-xl font-bold">₱${Math.floor(productData.price).toLocaleString('en-US')}</div>
                        </div>
                    </div>
                </div>
                <div class="mt-auto flex p-4 justify-between">
                    <button class="btn btn-quick-view max-w-[45%] shrink flex items-center justify-center py-2 px-0" data-product-id="${productData.id}" data-product-slug="${productData.slug}">
                        <i data-lucide="proportions" class="lucide-small"></i> 
                        <span class="font-medium ml-2">Quick view</span>
                    </button>
                    <button class="btn btn-add-to-cart max-w-[45%] shrink flex items-center justify-center py-2 px-0" data-product-id="${productData.id}">
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

        // Attach event handlers
        initQuickViewModals();
        initAddToCartButtons();
        initWishlistButtons();
    }
}

// ── Initialize Add to Cart Buttons ──
function initAddToCartButtons() {
    const buttons = document.querySelectorAll('.btn-add-to-cart');
    buttons.forEach(btn => {
        btn.addEventListener('click', async function (event) {
            event.preventDefault();
            event.stopPropagation();

            const productId = parseInt(this.getAttribute('data-product-id'));
            
            if (!window.authManager.isAuthenticated) {
                alert('Please login to add items to cart');
                if (typeof window.showmodallogin === 'function') {
                    window.showmodallogin();
                }
                return;
            }

            try {
                const response = await window.api.addToCart(productId, 1);
                if (response.success) {
                    // Show success message
                    showNotification('Item added to cart!', 'success');
                    // Update cart count in navbar
                    updateCartCount();
                }
            } catch (error) {
                showNotification(error.message, 'error');
            }
        });
    });
}

// ── Initialize Wishlist Buttons ──
function initWishlistButtons() {
    const buttons = document.querySelectorAll('.wishlist-btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', async function (event) {
            event.preventDefault();
            event.stopPropagation();

            const productId = parseInt(this.getAttribute('data-product-id'));
            
            if (!window.authManager.isAuthenticated) {
                alert('Please login to add items to wishlist');
                if (typeof window.showmodallogin === 'function') {
                    window.showmodallogin();
                }
                return;
            }

            try {
                // Check if already in wishlist
                const checkResponse = await window.api.checkWishlist(productId);
                const inWishlist = checkResponse.data.in_wishlist;

                if (inWishlist) {
                    await window.api.removeFromWishlist(productId);
                    showNotification('Removed from wishlist', 'info');
                } else {
                    await window.api.addToWishlist(productId);
                    showNotification('Added to wishlist!', 'success');
                }

                // Update button appearance
                const icon = this.querySelector('i');
                if (inWishlist) {
                    icon.setAttribute('data-lucide', 'heart');
                    this.classList.remove('active');
                } else {
                    icon.setAttribute('data-lucide', 'heart');
                    this.classList.add('active');
                }
                
                if (typeof lucide !== 'undefined') lucide.createIcons();
            } catch (error) {
                showNotification(error.message, 'error');
            }
        });
    });
}

// ── Initialize Quick View Modals ──
function initQuickViewModals() {
    const buttons = document.querySelectorAll('.btn-quick-view');
    buttons.forEach(btn => {
        btn.addEventListener('click', async function (event) {
            event.preventDefault();
            event.stopPropagation();

            const productId = parseInt(this.getAttribute('data-product-id'));
            const productSlug = this.getAttribute('data-product-slug');

            try {
                // Try to get product from API first
                let product;
                if (productSlug) {
                    const response = await window.api.getProduct(productSlug);
                    product = response.data;
                } else {
                    // Fallback to local products
                    product = products.find(p => p.id === productId);
                }

                if (!product) return;

                // Fill modal
                document.getElementById('quickViewLabel').textContent = product.name;
                document.getElementById('quick-view-image').src = product.primary_image || product.image;
                document.getElementById('quick-view-desc').textContent = product.description || product.desc;
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
            } catch (error) {
                console.error('Error loading product details:', error);
            }
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

        const modalEl = document.getElementById('modal-quick-view');
        if (modalEl) {
            initModal('modal-quick-view');
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

    } catch (error) {
        console.error('Error loading quick view modal:', error);
    }
}

// ── Initialize Navbar Buttons ──
function initNavbarButtons() {
    
    // Search Modal Button - Fixed
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

    // Cart Offcanvas Button
    const openCartBtn = document.getElementById('openCartOffcanvas');
    if (openCartBtn) {
        openCartBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            if (typeof window.showoffcanvascart === 'function') {
                window.showoffcanvascart();
            }
        });
    }

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Account dropdown toggle - Fixed with vanilla JS
    const accountDropdown = document.getElementById('account-dropdown');
    const accountMenu = document.getElementById('account-menu');
    if (accountDropdown && accountMenu) {
        // Start with menu hidden
        accountMenu.style.display = 'none';
        
        accountDropdown.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            if (accountMenu.style.display === 'none' || accountMenu.style.display === '') {
                accountMenu.style.display = 'block';
                accountMenu.style.opacity = '0';
                accountMenu.style.transform = 'translateY(-10px)';
                
                // Trigger reflow
                accountMenu.offsetHeight;
                
                // Animate in
                accountMenu.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                accountMenu.style.opacity = '1';
                accountMenu.style.transform = 'translateY(0)';
            } else {
                // Animate out
                accountMenu.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                accountMenu.style.opacity = '0';
                accountMenu.style.transform = 'translateY(-10px)';
                
                setTimeout(() => {
                    accountMenu.style.display = 'none';
                }, 200);
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!accountDropdown.contains(event.target) && !accountMenu.contains(event.target)) {
                accountMenu.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                accountMenu.style.opacity = '0';
                accountMenu.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    accountMenu.style.display = 'none';
                }, 200);
            }
        });
    }

    // Login and Signup Modal Buttons - Fixed
    const openLoginBtn = document.getElementById('open-login-modal');
    const openSignupBtn = document.getElementById('open-signup-modal');
    
    if (openLoginBtn) {
        openLoginBtn.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            console.log('Login button clicked');
            
            if (typeof window.showmodallogin === 'function') {
                window.showmodallogin();
            } else {
                console.log('showmodallogin function not found');
                // Try alternative method
                const modal = document.getElementById('modal-login');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.style.opacity = '1';
                }
            }
            
            // Close account menu
            if (accountMenu) {
                accountMenu.style.display = 'none';
            }
        });
    } else {
        console.log('Login button not found');
    }
    
    if (openSignupBtn) {
        openSignupBtn.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            console.log('Signup button clicked');
            
            if (typeof window.showmodalsignup === 'function') {
                window.showmodalsignup();
            } else {
                console.log('showmodalsignup function not found');
                // Try alternative method
                const modal = document.getElementById('modal-signup');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.style.opacity = '1';
                }
            }
            
            // Close account menu
            if (accountMenu) {
                accountMenu.style.display = 'none';
            }
        });
    } else {
        console.log('Signup button not found');
    }
}

// ── Initialize Hero Slider ──
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

        const currentSlide = $slides.eq(index);
        $('#slide-title').text(currentSlide.data('name'));
        $('#slide-desc').text(currentSlide.data('price'));
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % $slides.length;
        showSlide(currentIndex);
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 2000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    startAutoSlide();

    $('.hero-slider').hover(
        function() { stopAutoSlide(); },
        function() { startAutoSlide(); }
    );

    $indicators.click(function() {
        currentIndex = $(this).index();
        showSlide(currentIndex);
    });

    showSlide(currentIndex);
}

// ── Initialize Authentication Modals ──
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
    
    togglePassword('login-password', 'toggle-login-password');
    togglePassword('signup-password', 'toggle-signup-password');
    togglePassword('signup-confirm-password', 'toggle-confirm-password');

    // ── Dynamic Password Validation Functionality ──
    function initPasswordValidation() {
        const passwordInput = document.getElementById('signup-password');
        const strengthMeter = document.getElementById('password-strength-meter');
        const strengthBar = document.getElementById('password-strength-bar');
        const strengthText = document.getElementById('password-strength-text');
        const tooltip = document.getElementById('password-strength-tooltip');
        
        if (!passwordInput || !strengthMeter || !strengthBar || !strengthText || !tooltip) {
            console.log('Password validation elements not found');
            return;
        }

        // Password validation rules
        const passwordRules = {
            length: { test: (pwd) => pwd.length >= 8, message: "At least 8 characters" },
            lowercase: { test: (pwd) => /[a-z]/.test(pwd), message: "At least one lowercase letter" },
            uppercase: { test: (pwd) => /[A-Z]/.test(pwd), message: "At least one uppercase letter" },
            number: { test: (pwd) => /[0-9]/.test(pwd), message: "At least one number" },
            special: { test: (pwd) => /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(pwd), message: "At least one special character" }
        };

        // Show/hide validation elements based on focus
        function showPasswordValidation() {
            strengthMeter.classList.remove('hidden', 'opacity-0');
            strengthMeter.classList.add('opacity-100');
            tooltip.classList.remove('hidden', 'opacity-0');
            tooltip.classList.add('opacity-100');
            tooltip.setAttribute('aria-hidden', 'false');
        }

        function hidePasswordValidation() {
            strengthMeter.classList.remove('opacity-100');
            strengthMeter.classList.add('opacity-0');
            tooltip.classList.remove('opacity-100');
            tooltip.classList.add('opacity-0');
            tooltip.setAttribute('aria-hidden', 'true');
            
            // Hide after animation completes
            setTimeout(() => {
                strengthMeter.classList.add('hidden');
                tooltip.classList.add('hidden');
            }, 300);
        }

        // Calculate password strength and update UI
        function updatePasswordStrength(password) {
            const rules = Object.keys(passwordRules);
            const metRules = rules.filter(rule => passwordRules[rule].test(password));
            const score = (metRules.length / rules.length) * 100;
            
            // Update progress bar
            strengthBar.style.width = `${score}%`;
            strengthBar.setAttribute('aria-valuenow', score);
            
            // Update colors and text based on strength
            if (score === 0) {
                strengthBar.className = 'bg-red-500 h-2 rounded-full transition-all duration-300';
                strengthText.textContent = 'Very weak';
                strengthText.className = 'text-sm font-medium text-red-500';
            } else if (score < 40) {
                strengthBar.className = 'bg-red-500 h-2 rounded-full transition-all duration-300';
                strengthText.textContent = 'Weak';
                strengthText.className = 'text-sm font-medium text-red-500';
            } else if (score < 60) {
                strengthBar.className = 'bg-orange-500 h-2 rounded-full transition-all duration-300';
                strengthText.textContent = 'Fair';
                strengthText.className = 'text-sm font-medium text-orange-500';
            } else if (score < 80) {
                strengthBar.className = 'bg-yellow-500 h-2 rounded-full transition-all duration-300';
                strengthText.textContent = 'Good';
                strengthText.className = 'text-sm font-medium text-yellow-500';
            } else {
                strengthBar.className = 'bg-green-500 h-2 rounded-full transition-all duration-300';
                strengthText.textContent = 'Strong';
                strengthText.className = 'text-sm font-medium text-green-500';
            }

            // Update rule indicators
            rules.forEach(rule => {
                const ruleElement = tooltip.querySelector(`[data-rule="${rule}"]`);
                if (ruleElement) {
                    const icon = ruleElement.querySelector('.rule-icon');
                    const isMet = passwordRules[rule].test(password);
                    
                    if (isMet) {
                        icon.setAttribute('data-lucide', 'check');
                        icon.className = 'w-4 h-4 text-green-500 rule-icon transition-colors duration-200';
                    } else {
                        icon.setAttribute('data-lucide', 'x');
                        icon.className = 'w-4 h-4 text-red-500 rule-icon transition-colors duration-200';
                    }
                }
            });

            // Re-init lucide icons for rule indicator updates
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Track focus state and mouse interactions
        let isFocused = false;
        let validationTimeout = null;

        // Event listeners
        passwordInput.addEventListener('focus', function() {
            isFocused = true;
            showPasswordValidation();
        });

        // Helper to check if mouse is over tooltip
        let isMouseOverTooltip = false;

        passwordInput.addEventListener('blur', function() {
            isFocused = false;
            // Small delay to prevent flickering when clicking on tooltip
            clearTimeout(validationTimeout);
            validationTimeout = setTimeout(() => {
                if (!isFocused && !isMouseOverTooltip) {
                    hidePasswordValidation();
                }
            }, 150);
        });
        tooltip.addEventListener('mouseenter', function() {
            isMouseOverTooltip = true;
            showPasswordValidation();
        });

        tooltip.addEventListener('mouseleave', function() {
            isMouseOverTooltip = false;
            if (!isFocused) {
                hidePasswordValidation();
            }
        });

        passwordInput.addEventListener('input', function() {
            if (!tooltip.classList.contains('hidden')) {
                updatePasswordStrength(this.value);
            }
        });

        // Handle escape key
        passwordInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hidePasswordValidation();
                this.blur();
            }
        });

        // Monitor click outside to hide validation
        document.addEventListener('click', function(e) {
            if (!passwordInput.contains(e.target) && !tooltip.contains(e.target)) {
                // Only hide if not focused and not hovering over tooltip
                if (!isFocused && !isMouseOverTooltip) {
                    hidePasswordValidation();
                }
            }
        });

        // Initialization
        console.log('Password validation initialized');
    }

    // Initialize password validation
    setTimeout(initPasswordValidation, 100);
    
    // Form submissions
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');
    
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const username = document.getElementById('login-username').value;
            const password = document.getElementById('login-password').value;
            
            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ username, password })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Login successful!', 'success');
                    if (typeof window.hidemodallogin === 'function') {
                        window.hidemodallogin();
                    }
                    // Reload the page to update navbar dynamically
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (error) {
                showNotification('Login failed. Please try again.', 'error');
            }
        });
    }
    
    // Enable/disable signup button based on form validation
    const signupButton = document.getElementById('signup-submit');
    console.log('🔧 ACTUAL SIGNUP BUTTON DETECTION:', !!signupButton);
    if (signupButton && signupForm) {
        function updateSignupButtonState() {
            const firstName = document.getElementById('signup-firstname');
            const lastName = document.getElementById('signup-lastname');
            const email = document.getElementById('signup-email');
            const username = document.getElementById('signup-username');
            const password = document.getElementById('signup-password');
            const confirmPassword = document.getElementById('signup-confirm-password');
            
            if (!firstName || !lastName || !email || !username || !password || !confirmPassword) return;
            
            const firstNameVal = firstName.value.trim();
            const lastNameVal = lastName.value.trim();
            const emailVal = email.value.trim();
            const usernameVal = username.value.trim();
            const passwordVal = password.value.trim();
            const confirmPasswordVal = confirmPassword.value.trim();
            
            const allFieldsFilled = firstNameVal.length > 0 && lastNameVal.length > 0 && emailVal.length > 0 && usernameVal.length > 0 && passwordVal.length > 0 && confirmPasswordVal.length > 0;
            const passwordsMatch = passwordVal === confirmPasswordVal && passwordVal.length > 0 && confirmPasswordVal.length > 0;
            const emailValid = emailVal.includes('@') && emailVal.includes('.');
            const validPasswordLength = passwordVal.length >= 8;
            
            const shouldEnable = allFieldsFilled && passwordsMatch && emailValid && validPasswordLength;
            
            if (shouldEnable) {
                signupButton.disabled = false;
                signupButton.classList.remove('opacity-50');
                signupButton.style.opacity = '1';
                console.log('✅ BUTTON ENABLED BY PASSWORD MATCH');
            } else {
                signupButton.disabled = true;
                signupButton.classList.add('opacity-50');
                console.log('❌ BUTTON DISABLED - validation failed');
            }
        }
        
        // Watch all input fields for changes
        const firstNameField = document.getElementById('signup-firstname');
        const lastNameField = document.getElementById('signup-lastname');
        const emailField = document.getElementById('signup-email');
        const usernameField = document.getElementById('signup-username');
        const passwordField = document.getElementById('signup-password');
        const confirmPasswordField = document.getElementById('signup-confirm-password');
        
        const fields = [firstNameField, lastNameField, emailField, usernameField, passwordField, confirmPasswordField];
        fields.forEach(field => {
            if (field) {
                field.addEventListener('input', updateSignupButtonState);
                field.addEventListener('keyup', updateSignupButtonState);
                field.addEventListener('change', updateSignupButtonState);
                field.addEventListener('paste', () => setTimeout(updateSignupButtonState, 100));
            }
        });
        
        // Check confirm password specifically every 100ms
        if (confirmPasswordField) {
            confirmPasswordField.addEventListener('input', function() {
                console.log('⚡ CONFIRM PASSWORD INPUT EVENT', this.value);
                setTimeout(updateSignupButtonState, 10);
            });
        }
        
        // Initial check
        setTimeout(updateSignupButtonState, 100);
    }
    
    if (signupForm) {
        signupForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const userData = {
                firstName: document.getElementById('signup-firstname').value,
                lastName: document.getElementById('signup-lastname').value,
                email: document.getElementById('signup-email').value,
                username: document.getElementById('signup-username').value,
                password: document.getElementById('signup-password').value,
                confirmPassword: document.getElementById('signup-confirm-password').value,
            };
            
            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(userData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Registration successful!', 'success');
                    if (typeof window.hidemodalsignup === 'function') {
                        window.hidemodalsignup();
                    }
                    // Reload the page to update navbar dynamically
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification(result.message, 'error');
                    if (result.errors) {
                        // Display validation errors if available
                        Object.values(result.errors).forEach(errorArray => {
                            errorArray.forEach(error => {
                                showNotification(error, 'error');
                            });
                        });
                    }
                }
            } catch (error) {
                showNotification('Registration failed. Please try again.', 'error');
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
    
    // Add logout functionality
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'logout-btn') {
            e.preventDefault();
            logout();
        }
    });
}

// ── Logout Function ──
async function logout() {
    try {
        const response = await fetch('/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Logout successful!', 'success');
            // Reload the page to update navbar dynamically
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(result.message || 'Logout failed', 'error');
        }
    } catch (error) {
        showNotification('Logout failed. Please try again.', 'error');
    }
}

// ── Utility Functions ──
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-black' :
        'bg-blue-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button class="ml-2 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Re-init icons
    if (typeof lucide !== 'undefined') lucide.createIcons();
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

function updateCartCount() {
    // This would be called after cart operations to update the cart count in navbar
    // Implementation depends on your navbar structure
    console.log('Cart count updated');
}

// ── Initialize all modals and offcanvas components ──
function initializeAllComponents() {
    // Initialize all modals that exist
    const modals = ['modal-search', 'modal-login', 'modal-signup', 'modal-quick-view'];
    modals.forEach(modal => {
        const modalElement = document.getElementById(modal);
        if (modalElement) {
            initModal(modal);
        }
    });

    // Initialize all offcanvas components
    const offcanvasComponents = ['offcanvas-wishlist', 'offcanvas-cart'];
    offcanvasComponents.forEach(offcanvas => {
        const offcanvasElement = document.getElementById(offcanvas);
        if (offcanvasElement) {
            initOffcanvas(offcanvas);
        }
    });
}

// ── Fix Smooth Scrolling for Anchor Links ──
function initSmoothScrolling() {
    // Fix anchor link scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#') {
                e.preventDefault();
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
}

// ── Initialize Products from Database ──
async function initDatabaseProducts() {
    const grid = document.getElementById('product-grid');
    if (!grid) return;

    try {
        // Try to get products from Laravel backend first
        const baseUrl = window.location.origin;
        const apiUrl = `${baseUrl}/api/products`;
        
        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            const data = await response.json();
            console.log('API Response:', data);
            
            if (data.success && data.data && data.data.length > 0) {
                renderProductsFromDatabase(data.data);
                return;
            }
        }
    } catch (error) {
        console.log('API not available, using fallback products. Error:', error);
    }

    // Fallback to hardcoded products if API fails
    try {
        const fallbackProducts = [
            {
                id: 1,
                name: "Handcrafted Chair",
                description: "Beautiful handcrafted wooden chair",
                price: 12999,
                image: "/frontend/assets/chair.png",
                category: "chairs",
                rating: 4.5,
                stock: "in-stock",
                material: "Solid Oak Wood",
                dimensions: "24L x 18W x 32H inches",
                slug: "handcrafted-chair"
            },
            {
                id: 2,
                name: "Modern Cabinet",
                description: "Contemporary wooden cabinet with clean lines",
                price: 24500,
                image: "/frontend/assets/cabinet.png",
                category: "cabinets",
                rating: 4.8,
                stock: "in-stock",
                material: "Walnut Wood",
                dimensions: "48L x 20W x 35H inches",
                slug: "modern-cabinet"
            },
            {
                id: 3,
                name: "Elegant Floor Lamp",
                description: "Handcrafted wooden floor lamp base",
                price: 16900,
                image: "/frontend/assets/floor-lamp.png",
                category: "lighting",
                rating: 4.7,
                stock: "in-stock",
                material: "Pine Wood",
                dimensions: "12L x 12W x 66H inches",
                slug: "elegant-floor-lamp"
            }
        ];
        
        renderProductsFromDatabase(fallbackProducts);
    } catch (error) {
        console.error('Error loading products:', error);
        grid.innerHTML = '<p>Unable to load products. Please try again later.</p>';
    }
}

// ── Render Products from Database ──
function renderProductsFromDatabase(products) {
    const grid = document.getElementById('product-grid');
    if (!grid) return;

    grid.innerHTML = '';
    
    products.forEach(product => {
        const productElement = document.createElement('div');
        productElement.className = 'w-full';

        // Handle product data safely
        const productData = {
            id: product.id,
            name: product.name || 'Unnamed Product',
            description: product.description || product.desc || 'No description available',
            price: parseFloat(product.price) || 0,
            image: product.image || product.primary_image || '/frontend/assets/chair.png',
            rating: parseFloat(product.rating) || 4.5,
            stock: product.stock || product.stock_status || 'in-stock',
            material: product.material || 'Wood',
            dimensions: product.dimensions || 'Contact for specs',
            category: product.category || 'furniture',
            slug: product.slug || `product-${product.id}`
        };

        productElement.innerHTML = `
            <div class="card product-card flex flex-col h-full rounded-2xl border bg-white">
                <img src="${productData.image}" class="w-full h-64 object-cover rounded-t-2xl" alt="${productData.name}">
                <div class="absolute top-2 left-2 right-2 flex justify-between">
                    <div class="rounded-full stock-badge ${productData.stock === 'low' ? 'bg-red-500' : 'bg-green-500'} text-white px-3 py-1 text-xs font-medium">
                        ${productData.stock === 'low' ? 'Low stock' : 'In stock'}
                    </div>
                    <div class="text-right text-white bg-black bg-opacity-50 px-2 py-1 rounded">
                        <span class="rating flex items-center">
                            <i data-lucide="star" class="lucide-small mr-1"></i>${productData.rating}
                        </span>
                    </div>
                </div>
                <div class="flex justify-end items-center absolute top-2 right-2">
                    <button class="heart wishlist-btn bg-white rounded-full p-2 shadow-md" data-product-id="${productData.id}" onclick="event.stopPropagation();">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            <div class="p-4 flex flex-col flex-1">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                    <div class="md:col-span-2">
                        <h6 class="product-title text-lg font-semibold">${productData.name}</h6>
                        <p class="product-desc text-sm text-gray-600">${productData.description.substring(0, 80)}...</p>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-500 text-sm">Price</div>
                        <div class="price text-xl font-bold">₱${Math.floor(productData.price).toLocaleString('en-US')}</div>
                    </div>
                </div>
            </div>
            <div class="mt-auto flex p-4 justify-between">
                <button class="btn btn-quick-view max-w-[45%] shrink flex items-center justify-center py-2 px-2" data-product-id="${productData.id}" data-product-slug="${productData.slug}">
                    <i data-lucide="eye" class="lucide-small mr-1"></i> 
                    <span class="font-medium text-xs">Quick view</span>
                </button>
                <button class="btn btn-add-to-cart max-w-[45%] shrink flex items-center justify-center py-2 px-2" data-product-id="${productData.id}">
                    <i data-lucide="shopping-cart" class="lucide-small mr-1"></i> 
                    <span class="font-medium text-xs">Add to cart</span>
                </button>
            </div>
        `;

        grid.appendChild(productElement);
    });

    // Re-initialize icons
    setTimeout(() => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Attach event handlers
        initQuickViewModals();
        initAddToCartButtons();
        initWishlistButtons();
    }, 100);
}

// ── DOM Ready Handler ──
document.addEventListener('DOMContentLoaded', async function () {
    console.log('DOM loaded, initializing application...');
    
    // Check authentication status
    if (window.authManager) {
        await window.authManager.checkAuth();
    }

    // Initialize all modals and offcanvas 
    initializeAllComponents();
    
    // Initialize navbar buttons
    initNavbarButtons();
    
    // Fix anchor scrolling
    initSmoothScrolling();

    // Initialize hero slider
    if ($('.hero-slider').length) {
        initHeroSlider();
    }

    // Initialize auth modals
    initAuthModals();

    // Initialize products from database
    if (document.getElementById('product-grid')) {
        initDatabaseProducts();
    }

    await loadQuickViewModal();
    
    console.log('Application initialized successfully');
});

                }



                // Re-init icons after modal opens

                setTimeout(() => {

                    if (typeof lucide !== 'undefined') lucide.createIcons();

                }, 100);

            } catch (error) {

                console.error('Error loading product details:', error);

            }

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



        const modalEl = document.getElementById('modal-quick-view');

        if (modalEl) {

            initModal('modal-quick-view');

        }



        if (typeof lucide !== 'undefined') {

            lucide.createIcons();

        }



    } catch (error) {

        console.error('Error loading quick view modal:', error);

    }

}



// ── Initialize Navbar Buttons ──

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



    // Cart Offcanvas Button

    const openCartBtn = document.getElementById('openCartOffcanvas');

    if (openCartBtn) {

        openCartBtn.addEventListener('click', function (event) {

            event.preventDefault();

            event.stopPropagation();

            if (typeof window.showoffcanvascart === 'function') {

                window.showoffcanvascart();

            }

        });

    }



    // Mobile menu toggle

    const mobileMenuBtn = document.getElementById('mobile-menu-button');

    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {

        mobileMenuBtn.addEventListener('click', function() {

            mobileMenu.classList.toggle('hidden');

        });

    }



    // Account dropdown toggle

    const accountDropdown = document.getElementById('account-dropdown');

    const accountMenu = document.getElementById('account-menu');

    if (accountDropdown && accountMenu) {

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

            if (accountMenu) {

                $(accountMenu).hide('blind', { direction: 'up' }, 300);

            }

        });

    }

}



// ── Initialize Hero Slider ──

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



        const currentSlide = $slides.eq(index);

        $('#slide-title').text(currentSlide.data('name'));

        $('#slide-desc').text(currentSlide.data('price'));

    }



    function nextSlide() {

        currentIndex = (currentIndex + 1) % $slides.length;

        showSlide(currentIndex);

    }



    function startAutoSlide() {

        autoSlideInterval = setInterval(nextSlide, 2000);

    }



    function stopAutoSlide() {

        clearInterval(autoSlideInterval);

    }



    startAutoSlide();



    $('.hero-slider').hover(

        function() { stopAutoSlide(); },

        function() { startAutoSlide(); }

    );



    $indicators.click(function() {

        currentIndex = $(this).index();

        showSlide(currentIndex);

    });



    showSlide(currentIndex);

}



// ── Initialize Authentication Modals ──

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

    

    togglePassword('login-password', 'toggle-login-password');

    togglePassword('signup-password', 'toggle-signup-password');

    togglePassword('signup-confirm-password', 'toggle-confirm-password');

    

    // Form submissions

    const loginForm = document.getElementById('login-form');

    const signupForm = document.getElementById('signup-form');

    

    if (loginForm) {

        loginForm.addEventListener('submit', async function(e) {

            e.preventDefault();

            

            const email = document.getElementById('login-username').value; // Using email field

            const password = document.getElementById('login-password').value;

            

            const result = await window.authManager.login(email, password);

            

            if (result.success) {

                showNotification('Login successful!', 'success');

                if (typeof window.hidemodallogin === 'function') {

                    window.hidemodallogin();

                }

            } else {

                showNotification(result.message, 'error');

            }

        });

    }

    

    if (signupForm) {

        signupForm.addEventListener('submit', async function(e) {

            e.preventDefault();

            

            const userData = {

                firstName: document.getElementById('signup-firstname').value,

                lastName: document.getElementById('signup-lastname').value,

                email: document.getElementById('signup-email').value,

                username: document.getElementById('signup-username').value,

                password: document.getElementById('signup-password').value,

                password_confirmation: document.getElementById('signup-confirm-password').value,

            };

            

            const result = await window.authManager.register(userData);

            

            if (result.success) {

                showNotification('Registration successful!', 'success');

                if (typeof window.hidemodalsignup === 'function') {

                    window.hidemodalsignup();

                }

            } else {

                showNotification(result.message, 'error');

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

}



// ── Utility Functions ──

function showNotification(message, type = 'info') {

    // Create notification element

    const notification = document.createElement('div');

    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${

        type === 'success' ? 'bg-green-500 text-white' :

        type === 'error' ? 'bg-red-500 text-white' :

        type === 'warning' ? 'bg-yellow-500 text-black' :

        'bg-blue-500 text-white'

    }`;

    

    notification.innerHTML = `

        <div class="flex items-center">

            <span>${message}</span>

            <button class="ml-2 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">

                <i data-lucide="x" class="w-4 h-4"></i>

            </button>

        </div>

    `;

    

    document.body.appendChild(notification);

    

    // Re-init icons

    if (typeof lucide !== 'undefined') lucide.createIcons();

    

    // Auto remove after 5 seconds

    setTimeout(() => {

        if (notification.parentElement) {

            notification.remove();

        }

    }, 5000);

}



function updateCartCount() {

    // This would be called after cart operations to update the cart count in navbar

    // Implementation depends on your navbar structure

    console.log('Cart count updated');

}



// ── DOM Ready Handler ──

document.addEventListener('DOMContentLoaded', async function () {

    // Check authentication status

    await window.authManager.checkAuth();



    // Load components

    await loadComponent('components/block-navbar.html', 'navbar-container');

    await loadComponent('components/modal-search.html', 'modal-search-container', () => initModal('modal-search'));

    await loadComponent('components/offcanvas-wishlist.html', 'offcanvas-container', () => initOffcanvas('offcanvas-wishlist'));

    await loadComponent('components/offcanvas-cart.html', 'offcanvas-cart-container', () => initOffcanvas('offcanvas-cart'));

    await loadComponent('components/modal-login.html', 'modal-login-container', () => initModal('modal-login'));

    await loadComponent('components/modal-signup.html', 'modal-signup-container', () => initModal('modal-signup'));

    await loadComponent('components/block-footer.html', 'footer-container');



    // Initialize sections

    if (document.getElementById('product-grid')) {

        initProductsSection();

    }



    await loadQuickViewModal();



    if ($('.hero-slider').length) {

        initHeroSlider();

    }



    setTimeout(() => {

        initAuthModals();

    }, 1000);



    setTimeout(initNavbarButtons, 500);

});


