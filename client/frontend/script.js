// ──────────────────────────────────────────────────────────────────────
// 🚀 script.js — Centralized Component & Feature Loader
// ──────────────────────────────────────────────────────────────────────

import { products } from './data/products.js';

// ── Generic Component Loader ──
async function loadComponent(url, targetId, initCallback = null) {
    const container = document.getElementById(targetId);
    if (!container) return;

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error(`Failed to load ${url}`);

        container.innerHTML = await response.text();

        // Always re-init Feather icons after injecting HTML
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Make sure Lucide is available
        if (typeof lucide !== 'undefined') {
            lucide.replace(); // Run once on page load
        }

        // Run optional init logic (Bootstrap, event listeners, etc.)
        if (initCallback && typeof initCallback === 'function') {
            initCallback();
        }

    } catch (error) {
        console.error(`Error loading component from ${url}:`, error);
    }
}

// ── Initialize Bootstrap Offcanvas (Reusable) ──
function initOffcanvas(id) {
    const el = document.getElementById(id);
    if (el) {
        new bootstrap.Offcanvas(el, {
            backdrop: true,
            scroll: true,
            keyboard: true
        });
    }
}

// ── Initialize Bootstrap Modal (Reusable) ──
function initModal(id) {
    const el = document.getElementById(id);
    if (el) {
        new bootstrap.Modal(el);
    }
}

// ── Initialize Products Section (Featured Furniture) ──
function initProductsSection() {
    const grid = document.getElementById('product-grid');
    const seeMoreBtn = document.getElementById('see-more-btn');

    if (!grid) return;

    let displayedProducts = [];

    // Initial render (first 6)
    renderProducts(products.slice(0, 6));

    // Filter buttons
    document.querySelectorAll('.btn[data-filter]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.btn[data-filter]').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.getAttribute('data-filter');
            const filtered = products.filter(p => filter === 'all' || p.category === filter);
            renderProducts(filtered.slice(0, 6));
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
            default: // popularity = by rating
                sorted.sort((a, b) => b.rating - a.rating);
        }

        renderProducts(sorted.slice(0, 6));
    });

    // See more
    seeMoreBtn?.addEventListener('click', () => {
        const currentCount = displayedProducts.length;
        const nextProducts = products.slice(currentCount, currentCount + 6);
        renderProducts([...displayedProducts, ...nextProducts]);
    });

    // Render function
    function renderProducts(products) {
        grid.innerHTML = '';
        displayedProducts = products;

        products.forEach(product => {
            const col = document.createElement('div');
            col.className = 'col-lg-4 col-md-6';

            col.innerHTML = `
                <div class="card product-card h-100 rounded-4">
                    <img src="${product.image}" class="card-img" alt="${product.name}">
                    <div class="card-img-overlay d-flex">
                        <div class="flex-fill">
                            <div class="rounded-pill stock-badge ${product.stock === 'low' ? 'low' : 'in-stock'}">
                            ${product.stock === 'low' ? 'Low stock' : 'In stock'}
                        </div>
                        <div class="text-end text-white">
                            <span class="rating">
                                <i data-lucide="star" class="lucide-small me-1"></i> ${product.rating}
                            </span>
                        </div>
                        <button class="heart" onclick="event.stopPropagation();">
                            <i data-lucide="heart"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-9">
                            <h6 class="product-title fs-5 fw-semibold">${product.name}</h6>
                            <p class="product-desc">${product.desc}</p>
                        </div>
                        <div class="col col-md-3 text-end">
                            <div class="text-muted">Price</div>
                            <div class="price">₱${product.price.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</div>
                        </div>
                    </div>
                    <div class="buttons mt-3">
                        <div class="btn-quick-view-icon p-1 d-flex flex-row align-items-center">
                            <button class="btn btn-quick-view" data-product-id="${product.id}">
                                <i data-lucide="proportions" class="lucide-small"></i> 
                                <span class="fw-medium ms-2">Quick view</span>
                            </button>
                        </div>
                        <div class="btn-quick-view-icon p-1 d-flex flex-row align-items-center">
                            <button class="btn btn-add-to-cart" data-product-id="${product.id}">
                                <i data-lucide="shopping-cart" class="lucide-small"></i> 
                                <span class="fw-medium ms-2">Add to cart</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            grid.appendChild(col);
        });

        // Re-init feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        

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

            // Fill modal with product data
            document.getElementById('quickViewLabel').textContent = product.name;
            document.getElementById('quick-view-image').src = product.image;
            document.getElementById('quick-view-desc').textContent = product.desc;
            document.getElementById('quick-view-rating').textContent = product.rating;
            document.getElementById('quick-view-price').textContent = `₱${product.price.toLocaleString('en-US', { 
                minimumFractionDigits: 0, maximumFractionDigits: 0
            })}`;
            document.getElementById('quick-view-material').textContent = product.material;
            document.getElementById('quick-view-dimensions').textContent = product.dimensions;

            // Show modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modal-quick-view'));
            modal.show();

            // ✅ Re-init Lucide icons after modal is fully opened
            modal._element.addEventListener('shown.bs.modal', function () {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
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

        // Initialize Bootstrap Modal
        const modalEl = document.getElementById('modal-quick-view');
        if (modalEl) {
            new bootstrap.Modal(modalEl);
        }

        // ✅ Re-init Lucide icons after injecting modal HTML
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

    } catch (error) {
        console.error('Error loading quick view modal:', error);
    }
}

// ── DOM Ready Handler ──
document.addEventListener('DOMContentLoaded', function () {

    // ── 1. SEARCH MODAL ──
    loadComponent(
        'components/modal-search.html',
        'modal-search-container',
        () => initModal('modal-search')
    );

    // Attach click handler
    const openSearchBtn = document.getElementById('openSearchModal');
    if (openSearchBtn) {
        openSearchBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const modalEl = document.getElementById('modal-search');
            if (modalEl) {
                const bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                bsModal.show();
            }
        });
    }

    // ── 2. WISHLIST OFFCANVAS ──
    loadComponent(
        'components/offcanvas-wishlist.html',
        'offcanvas-container',
        () => initOffcanvas('offcanvas-wishlist')
    );

    // Attach click handler
    const openWishlistBtn = document.getElementById('openOffcanvas');
    if (openWishlistBtn) {
        openWishlistBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const el = document.getElementById('offcanvas-wishlist');
            if (el) {
                const bs = bootstrap.Offcanvas.getInstance(el) || new bootstrap.Offcanvas(el);
                bs.show();
            }
        });
    }

    // ── 3. CART OFFCANVAS ──
    loadComponent(
        'components/offcanvas-cart.html',
        'offcanvas-cart-container',
        () => initOffcanvas('offcanvas-cart')
    );

    // Attach click handler
    const openCartBtn = document.getElementById('openCartOffcanvas');
    if (openCartBtn) {
        openCartBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const el = document.getElementById('offcanvas-cart');
            if (el) {
                const bs = bootstrap.Offcanvas.getInstance(el) || new bootstrap.Offcanvas(el);
                bs.show();
            }
        });
    }

    // ── 4. PRODUCTS SECTION (Featured Furniture) ──
    if (document.getElementById('product-grid')) {
        initProductsSection();
    }

    // ── 5. QUICK VIEW MODAL ──
    loadQuickViewModal();

});