// Main Application JavaScript - Updated to use Database API
// Note: Products are loaded via database API, not static imports


// ── Generic Component Loader ──
async function loadComponent(url, targetId, initCallback = null) {
    const container = document.getElementById(targetId);
    if (!container) return;

    try {
        const response = await fetch(url, {
            credentials: 'include' // Include cookies for session management
        });
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
        // Show the offcanvas backdrop
        el.classList.remove('hidden');
        el.style.display = 'block';
        
        // Force a reflow to ensure the element is visible before animation
        el.offsetHeight;
        
        // Start panel slide animation with a small delay for smooth transition
        requestAnimationFrame(() => {
            panel.classList.remove('translate-x-full');
        });
    };

    const hide = function() {
        // Start panel slide-out animation
        panel.classList.add('translate-x-full');
        
        // Hide the element entirely after slide animation completes
        setTimeout(() => {
            el.classList.add('hidden');
            el.style.display = 'none';
            // Reset panel position for next opening
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
        
        // Force remove hidden class and override all styles
        el.classList.remove('hidden');
        el.classList.add('block');
        
        el.classList.remove('hidden');
        el.
        
        // Set opacity immediately for instant display
        el.style.opacity = '1';
    };

    const hide = function() { 
        el.style.display = 'none';
        el.classList.add('hidden');
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
    if (!grid) {
        return;
    }

    try {
        // Check if API is available
        if (!window.api) {
            renderProductsWithFilter([]);
            return;
        }

        // Load products from API
        const response = await window.api.getProducts({ per_page: 8 });
        
        // Check the actual response structure - API returns data directly
        const apiProducts = response.data || [];

        // Fallback to API products only
        const productsToUse = Array.isArray(apiProducts) && apiProducts.length > 0 ? apiProducts : [];

        // Initial render
        renderProductsWithFilter(productsToUse);

        // Filter buttons
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        filterButtons.forEach(btn => {
            btn.addEventListener('click', async () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filter = btn.getAttribute('data-filter');
                
                try {
                    // Get current sort setting
                    const currentSort = sortSelect ? sortSelect.value : 'popularity';
                    
                    const filterParams = filter === 'all' ? {} : { category: filter };
                    const response = await window.api.getProducts({ ...filterParams, sort: currentSort, per_page: 8 });
                    const filteredProducts = response.data || [];
                    renderProductsWithFilter(filteredProducts);
                } catch (error) {
                    // Filter error, show empty results
                    renderProductsWithFilter([]);
                }
            });
        });

        // Sort dropdown
        const sortSelect = document.getElementById('sort-select');
        
        if (sortSelect) {
            sortSelect.addEventListener('change', async () => {
                const sort = sortSelect.value;
            
                // Get current active filter
                const activeFilterBtn = document.querySelector('.filter-btn.active');
                const currentFilter = activeFilterBtn ? activeFilterBtn.getAttribute('data-filter') : 'all';
            
                try {
                    const filterParams = currentFilter === 'all' ? {} : { category: currentFilter };
                    const response = await window.api.getProducts({ ...filterParams, sort, per_page: 8 });
                    const sortedProducts = response.data || [];
                    renderProductsWithFilter(sortedProducts);
                } catch (error) {
                    // Show error message instead of fallback
                    showNotification('Unable to sort products. Please try again.', 'error');
                }
            });
        }

    } catch (error) {
        // Fallback to the original database products function
        initDatabaseProducts();
    }

    // Render function
    function renderProductsWithFilter(products) {
        // Clear grid with fade out effect
        grid.style.transition = 'opacity 0.2s ease-in-out';
        grid.style.opacity = '0.3';
        grid.innerHTML = '';
        
        // Small delay to allow fade out
        setTimeout(() => {
            grid.style.opacity = '1';

            if (products.length === 0) {
                grid.innerHTML = '<div class="col-span-full text-center py-8" data-aos="fade-up"><p class="text-gray-500">No products found.</p></div>';
                // Refresh AOS for empty state
                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }
                return;
            }

            products.forEach((product, index) => {
                const col = document.createElement('div');
                col.className = 'w-full';
                col.setAttribute('data-aos', 'fade-up');
                col.setAttribute('data-aos-delay', (index * 100).toString()); // Stagger animations
                col.setAttribute('data-aos-duration', '600');

            // Handle both API and local product formats
            const productData = {
                id: product.id,
                name: product.name,
                description: product.short_description || product.description || product.desc,
                price: product.price,
                image: product.primary_image || product.images?.[0]?.url || product.image || 'https://via.placeholder.com/300x300?text=No+Image',
                rating: product.rating || 4.5,
                stock: product.stock_status || product.stock || 'in-stock',
                material: product.material,
                dimensions: product.dimensions,
                slug: product.slug
            };

            // Validate product data before rendering
            if (!productData.id) {
                console.error('Product missing ID, skipping render:', product);
                return;
            }

            col.innerHTML = `
                <div class="card product-card flex flex-col h-full rounded-2xl border bg-white">
                    <div class="relative">
                        <img src="${productData.image}" class="w-full h-64 object-cover" alt="${productData.name}">
                        <div class="absolute inset-0 flex p-4 h-full">
                            <div class="flex-1">
                                <div class="rounded-full stock-badge ${productData.stock === 'low' ? 'low' : 'in-stock'} px-3 py-1 text-xs font-medium">
                                ${productData.stock === 'low' ? 'Low stock' : 'In stock'}
                            </div>
                        </div>
                        <div class="absolute top-4 right-4">
                            <button class="wishlist-btn" data-product-id="${productData.id || ''}" onclick="event.stopPropagation();">
                                <i id="heart-icon-${productData.id || ''}" data-lucide="heart" class="heart-toggle-icon"></i>
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
                            <span class="rating flex items-center justify-end mt-1">
                                <i data-lucide="star" class="lucide-small mr-1"></i> ${productData.rating}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-auto flex p-4 justify-between">
                    <button class="btn btn-quick-view max-w-[45%] shrink flex items-center justify-center py-2 px-0" data-product-id="${productData.id}" data-product-slug="${productData.slug}">
                        <i data-lucide="proportions" class="lucide-small"></i> 
                        <span class="font-medium ml-2">Quick view</span>
                    </button>
                    <button class="btn btn-add-to-cart max-w-[45%] shrink flex items-center justify-center py-2 px-0" data-product-id="${productData.id}" style="cursor: pointer !important;">
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
            
            // Refresh AOS animations for new content
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }

            // Attach event handlers
            initModalQuickView();
            initAddToCartButtons();
            
            // Initialize wishlist buttons after DOM is fully updated
            // Use requestAnimationFrame to ensure DOM is ready
            requestAnimationFrame(() => {
            initWishlistButtons();
            });
        }, 150); // Small delay for smooth transition
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
            console.log('🛒 MODAL Add to Cart clicked');

            // Get product ID from quick view modal context
            const productId = modalButton.getAttribute('data-product-id') || 
                            window.currentQuickViewProduct?.id;
            
            console.log('Modal - Product ID:', productId);
            
            if (!productId) {
                console.error('Modal - No product ID found');
                return;
            }
            
            const quantity = parseInt(document.getElementById('quantity-input')?.value) || 1;
            console.log('Modal - Quantity:', quantity);
            
            await handleAddToCart(productId, quantity, event.target);
            return;
        }

        // Handle product card add to cart buttons
        if (cardButton || target) {
            event.preventDefault();
            event.stopPropagation();
            console.log('🛒 CARD Add to Cart clicked');
            
            const productId = parseInt((cardButton || target).getAttribute('data-product-id'));
            const quantity = 1; // Default quantity for product cards
            
            console.log('Card - Product ID:', productId);
            console.log('Card - Quantity:', quantity);
            
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
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // Restore original state
    if (textSpan) {
        textSpan.textContent = 'Add to cart';
    }
    button.className = originalClasses;
    button.removeChild(sparkleBadge);
}

// ── Shared Add to Cart Handler ──
async function handleAddToCart(productId, quantity = 1, clickedElement = null) {
    console.log('handleAddToCart called with:', { productId, quantity });
    if (!productId) {
        console.error('No product ID provided');
        return;
    }
        
    // Check authentication more thoroughly
    const hasToken = !!window.api?.token;
    const authManagerAuth = window.authManager?.isAuthenticated;
    const isAuthenticated = hasToken && authManagerAuth;
    
    if (!isAuthenticated) {
        // Continue with guest cart functionality - don't block the request
    }
            try {
                console.log('Calling window.api.addToCart...');
                const response = await window.api.addToCart(productId, quantity);
                console.log('Add to cart response:', response);
                
                if (response.success) {
                    console.log('Item added successfully, updating UI...');
                    console.log('Add to cart session ID:', response.session_id);
                    // Update button state with animation
                    if (clickedElement) {
                        await animateButtonSuccess(clickedElement);
                    }
                    // Update cart count in navbar
                    await updateCartCount();
                    // Load updated cart if cart offcanvas is open
                    console.log('About to call loadCartItems...');
                    await loadCartItems();
                    console.log('loadCartItems completed');
                    
                    // Force refresh cart offcanvas if it's open
                    const cartOffcanvas = document.getElementById('offcanvas-cart');
                    if (cartOffcanvas && !cartOffcanvas.classList.contains('hidden')) {
                        console.log('Cart offcanvas is open, forcing refresh...');
                        // Just reload cart items without clearing first
                        await loadCartItems();
                    }
                }
            } catch (error) {
                console.error('Add to cart error:', error);
                showNotification(error.message, 'error');
            }
}

// ── Set Wishlist Button Visual State ──
async function setWishlistButtonState(button) {
    const productId = button.getAttribute('data-product-id');
    if (!productId) {
        console.warn('setWishlistButtonState: No product ID found on button', button);
        return;
    }
    
    let isInWishlist = false;
    
    if (window.authManager && window.authManager.isAuthenticated) {
        // Check server wishlist
        try {
            const checkResponse = await window.api.checkWishlist(productId);
            isInWishlist = checkResponse.data.in_wishlist;
        } catch (error) {
            console.warn('Server wishlist check failed, using guest mode:', error);
            isInWishlist = isInGuestWishlist(productId);
        }
    } else {
        // Check guest wishlist
        isInWishlist = isInGuestWishlist(productId);
    }
    
    // Set initial visual state based on wishlist status using ID selector
    const $icon = $(`#heart-icon-${productId}`);
    
    if ($icon.length) {
        if (isInWishlist) {
            // Activate: filled
            $icon.addClass('active');
            $icon.attr('fill', 'currentColor');
            $icon.attr('stroke', 'none');
        } else {
            // Deactivate: stroke only
            $icon.removeClass('active');
            $icon.attr('fill', 'none');
            $icon.attr('stroke', 'currentColor');
        }
        
        // Re-initialize lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    } else {
        console.warn('Icon element not found for product:', productId);
    }
}

// ── Initialize Wishlist Buttons ──
function initWishlistButtons() {
    // Remove existing event listeners to prevent duplicates
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        // Clone the button to remove all event listeners
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });

    // Set initial state for all buttons
    const buttons = document.querySelectorAll('.wishlist-btn');
    
    // Batch update all wishlist button states simultaneously
    if (buttons.length > 0) {
        updateAllWishlistButtonStates(buttons);
    }

    // Add click listeners directly to each button
    buttons.forEach((btn, index) => {
        const productId = btn.getAttribute('data-product-id');
        if (productId) {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = parseInt(btn.getAttribute('data-product-id'));
                if (!productId) {
                    console.error('Invalid product ID:', btn.getAttribute('data-product-id'));
                    return;
                }

                try {
                    // Check if API is available
                    if (!window.api) {
                        console.error('window.api is not available!');
                        showNotification('API not available', 'error');
                        return;
                    }

                    // Toggle via API
                    const response = await window.api.toggleWishlist(productId);

                    // Update button state
                    updateWishlistButtonState(productId);

                    // Update offcanvas if open
                    const offcanvas = document.getElementById('offcanvas-wishlist');
                    if (offcanvas && getComputedStyle(offcanvas).visibility !== 'hidden') {
                        await updateWishlistOffcanvas();
                    }

                    // Update wishlist count badge
                    await updateWishlistCount();

                } catch (error) {
                    console.error('Wishlist toggle error:', error);
                    showNotification('Failed to update wishlist', 'error');
                }
            });
        }
    });
}

// ── Update All Wishlist Button States (Batch) ──
async function updateAllWishlistButtonStates(buttons) {
    try {
        // Extract all product IDs
        const productIds = Array.from(buttons)
            .map(btn => btn.getAttribute('data-product-id'))
            .filter(id => id);

        if (productIds.length === 0) return;

        // Batch check all wishlist states
        const wishlistStates = await Promise.all(
            productIds.map(async (productId) => {
                if (!productId) {
                    console.warn('updateWishlistButtonStates: Invalid product ID', productId);
                    return {
                        productId,
                        isInWishlist: false
                    };
                }
                try {
                    const response = await window.api.checkWishlist(productId);
                    return {
                        productId,
                        isInWishlist: response.in_wishlist
                    };
                } catch (error) {
                    console.warn(`Failed to check wishlist for product ${productId}:`, error);
                    return {
                        productId,
                        isInWishlist: false
                    };
                }
            })
        );

        // Update all icons simultaneously
        wishlistStates.forEach(({ productId, isInWishlist }) => {
            const icon = document.getElementById(`heart-icon-${productId}`);
            if (!icon) return;

            if (isInWishlist) {
                icon.classList.add('active');
                icon.setAttribute('fill', 'currentColor');
                icon.setAttribute('stroke', 'none');
            } else {
                icon.classList.remove('active');
                icon.setAttribute('fill', 'none');
                icon.setAttribute('stroke', 'currentColor');
            }
        });

        // Re-initialize lucide icons once for all changes
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    } catch (error) {
        console.warn('Failed to update wishlist button states:', error);
    }
}

// ── Update Wishlist Button State (Individual) ──
async function updateWishlistButtonState(productId) {
    if (!productId) {
        console.warn('updateWishlistButtonState: Invalid product ID', productId);
        return;
    }
    try {
        const response = await window.api.checkWishlist(productId);
        const isInWishlist = response.in_wishlist;
        
        const icon = document.getElementById(`heart-icon-${productId}`);
        if (!icon) {
            console.warn(`Heart icon not found for product ${productId}`);
            return;
        }

        if (isInWishlist) {
            icon.classList.add('active');
            icon.setAttribute('fill', 'currentColor');
            icon.setAttribute('stroke', 'none');
        } else {
            icon.classList.remove('active');
            icon.setAttribute('fill', 'none');
            icon.setAttribute('stroke', 'currentColor');
        }

        if (typeof lucide !== 'undefined') lucide.createIcons();
    } catch (error) {
        console.warn('Failed to update wishlist button state:', error);
    }
}

// ── Migrate Guest Wishlist to User Account ──
async function migrateGuestWishlist() {
    if (!window.authManager.isAuthenticated) return;
    
    try {
        // Wait a moment for backend migration to complete
        await new Promise(resolve => setTimeout(resolve, 500));
        
        // Update all wishlist button states after migration
        const buttons = document.querySelectorAll('.wishlist-btn');
        if (buttons.length > 0) {
            await updateAllWishlistButtonStates(buttons);
        }
        
        // Update wishlist offcanvas if open
        const offcanvas = document.getElementById('offcanvas-wishlist');
        if (offcanvas && getComputedStyle(offcanvas).visibility !== 'hidden') {
            await updateWishlistOffcanvas();
        }
    } catch (error) {
        console.error('Error updating wishlist after migration:', error);
    }
}

// Make migrateGuestWishlist globally accessible
window.migrateGuestWishlist = migrateGuestWishlist;

// ── Clear Guest Wishlist State (for logout) ──
function clearGuestWishlist() {
    try {
        // Clear wishlist UI elements
        const wishlistItems = document.querySelectorAll('.wishlist-item');
        wishlistItems.forEach(item => item.remove());
        
        // Clear wishlist count
        const wishlistCount = document.getElementById('wishlist-count');
        if (wishlistCount) {
            wishlistCount.textContent = '0';
            wishlistCount.classList.add('hidden');
        }
        
        // Clear localStorage
        localStorage.removeItem('wishlist_items');
        
        console.log('Guest wishlist state cleared successfully');
    } catch (error) {
        console.error('Error clearing guest wishlist state:', error);
    }
}

// Make clearGuestWishlist globally accessible
window.clearGuestWishlist = clearGuestWishlist;

// ── Update Wishlist Offcanvas ──
let updateWishlistTimeout;
async function updateWishlistOffcanvas() {
    try {
        const response = await window.api.getWishlist();
        const wishlistItems = response; // array of items

        const body = document.querySelector('#offcanvas-wishlist .offcanvas-body');
        if (!body) return;

        if (wishlistItems.length === 0) {
            body.innerHTML = '<p class="empty-state-text">No favorites yet.</p>';
            return;
        }

        let html = '<div class="wishlist-items">';
        wishlistItems.forEach(item => {
            const p = item.product;
            html += `
                <div class="wishlist-item flex items-center py-4 border-b border-gray-200" data-product-id="${p.id}">
                    <div class="flex-shrink-0 w-16 h-16">
                        <img src="${p.image}" alt="${p.name}" class="w-full h-full object-cover rounded">
                    </div>
                    <div class="flex-1 ml-4">
                        <h6 class="text-sm font-medium text-gray-900">${p.name}</h6>
                        <p class="text-sm text-gray-500">₱${Math.floor(p.price).toLocaleString()}</p>
                        <button class="btn-add-to-cart mt-1 px-3 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors" data-product-id="${p.id}">
                            Add to Cart
                        </button>
                    </div>
                    <div class="flex-shrink-0">
                        <button class="btn-remove-wishlist text-red-500 hover:text-red-700" data-product-id="${p.id}">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        body.innerHTML = html;

        // Re-init icons & attach remove handlers
        if (typeof lucide !== 'undefined') lucide.createIcons();
        body.querySelectorAll('.btn-remove-wishlist').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.stopPropagation();
                const id = btn.getAttribute('data-product-id');
                await window.api.removeFromWishlist(id);
                updateWishlistOffcanvas();
                updateWishlistButtonState(id);
                await updateWishlistCount();
            });
        });

        // Attach add to cart handlers
        body.querySelectorAll('.btn-add-to-cart').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.stopPropagation();
                const productId = parseInt(btn.getAttribute('data-product-id'));
                
                try {
                    // Add to cart
                    const cartResponse = await window.api.addToCart(productId, 1);
                    
                    if (cartResponse.success) {
                        // Remove from wishlist
                        await window.api.removeFromWishlist(productId);
                        
                        // Update wishlist offcanvas
                        await updateWishlistOffcanvas();
                        
                        // Update wishlist count
                        await updateWishlistCount();
                        
                        // Update cart count if cart offcanvas is open
                        const cartOffcanvas = document.getElementById('offcanvas-cart');
                        if (cartOffcanvas && getComputedStyle(cartOffcanvas).visibility !== 'hidden') {
                            await performLoadCartItems();
                        }
                        
                        // Update cart count in navbar
                        await updateCartCount();
                        
                        // Show success notification
                        showNotification('Item moved to cart successfully!', 'success');
                    } else {
                        showNotification('Failed to add item to cart', 'error');
                    }
                } catch (error) {
                    console.error('Error moving item to cart:', error);
                    showNotification('Failed to move item to cart', 'error');
                }
            });
        });

        // Attach clear wishlist button handler
        const clearWishlistBtn = document.getElementById('clear-wishlist-btn');
        if (clearWishlistBtn) {
            // Remove existing event listeners to prevent duplicates
            const newBtn = clearWishlistBtn.cloneNode(true);
            clearWishlistBtn.parentNode.replaceChild(newBtn, clearWishlistBtn);
            
            newBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                if (confirm('Are you sure you want to clear all favorites? This action cannot be undone.')) {
                    try {
                        await window.api.clearWishlist();
                        await updateWishlistOffcanvas();
                        await updateWishlistCount();
                        
                        // Update all wishlist button states
                        document.querySelectorAll('.wishlist-btn').forEach(btn => {
                            const productId = btn.getAttribute('data-product-id');
                            if (productId) {
                                updateWishlistButtonState(productId);
                            }
                        });
                    } catch (error) {
                        console.error('Failed to clear wishlist:', error);
                        showNotification('Failed to clear favorites. Please try again.', 'error');
                    }
                }
            });
        }
    } catch (error) {
        console.error('Failed to load wishlist:', error);
    }
}

// ── Initialize Quick View Modals ──
function initModalQuickView() {
    const buttons = document.querySelectorAll('.btn-quick-view');
    
    buttons.forEach((btn, index) => {
        // Remove existing event listeners to prevent duplicates
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        newBtn.addEventListener('click', async function (event) {
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
                    product = products.find(p => p.id === productId);
                }

                if (!product) {
                    return;
                }

                // Cache modal elements for faster updates
                const modalElements = {
                    label: document.getElementById('quickViewLabel'),
                    image: document.getElementById('quick-view-image'),
                    desc: document.getElementById('quick-view-desc'),
                    rating: document.getElementById('quick-view-rating'),
                    price: document.getElementById('quick-view-price'),
                    material: document.getElementById('quick-view-material'),
                    dimensions: document.getElementById('quick-view-dimensions')
                };

                // Fill modal with cached elements
                if (modalElements.label) modalElements.label.textContent = product.name;
                if (modalElements.image) modalElements.image.src = product.primary_image || product.image;
                if (modalElements.desc) modalElements.desc.textContent = product.description || product.desc;
                if (modalElements.rating) modalElements.rating.textContent = product.rating;
                if (modalElements.price) modalElements.price.textContent = `₱${Math.floor(product.price).toLocaleString('en-US')}`;
                if (modalElements.material) modalElements.material.textContent = product.material;
                if (modalElements.dimensions) modalElements.dimensions.textContent = product.dimensions;
                
                // Set product ID for add to cart button
                const addToCartBtn = document.getElementById('modalAddToCart');
                if (addToCartBtn) {
                    addToCartBtn.setAttribute('data-product-id', product.id);
                }
                
                // Store current product for global access
                window.currentQuickViewProduct = product;

                // Show modal
                if (typeof window.showmodalQuickView === 'function') {
                    window.showmodalQuickView();
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
async function loadModalQuickView() {
    // Modal is already included in the layout, just initialize it
    const modalEl = document.getElementById('modalQuickView');
    if (modalEl) {
        initModal('modalQuickView');
    }

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// ── Initialize Search Modal ──
function initSearchModal() {
    const modal = document.getElementById('modal-search');
    if (!modal) return;

    const searchInput = document.getElementById('search-input');
    const searchPlaceholder = document.getElementById('search-placeholder');
    const searchLoading = document.getElementById('search-loading');
    const searchResultsList = document.getElementById('search-results-list');
    const searchNoResults = document.getElementById('search-no-results');
    
    let searchTimeout;

    // Search input event listener with debouncing
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            
            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            if (query.length === 0) {
                // Show placeholder when input is empty
                showSearchPlaceholder();
            } else if (query.length >= 2) {
                // Debounce search requests
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            }
        });

        // Focus input when modal opens
        searchInput.addEventListener('focus', function() {
            if (searchInput.value.trim().length >= 2) {
                performSearch(searchInput.value.trim());
            }
        });
    }

    function showSearchPlaceholder() {
        searchPlaceholder?.classList.remove('hidden');
        searchLoading?.classList.add('hidden');
        searchResultsList?.classList.add('hidden');
        searchNoResults?.classList.add('hidden');
    }

    function showSearchLoading() {
        searchPlaceholder?.classList.add('hidden');
        searchLoading?.classList.remove('hidden');
        searchResultsList?.classList.add('hidden');
        searchNoResults?.classList.add('hidden');
    }

    function showSearchResults(results) {
        searchPlaceholder?.classList.add('hidden');
        searchLoading?.classList.add('hidden');
        searchResultsList?.classList.remove('hidden');
        searchNoResults?.classList.add('hidden');

        if (!searchResultsList) return;

        let html = '';
        results.forEach((product, index) => {
            const productData = {
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.images?.[0]?.url || product.image || 'https://via.placeholder.com/300x300?text=No+Image',
                slug: product.slug
            };

            html += `
                <div class="search-result-item p-4 border-b border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors ${index === results.length - 1 ? 'border-b-0' : ''}" 
                     data-product-id="${productData.id}" 
                     data-product-slug="${productData.slug}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 flex-shrink-0">
                                <img src="${productData.image}" alt="${productData.name}" 
                                     class="w-full h-full object-cover rounded-lg">
                            </div>
                            <div>
                                <h6 class="text-sm font-medium text-gray-900">${productData.name}</h6>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900">₱${Math.floor(productData.price).toLocaleString('en-US')}</div>
                        </div>
                    </div>
                </div>
            `;
        });

        searchResultsList.innerHTML = html;

        // Add click handlers to search results
        searchResultsList.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const productSlug = this.getAttribute('data-product-slug');
                
                // Close search modal
                if (typeof window.hidemodalsearch === 'function') {
                    window.hidemodalsearch();
                }
                
                // Navigate to product or open quick view
                if (productSlug) {
                    // Try to open quick view modal
                    const quickViewBtn = document.querySelector(`[data-product-id="${productId}"][data-product-slug="${productSlug}"]`);
                    if (quickViewBtn) {
                        quickViewBtn.click();
                    } else {
                        // Navigate to product page
                        window.location.href = `/products/${productSlug}`;
                    }
                }
            });
        });
    }

    function showNoResults() {
        searchPlaceholder?.classList.add('hidden');
        searchLoading?.classList.add('hidden');
        searchResultsList?.classList.add('hidden');
        searchNoResults?.classList.remove('hidden');
    }

    async function performSearch(query) {
        if (!window.api) {
            console.error('API not available for search');
            showNoResults();
            return;
        }

        showSearchLoading();

        try {
            const response = await window.api.searchProducts(query);
            
            if (response.success && response.data && response.data.length > 0) {
                showSearchResults(response.data);
            } else {
                showNoResults();
            }
        } catch (error) {
            console.error('Search error:', error);
            showNoResults();
        }
    }

    // Clear search when modal is closed
    modal.addEventListener('hidden.bs.modal', function() {
        if (searchInput) {
            searchInput.value = '';
        }
        showSearchPlaceholder();
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
    });
}

// ── Initialize Navbar Buttons ──
function initNavbarButtons() {
    
    // Search Modal Button - Fixed
    const openSearchBtn = document.getElementById('openSearchModal');
    if (openSearchBtn) {
        openSearchBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            
            // Initialize search modal if not already done
            if (!document.getElementById('search-input')) {
                // Modal not ready yet, try again
                setTimeout(() => {
                    if (typeof window.showmodalsearch === 'function') {
                        window.showmodalsearch();
                    }
                }, 100);
                return;
            }
            
            if (typeof window.showmodalsearch === 'function') {
                window.showmodalsearch();
            }
            
            // Focus search input after modal opens
            setTimeout(() => {
                const searchInput = document.getElementById('search-input');
                if (searchInput) {
                    searchInput.focus();
                }
            }, 300);
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
        openWishlistBtn.addEventListener('click', async function (event) {
            event.preventDefault();
            event.stopPropagation();
            
            // Load wishlist items when offcanvas is opened
            await updateWishlistOffcanvas();
            
            // Update wishlist count
            await updateWishlistCount();
            
            // Show wishlist offcanvas
            if (typeof window.showoffcanvaswishlist === 'function') {
                window.showoffcanvaswishlist();
            }
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

    // Wishlist Offcanvas Functions
    window.showoffcanvaswishlist = function() {
        const offcanvas = document.getElementById('offcanvas-wishlist');
        const panel = document.getElementById('offcanvas-wishlist-panel');
        if (offcanvas && panel) {
            offcanvas.classList.remove('hidden');
            offcanvas.style.display = 'block';
            
            // Force a reflow to ensure the element is visible before animation
            offcanvas.offsetHeight;
            
            // Start panel slide animation with a small delay for smooth transition
            requestAnimationFrame(() => {
                panel.classList.remove('translate-x-full');
            });
            
            document.body.style.overflow = 'hidden';
        }
    };

    window.hideoffcanvaswishlist = function() {
        const offcanvas = document.getElementById('offcanvas-wishlist');
        const panel = document.getElementById('offcanvas-wishlist-panel');
        if (offcanvas && panel) {
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                offcanvas.classList.add('hidden');
                offcanvas.style.display = 'none';
                // Reset panel position for next opening
                panel.classList.remove('translate-x-full');
                document.body.style.overflow = '';
            }, 300);
        }
    };

    // Cart Offcanvas Functions
    window.showoffcanvascart = function() {
        const offcanvas = document.getElementById('offcanvas-cart');
        const panel = document.getElementById('offcanvas-cart-panel');
        if (offcanvas && panel) {
            offcanvas.classList.remove('hidden');
            offcanvas.style.display = 'block';
            
            // Force a reflow to ensure the element is visible before animation
            offcanvas.offsetHeight;
            
            // Start panel slide animation with a small delay for smooth transition
            requestAnimationFrame(() => {
                panel.classList.remove('translate-x-full');
            });
            
            document.body.style.overflow = 'hidden';
        }
    };

    window.hideoffcanvascart = function() {
        const offcanvas = document.getElementById('offcanvas-cart');
        const panel = document.getElementById('offcanvas-cart-panel');
        if (offcanvas && panel) {
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                offcanvas.classList.add('hidden');
                offcanvas.style.display = 'none';
                // Reset panel position for next opening
                panel.classList.remove('translate-x-full');
                document.body.style.overflow = '';
            }, 300);
        }
    };

    // Close button event listeners
    const closeWishlistBtn = document.getElementById('close-wishlist-offcanvas');
    if (closeWishlistBtn) {
        closeWishlistBtn.addEventListener('click', function() {
            window.hideoffcanvaswishlist();
        });
    }

    const closeCartBtn = document.getElementById('close-cart-offcanvas');
    if (closeCartBtn) {
        closeCartBtn.addEventListener('click', function() {
            window.hideoffcanvascart();
        });
    }

    // Close on backdrop click
    const wishlistOffcanvas = document.getElementById('offcanvas-wishlist');
    if (wishlistOffcanvas) {
        wishlistOffcanvas.addEventListener('click', function(e) {
            if (e.target === wishlistOffcanvas) {
                window.hideoffcanvaswishlist();
            }
        });
    }

    const cartOffcanvas = document.getElementById('offcanvas-cart');
    if (cartOffcanvas) {
        cartOffcanvas.addEventListener('click', function(e) {
            if (e.target === cartOffcanvas) {
                window.hideoffcanvascart();
            }
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
                if (icon && icon.setAttribute) {
                icon.setAttribute('data-lucide', type === 'password' ? 'eye' : 'eye-off');
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                    }
                }
            });
        }
    };
    
    togglePassword('login-password', 'toggle-login-password');
    togglePassword('signup-password', 'toggle-signup-password');
    togglePassword('signup-confirm-password', 'toggle-confirm-password');
    
    // Login and signup form submissions are now handled in auth.js
    // No need for duplicate listeners here
    
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

async function updateWishlistCount() {
    try {
        const response = await window.api.getWishlist();
        const wishlistItems = response || [];
        const count = wishlistItems.length || 0;
        
        const wishlistCountElement = document.getElementById('wishlist-count');
        if (wishlistCountElement) {
            if (count > 0) {
                // Show "9+" if count is greater than 9, otherwise show actual count
                wishlistCountElement.textContent = count > 9 ? '9+' : count.toString();
                wishlistCountElement.classList.remove('hidden');
            } else {
                wishlistCountElement.classList.add('hidden');
            }
        }
    } catch (error) {
        // Keep the wishlist count element hidden on error
        const wishlistCountElement = document.getElementById('wishlist-count');
        if (wishlistCountElement) {
            wishlistCountElement.classList.add('hidden');
        }
    }
}

// ── Update Both Badge Counts Simultaneously ──
async function updateBothBadgeCounts() {
    try {
        // Run both badge updates in parallel
        const [cartResult, wishlistResult] = await Promise.allSettled([
            updateCartCount(),
            updateWishlistCount()
        ]);

        // Handle any errors from individual badge updates
        if (cartResult.status === 'rejected') {
            console.warn('Cart count update failed:', cartResult.reason);
        }
        if (wishlistResult.status === 'rejected') {
            console.warn('Wishlist count update failed:', wishlistResult.reason);
        }
    } catch (error) {
        console.warn('Failed to update badge counts:', error);
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

// ── Clear Cart State (for logout) ──
function clearCartState() {
    console.log('🟣 CLEAR CART STATE: Starting cart state clearing');
    
    try {
        // Clear cart UI elements
        const cartItems = document.getElementById('cart-items');
        const cartEmptyState = document.getElementById('cart-empty-state');
        const cartFooter = document.getElementById('cart-footer');
        const cartSubtotal = document.getElementById('cart-subtotal');
        const cartCount = document.getElementById('cart-count');
        
        console.log('🟣 CLEAR CART STATE: Found elements', {
            cartItems: !!cartItems,
            cartEmptyState: !!cartEmptyState,
            cartFooter: !!cartFooter,
            cartSubtotal: !!cartSubtotal,
            cartCount: !!cartCount
        });
        
        // Show empty state
        if (cartEmptyState) {
            cartEmptyState.style.display = 'block';
            console.log('🟣 CLEAR CART STATE: Empty state shown');
        }
        if (cartItems) {
            cartItems.style.display = 'none';
            cartItems.innerHTML = '';
            console.log('🟣 CLEAR CART STATE: Cart items cleared');
        }
        if (cartFooter) {
            cartFooter.classList.add('hidden');
            console.log('🟣 CLEAR CART STATE: Cart footer hidden');
        }
        if (cartSubtotal) {
            cartSubtotal.textContent = '₱0';
            console.log('🟣 CLEAR CART STATE: Subtotal reset');
        }
        if (cartCount) {
            cartCount.textContent = '0';
            cartCount.classList.add('hidden');
            console.log('🟣 CLEAR CART STATE: Cart count reset and hidden');
        }
        
        // Clear localStorage
        localStorage.removeItem('cart_items');
        localStorage.removeItem('wishlist_items');
        console.log('🟣 CLEAR CART STATE: LocalStorage cleared');
        
        console.log('🟣 CLEAR CART STATE: Cart state cleared successfully');
    } catch (error) {
        console.error('🟣 CLEAR CART STATE: Error clearing cart state:', error);
    }
}

async function performLoadCartItems() {
    console.log('performLoadCartItems called');
    const cartBody = document.getElementById('cart-body');
    const cartEmptyState = document.getElementById('cart-empty-state');
    const cartItems = document.getElementById('cart-items');
    const cartFooter = document.getElementById('cart-footer');
    const cartSubtotal = document.getElementById('cart-subtotal');
    
    console.log('Cart elements found:', {
        cartBody: !!cartBody,
        cartEmptyState: !!cartEmptyState,
        cartItems: !!cartItems,
        cartFooter: !!cartFooter,
        cartSubtotal: !!cartSubtotal
    });
      
    if (!cartBody) {
        console.error('cart-body element not found!');
        return;
    }

    try {
        console.log('Calling API to get cart...');
        const response = await window.api.getCart();
        console.log('Cart API response:', response);
        console.log('Response success:', response.success);
        console.log('Response data:', response.data);
        console.log('Get cart session ID:', response.session_id);
        
        if (!response.success) {
            console.error('API returned error:', response.message);
            return;
        }
        
        const cartData = response.data;
        console.log('Full cartData object:', cartData);
        console.log('cartData type:', typeof cartData);
        console.log('cartData keys:', Object.keys(cartData));
        
        const items = cartData.cart_items || [];
        console.log('Cart items found:', items.length, items);
        console.log('Session ID from response:', response.session_id);
        console.log('Debug info from response:', response.debug);
        console.log('Cart subtotal:', cartData.subtotal);
        console.log('Cart total_items:', cartData.total_items);
        
        // Debug the actual items structure
        if (items.length > 0) {
            console.log('=== ITEMS DEBUG ===');
            items.forEach((item, index) => {
                console.log(`Item ${index + 1}:`, {
                    id: item.id,
                    product_id: item.product_id,
                    product_name: item.product_name,
                    quantity: item.quantity,
                    unit_price: item.unit_price,
                    total_price: item.total_price,
                    session_id: item.session_id,
                    user_id: item.user_id,
                    product: item.product
                });
            });
            console.log('=== END ITEMS DEBUG ===');
        }
        
        // Debug each item
        if (items.length > 0) {
            console.log('=== CART ITEMS DEBUG ===');
            items.forEach((item, index) => {
                console.log(`Item ${index + 1}:`, {
                    id: item.id,
                    product_id: item.product_id,
                    product_name: item.product_name,
                    quantity: item.quantity,
                    unit_price: item.unit_price,
                    total_price: item.total_price,
                    session_id: item.session_id,
                    user_id: item.user_id
                });
            });
            console.log('=== END CART ITEMS DEBUG ===');
        }

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
                        <div class="cart-item-new border-b py-3" data-product-id="${item.product_id}">
                            <div class="flex items-center justify-between">
                                <!-- Left Section: Item Details -->
                                <div class="flex items-center space-x-4 flex-grow">
                                    <!-- Material Label -->
                                    <div class="material-label">
                                        <span class="text-sm text-gray-600 font-medium">${productData.material || 'Wood'}</span>
                                    </div>
                                    
                                    <!-- Item Info -->
                                    <div class="item-info">
                                        <h1 class="item-name text-sm font-semibold text-gray-900">${item.product_name}</h1>
                                        <p class="unit-price text-sm text-gray-700 mb-3">₱${parseFloat(item.unit_price).toLocaleString('en-US')}</p>
                                        
                                        <!-- Quantity Selector -->
                                        <div class="quantity-selector">
                                            <button class="qty-btn qty-minus" onclick="updateCartQuantity(${item.product_id}, ${item.quantity - 1})">-</button>
                                            <span class="qty-display">${item.quantity}</span>
                                            <button class="qty-btn qty-plus" onclick="updateCartQuantity(${item.product_id}, ${item.quantity + 1})">+</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Right Section: Actions & Total -->
                                <div class="cart-actions">
                                    <button class="remove-btn text-sm text-gray-500 hover:text-red-500 mb-2" onclick="removeFromCart(${item.product_id})">
                                        Remove
                                    </button>
                                    <div class="total-price text-base font-semibold text-gray-900">
                                        ₱${parseFloat(item.total_price).toLocaleString('en-US')}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                if (cartItems) {
                    console.log('Setting cart items HTML:', cartItemsHTML);
                    cartItems.innerHTML = cartItemsHTML;
                    console.log('Cart items HTML set successfully');
                } else {
                    console.error('cartItems element not found!');
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
        showNotification('Error removing item', 'error');
    }
}

// ── Initialize all modals and offcanvas components ──
function initializeAllComponents() {
    // Initialize all modals that exist
    const modals = ['modal-search', 'modal-login', 'modal-signup', 'modalQuickView'];
    
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
            credentials: 'include', // Include cookies for session management
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
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

// ── Render Products from Database (using unified function) ──
function renderProductsFromDatabase(products) {
    // Use the existing renderProductsWithFilter function to avoid duplication
    renderProductsWithFilter(products);
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

    // Initialize search modal
    initSearchModal();

    // Initialize products from database with filtering and sorting
    const productGrid = document.getElementById('product-grid');
    if (productGrid) {
        initProductsSection();
    }

    // Load both cart and wishlist counts simultaneously
    try {
        await updateBothBadgeCounts();
    } catch (error) {
    }

    await loadModalQuickView();
});