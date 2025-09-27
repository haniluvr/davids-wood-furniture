// Main Application JavaScript - Updated to use Database API
// Note: Products are loaded via database API, not static imports

// ── Generic Component Loader ──
async function loadComponent(url, targetId, initCallback = null) {
    const container = document.getElementById(targetId);
    if (!container) return;

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error(`Failed to load ${url}`);

        container.innerHTML = await response.text();

        // Re-init Lucide
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Run optional init logic
        if (initCallback && typeof initCallback === 'function') {
            initCallback();
        }

    } catch (error) {
        // Component loading failed
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
        // Clear any opacity/transition styles that might interfere
        el.style.opacity = '';
        el.style.transition = '';
        
        // Ensure panel starts in visible position
        panel.classList.remove('translate-x-full');
        panel.classList.add('translate-x-0');
        
        // Force a repaint to ensure current position is set
        panel.offsetHeight;
        
        // Now trigger the slide-out animation
        setTimeout(() => {
            panel.classList.remove('translate-x-0');
            panel.classList.add('translate-x-full');
        }, 10);
        
        // Hide the element entirely after slide animation completes
        setTimeout(() => {
            el.style.display = 'none';
            el.classList.add('hidden');
            // Reset for next opening
            panel.classList.remove('translate-x-full');
        }, 300);
    };

    // Create global function references
    window['show' + simpleName] = show;
    window['hide' + simpleName] = hide;
    window['show' + idBasedName] = show;
    window['hide' + idBasedName] = hide;

    // Bind close button
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            hide();
        });
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
    console.log('🚀 initProductsSection called');
    const grid = document.getElementById('product-grid');
    if (!grid) {
        console.log('❌ Product grid not found');
        return;
    }
    console.log('✅ Product grid found');

    try {
        // Check if API is available
        if (!window.api) {
            console.error('❌ API not available');
            renderProductsWithFilter([]);
            return;
        }

        console.log('🔄 Loading products from API...');
        // Load products from API
        const response = await window.api.getProducts({ per_page: 8 });
        console.log('📦 API Response:', response);
        
        const apiProducts = response.data.data;
        console.log('📦 API Products:', apiProducts);

        // Fallback to API products only
        const productsToUse = apiProducts.length > 0 ? apiProducts : [];

        // Initial render
        renderProductsWithFilter(productsToUse);

        // Filter buttons
        const filterButtons = document.querySelectorAll('.filter-btn');
        console.log('🔍 Found filter buttons:', filterButtons.length);
        
        filterButtons.forEach(btn => {
            console.log('🔘 Setting up filter button:', btn.textContent, btn.getAttribute('data-filter'));
            btn.addEventListener('click', async () => {
                console.log('🎯 Filter button clicked:', btn.textContent, btn.getAttribute('data-filter'));
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
                    // Filter error, show empty results
                    renderProductsWithFilter([]);
                }
            });
        });

        // Sort dropdown
        const sortSelect = document.getElementById('sort-select');
        console.log('🔍 Found sort dropdown:', !!sortSelect);
        
        if (sortSelect) {
            sortSelect.addEventListener('change', async () => {
                const sort = sortSelect.value;
                console.log('🎯 Sort changed to:', sort);
            
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
        }

    } catch (error) {
        console.error('❌ Error in initProductsSection:', error);
        // Fallback to the original database products function
        console.log('🔄 Falling back to initDatabaseProducts...');
        initDatabaseProducts();
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
                description: product.short_description || product.description || product.desc,
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
                    <div class="relative">
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
                        <div class="absolute top-4 right-4">
                                <button class="heart wishlist-btn" data-product-id="${productData.id}" onclick="event.stopPropagation();">
                                    <i data-lucide="heart"></i>
                                </button>
                            </div>
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
                    <button class="btn btn-add-to-cart max-w-[45%] shrink flex items-center justify-center py-2 px-0" id="cardAddToCart" data-product-id="${productData.id}" style="cursor: pointer !important;">
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
    // Remove any existing event listeners to prevent duplicates
    if (window.addToCartListener) {
        document.removeEventListener('click', window.addToCartListener);
    }
    
    // Create the event listener function
    window.addToCartListener = async function (event) {// Check if clicked element is an add to cart button or inside one
        const target = event.target.closest('.btn-add-to-cart');
        const modalButton = event.target.closest('#modalAddToCart');
        const cardButton = event.target.closest('#cardAddToCart');
        
        // Handle quick view modal add to cart button
        if (modalButton) {
            event.preventDefault();
            event.stopPropagation();

            // Get product ID from quick view modal context
            const productId = modalButton.getAttribute('data-product-id') || 
                            window.currentQuickViewProduct?.id;
            
            if (!productId) {
                return;
            }
            
                const quantity = parseInt(document.getElementById('quantity-input')?.value) || 1;
                
                await handleAddToCart(productId, quantity, event.target);
                return;
            }

        // Handle product card add to cart buttons
        if (cardButton || target) {
            event.preventDefault();
            event.stopPropagation();
            
            const productId = parseInt((cardButton || target).getAttribute('data-product-id'));
            const quantity = 1; // Default quantity for product cards
            
            await handleAddToCart(productId, quantity, event.target);
            return;
        }
    };
    
    // Add the event listener
    document.addEventListener('click', window.addToCartListener);
}

// ── Button Success Animation ──
async function animateButtonSuccess(clickedElement) {
    // Find the actual button element
    const button = clickedElement.closest('.btn-add-to-cart') || clickedElement.closest('#modalAddToCart');
    if (!button) return;
    
    // Store original state
    const originalText = button.innerHTML;
    const originalClasses = button.className;
    
    // Change button text to "Added"
    const textSpan = button.querySelector('span');
    if (textSpan) {
        textSpan.textContent = 'Added';
    }
    
    // Add hover state class (assuming it's a CSS class that gives the hover appearance)
    button.classList.add('btn-add-to-cart-hover');
    
    // Create sparkle badge
    const sparkleBadge = document.createElement('div');
    sparkleBadge.className = 'sparkle-badge';
    sparkleBadge.innerHTML = '<i data-lucide="sparkles" class="w-4 h-4 text-yellow-500"></i>';
    sparkleBadge.style.cssText = `
        position: absolute;
        top: -8px;
        right: -8px;
        z-index: 10;
        animation: sparkleAnimation 1.5s ease-out;
        pointer-events: none;
    `;
    
    // Make button position relative to contain the sparkle
    button.style.position = 'relative';
    
    // Add sparkle badge to button
    button.appendChild(sparkleBadge);
    
    // Re-initialize lucide icons for the sparkle
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Wait for 1.5 seconds
    await new Promise(resolve => setTimeout(resolve, 1500));
    
    // Restore original state
    if (textSpan) {
        textSpan.textContent = 'Add to cart';
    }
    button.className = originalClasses;
    button.removeChild(sparkleBadge);
}

// ── Shared Add to Cart Handler ──
async function handleAddToCart(productId, quantity = 1, clickedElement = null) {
    if (!productId) {
        return;
    }
        
    // Check authentication more thoroughly
    const hasToken = !!window.api?.token;
    const authManagerAuth = window.authManager?.isAuthenticated;
    const isAuthenticated = hasToken && authManagerAuth;
    
    console.log('🔐 Authentication details:', {
        hasToken,
        authManagerAuth,
        finalAuth: isAuthenticated,
        token: window.api?.token ? 'Present' : 'Missing'
    });
    
    if (!isAuthenticated) {
        console.log('👤 Guest user - adding to session cart');
        // Continue with guest cart functionality - don't block the request
    } else {
        console.log('✅ Authenticated user - adding to user cart');
    }

    console.log('✅ User authenticated, proceeding to add cart...');
            try {
        console.log('🔄 Calling API to add to cart...');
        const response = await window.api.addToCart(productId, quantity);
        console.log('🔄 API Response:', response);
        
        if (response.success) {
            // Update button state with animation
            if (clickedElement) {
                await animateButtonSuccess(clickedElement);
            }
            // Update cart count in navbar
            await updateCartCount();
            // Load updated cart if cart offcanvas is open
            await loadCartItems();
        } else {
            console.log('❌ Failed to add item:', response);
        }
            } catch (error) {
        console.log('❌ Error adding to cart:', error);
                showNotification(error.message, 'error');
            }
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
                    // Product not found in API or database
                    return;
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
                
                // Set product ID for add to cart button
                const addToCartBtn = document.getElementById('modalAddToCart');
                if (addToCartBtn) {
                    addToCartBtn.setAttribute('data-product-id', product.id);
                }
                
                // Store current product for global access
                window.currentQuickViewProduct = product;

                // Show modal
                if (typeof window.showmodalquickview === 'function') {
                    window.showmodalquickview();
                }

                // Re-init icons after modal opens
                setTimeout(() => {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }, 100);
            } catch (error) {
                // Error loading product details
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
        // Error loading quick view modal
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
        });
    }

    // Cart Offcanvas Button
    const openCartBtn = document.getElementById('openCartOffcanvas');
    if (openCartBtn) {
        openCartBtn.addEventListener('click', async function (event) {
            event.preventDefault();
            event.stopPropagation();
            
            // Load cart items when cart is opened
            await loadCartItems();
            
            // Show cart offcanvas
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

    // Account dropdown toggle - jQuery UI blind animation
    const accountDropdown = document.getElementById('account-dropdown');
    const accountMenu = document.getElementById('account-menu');
    
    if (accountDropdown && accountMenu) {
        // Start with menu hidden
        $(accountMenu).hide();
        accountMenu.style.display = 'none';
        
        let isAnimating = false;
        
        accountDropdown.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            // Don't toggle if animating
            if (isAnimating) {
                return;
            }
            
            if ($(accountMenu).is(':visible')) {
                // CLOSE with blind animation
                isAnimating = true;
                
                $(accountMenu).hide('blind', { direction: 'up' }, 300, function() {
                    isAnimating = false;
                });
                
            } else {
                // OPEN with blind animation  
                isAnimating = true;
                
                $(accountMenu).show('blind', { direction: 'up' }, 300, function() {
                    isAnimating = false;
                });
            }
        });

        // Close dropdown when clicking outside  
        document.addEventListener('click', function(event) {
            // Only deal with outside clicks
            if (accountDropdown.contains(event.target) || accountMenu.contains(event.target)) {
                return;
            }
            
            if (isAnimating) {
                return;
            }
            
            // Check if dropdown is actually open using jQuery
            if ($(accountMenu).is(':visible')) {
                isAnimating = true;
                
                $(accountMenu).hide('blind', { direction: 'up' }, 300, function() {
                    isAnimating = false;
                });
            }
        }, true);
    }

    // Login and Signup Modal Buttons - Fixed
    const openLoginBtn = document.getElementById('open-login-modal');
    const openSignupBtn = document.getElementById('open-signup-modal');
    
    if (openLoginBtn) {
        openLoginBtn.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            if (typeof window.showmodallogin === 'function') {
                window.showmodallogin();
            } else {
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
    } 
    
    if (openSignupBtn) {
        openSignupBtn.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            if (typeof window.showmodalsignup === 'function') {
                window.showmodalsignup();
            } else {
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
    } 
}

// ── Initialize Hero Slider ──
function initHeroSlider() {
    let currentIndex = 0;
    const $slides = $('.slide');
    const $indicators = $('.indicator');
    let autoSlideInterval;

    // Check if jQuery and elements exist
    if (!$slides.length || !$indicators.length) {
        return;
    }

    function showSlide(index) {
        $slides.removeClass('active');
        $indicators.removeClass('active');

        $slides.eq(index).addClass('active');
        $indicators.eq(index).addClass('active');

        // Update any text overlays if they exist
        const currentSlide = $slides.eq(index);
        $('#slide-title').text(currentSlide.data('name'));
        $('#slide-desc').text(currentSlide.data('price'));
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % $slides.length;
        showSlide(currentIndex);
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 2000); // 2 second interval like original
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    // Only start auto-slide if we have more than one slide
    if ($slides.length > 1) {
        startAutoSlide();
    }

    // Pause on hover
    $('.hero-slider').hover(
        function() { stopAutoSlide(); },
        function() { startAutoSlide(); }
    );

    // Dot click handlers
    $indicators.click(function() {
        currentIndex = $(this).index();
        showSlide(currentIndex);
    });

    // Initialize
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
                name: document.getElementById('signup-firstname').value + ' ' + document.getElementById('signup-lastname').value,
                email: document.getElementById('signup-email').value,
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

async function updateCartCount() {
    try {
        const response = await window.api.getCart();
        const cartData = response.data;
        const count = cartData.total_items || 0;
        
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            if (count > 0) {
                // Show "9+" if count is greater than 9, otherwise show actual count
                cartCountElement.textContent = count > 9 ? '9+' : count.toString();
                cartCountElement.classList.remove('hidden');
            } else {
                cartCountElement.classList.add('hidden');
            }
        } else {
        }
    } catch (error) {
        // Keep the cart count element hidden on error
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            cartCountElement.classList.add('hidden');
        }
    }
}

// ── Load Cart Items (with debouncing) ──
let loadCartItemsTimeout;
async function loadCartItems() {
    // Clear any pending calls to prevent multiple simultaneous loads
    if (loadCartItemsTimeout) {
        clearTimeout(loadCartItemsTimeout);
    }
    
    // Debounce the function call to prevent rapid successive calls
    loadCartItemsTimeout = setTimeout(async () => {
        await performLoadCartItems();
    }, 100);
}

async function performLoadCartItems() {
    const cartBody = document.getElementById('cart-body');
    const cartEmptyState = document.getElementById('cart-empty-state');
    const cartItems = document.getElementById('cart-items');
    const cartFooter = document.getElementById('cart-footer');
    const cartSubtotal = document.getElementById('cart-subtotal');
      
    if (!cartBody) {
        return;
    }

    try {
        const response = await window.api.getCart();
        
        const cartData = response.data;
        const items = cartData.cart_items || [];

        // Use requestAnimationFrame to ensure smooth DOM updates
        requestAnimationFrame(() => {
            if (items.length > 0) {
                // Hide empty state first
                if (cartEmptyState) {
                    cartEmptyState.style.display = 'none';
                }
                // Show items
                if (cartItems) {
                    cartItems.style.display = 'block';
                }
                // Show footer when there are items
                if (cartFooter) {
                    cartFooter.classList.remove('hidden');
                }
                
                // Generate cart items HTML
                let cartItemsHTML = '';
                items.forEach((item, index) => {
                    const productData = item.product_data || {};
                    const image = productData.image || '/frontend/assets/chair.png';
                    
                    cartItemsHTML += `
                        <div class="cart-item border-b py-4 px-6" data-product-id="${item.product_id}">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img src="${image}" class="w-16 h-16 object-cover rounded" alt="${item.product_name}">
                                </div>
                                <div class="flex-grow">
                                    <h6 class="text-sm font-medium text-gray-900">${item.product_name}</h6>
                                    <p class="text-xs text-gray-500">₱${parseFloat(item.unit_price).toLocaleString('en-US')}</p>
                                    <div class="flex items-center space-x-2 mt-2">
                                        <button class="quantity-btn text-gray-500 hover:text-gray-700" onclick="updateCartQuantity(${item.product_id}, ${item.quantity - 1})">-</button>
                                        <span class="quantity text-sm">${item.quantity}</span>
                                        <button class="quantity-btn text-gray-500 hover:text-gray-700" onclick="updateCartQuantity(${item.product_id}, ${item.quantity + 1})">+</button>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <div class="text-sm font-medium text-gray-900">₱${parseFloat(item.total_price).toLocaleString('en-US')}</div>
                                    <button class="remove-btn text-xs text-gray-500 hover:text-red-500 mt-1" onclick="removeFromCart(${item.product_id})">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });

                if (cartItems) {
                    cartItems.innerHTML = cartItemsHTML;
                }

                // Update subtotal
                if (cartData.subtotal && cartSubtotal) {
                    cartSubtotal.textContent = `₱${parseFloat(cartData.subtotal).toLocaleString('en-US')}`;
                }
            } else {
                // Hide items first
                if (cartItems) {
                    cartItems.style.display = 'none';
                }
                // Hide footer when empty
                if (cartFooter) {
                    cartFooter.classList.add('hidden');
                }
                // Show empty state last
                if (cartEmptyState) {
                    cartEmptyState.style.display = 'block';
                }
            }
        });
    } catch (error) {
        // Show empty state on error
        requestAnimationFrame(() => {
            if (cartEmptyState) cartEmptyState.style.display = 'block';
            if (cartItems) cartItems.style.display = 'none';
            if (cartFooter) {
                cartFooter.classList.add('hidden');
            }
        });
    }
}

// ── Update Cart Quantity ──
async function updateCartQuantity(productId, newQuantity) {
    if (newQuantity <= 0) {
        await removeFromCart(productId);
        return;
    }

    try {
        await window.api.updateCartItem(productId, newQuantity);
        await loadCartItems(); // Refresh cart display
        updateCartCount(); // Update count in navbar
    } catch (error) {
        console.error('Error updating cart quantity:', error);
        showNotification('Error updating cart', 'error');
    }
}

// ── Remove from Cart ──
async function removeFromCart(productId) {
    try {
        await window.api.removeFromCart(productId);
        await loadCartItems(); // Refresh cart display
        updateCartCount(); // Update count in navbar
        // No notification needed - cart updates are visible
    } catch (error) {
        console.error('Error removing from cart:', error);
        showNotification('Error removing item', 'error');
    }
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
    
    if (!grid) {
        return;
    }

    try {
        // Try to get products from Laravel backend first
        // Use exact URL of API endpoint
        const apiUrl = `${window.location.origin}/api/products`; 
        
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
            
            if (data.success && data.data && data.data.length > 0) {
                renderProductsFromDatabase(data.data);
                return;
            }
        }
    } catch (error) {
        // API fetch failed, continue to fallback
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
        grid.innerHTML = '<p>Unable to load products. Please try again later.</p>';
    }
}

// ── Render Products from Database ──
function renderProductsFromDatabase(products) {
    const grid = document.getElementById('product-grid');
    if (!grid) {
        return;
    }

    grid.innerHTML = '';
    
    products.forEach((product, index) => {
        const col = document.createElement('div');
        col.className = 'w-full';

        // Handle product data safely - use short_description instead of description
        const productData = {
            id: product.id,
            name: product.name || 'Unnamed Product',
            description: product.short_description || product.description || product.desc || 'No description available',
            price: parseFloat(product.price) || 0,
            image: product.image || product.primary_image || product.images?.[0]?.url || '/frontend/assets/chair.png',
            rating: parseFloat(product.rating) || 4.5,
            stock: product.stock || product.stock_status || product.in_stock === true ? 'in-stock' : 'low',
            material: product.material || 'Wood',
            dimensions: product.dimensions || 'Contact for specs',
            category: product.category?.name || product.category || 'furniture',
            slug: product.slug || `product-${product.id}`
        };

        col.innerHTML = `
            <div class="card product-card flex flex-col h-full rounded-2xl border bg-white">
                <div class="relative">
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
                    <div class="absolute top-4 right-4">
                            <button class="heart wishlist-btn" data-product-id="${productData.id}" onclick="event.stopPropagation();">
                                <i data-lucide="heart"></i>
                            </button>
                        </div>
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
                 <button class="btn btn-add-to-cart max-w-[45%] shrink flex items-center justify-center py-2 px-0" id="cardAddToCart" data-product-id="${productData.id}" style="cursor: pointer !important;">
                     <i data-lucide="shopping-cart" class="lucide-small"></i> 
                     <span class="font-medium ml-2">Add to cart</span>
                 </button>
            </div>
        </div>
        `;

        grid.appendChild(col);
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

    // Initialize hero slider - ensure it runs after DOM is fully loaded
    setTimeout(() => {
        if ($('.hero-slider').length) {
            initHeroSlider();
        }
    }, 100);

    // Initialize auth modals
    initAuthModals();

    // Initialize products from database with filtering and sorting
    const productGrid = document.getElementById('product-grid');
    if (productGrid) {
        initProductsSection();
    }

    // Load cart count on page load
    try {
        await updateCartCount();
    } catch (error) {
    }

    await loadQuickViewModal();
});