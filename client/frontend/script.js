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
        const show = function() { el.classList.remove('hidden'); };
        const hide = function() { el.classList.add('hidden'); };

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

    // ── 6. HERO SLIDER (jQuery) ──
    if ($('.hero-slider').length) {
        initHeroSlider();
    }

    // ── 7. LOAD FOOTER ──
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