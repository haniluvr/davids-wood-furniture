@extends('layouts.app')

@section('title', 'Products - David\'s Wood Furniture')

@section('content')
<style>
/* Sticky filter bar styles */
.sticky-filter-bar {
    position: sticky;
    top: 64px;
    z-index: 40;
    background: rgba(243, 239, 231, 0.1);
    backdrop-filter: blur(25px);
    margin: 0 -13rem;
    padding: 0rem 13rem;
    transition: all 0.3s ease;
}

/* Stuck state - white background when scrolled */
.sticky-filter-bar.is-stuck {
    background: rgba(255, 255, 255, 1);
    backdrop-filter: blur(20px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.filter-group {
    margin: 0.7rem 0;
}

.sort-group,
.room-group {
    font-size: 0.875rem; /* 14px - match base text size */
}

.sort-group select,
.room-group select {
    background-color: rgba(255, 255, 255, 0.5);
    font-size: 0.875rem; /* 14px */
    color: #374151; /* gray-700 - match text color */
    font-weight: 500;
    line-height: 1.5;
}

.sticky-filter-bar.is-stuck .sort-group select,
.sticky-filter-bar.is-stuck .room-group select {
    background-color: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.sort-group span,
.room-group span {
    font-size: 0.875rem; /* 14px */
    color: #6b7280; /* gray-500 */
    font-weight: 400;
}

@media (max-width: 1200px) {
    .sticky-filter-bar {
        margin: 0 -2rem;
        padding-left: 2rem;
        padding-right: 2rem;
    }
}

@media (max-width: 768px) {
    .sticky-filter-bar {
        margin: 0 -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        top: 60px;
    }
    
    .sticky-filter-bar .flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .sticky-filter-bar .filter-group {
        width: 100%;
        overflow-x: auto;
        white-space: nowrap;
    }
}

/* Pagination styles */
.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin-top: 3rem;
    padding: 1.5rem 0;
}

.pagination-btn {
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    background-color: white;
    color: #555;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
    font-weight: 500;
    min-width: 40px;
    text-align: center;
}

.pagination-btn:hover:not(:disabled) {
    background-color: rgba(255, 255, 255, 0.5);
    border-color: #1a1a1a;
    color: #1a1a1a;
}

.pagination-btn.active {
    background-color: #1a1a1a;
    color: white;
    border-color: #1a1a1a;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-info {
    color: #666;
    font-size: 0.875rem;
    padding: 0 1rem;
}
</style>

<!-- products -->
<section class="featured-furniture" id="products">
    <h2 class="mt-5 pt-5">Product Catalogue</h2>
    <p>Handcrafted pieces in natural wood tones. Subtle 3D interactions, refined details.</p>
    
    <!-- Sticky Filters and Sort -->
    <div class="sticky-filter-bar">
        <div class="flex justify-between items-center">
            <div class="filter-group">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="beds">Beds</button>
                <button class="filter-btn" data-filter="cabinets">Cabinets</button>
                <button class="filter-btn" data-filter="chairs">Chairs</button>
                <button class="filter-btn" data-filter="tables">Tables</button>
                <button class="filter-btn" data-filter="shelves">Shelves</button>
                <button class="filter-btn" data-filter="sofas">Sofas</button>
            </div>
            <div class="flex items-center gap-4">
                <div class="sort-group flex items-center">
                    <i data-lucide="list-filter" class="lucide-small mr-2"></i>
                    <span class="mr-2 text-gray-500">Sort</span>
                    <select class="rounded-lg font-medium border border-gray-300 px-3 py-2 bg-white" id="sort-select">
                        <option value="popularity">Popularity</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="newest">Newest</option>
                    </select>
                </div>
                <div class="room-group flex items-center">
                    <i data-lucide="home" class="lucide-small mr-2"></i>
                    <span class="mr-2 text-gray-500">Room</span>
                    <select class="rounded-lg font-medium border border-gray-300 px-3 py-2 bg-white" id="room-select">
                        <option value="all">All Rooms</option>
                        <option value="bedroom">Bedroom</option>
                        <option value="living-room">Living Room</option>
                        <option value="dining-room">Dining Room</option>
                        <option value="bathroom">Bathroom</option>
                        <option value="office">Office</option>
                        <option value="garden-and-balcony">Garden & Balcony</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-4" id="product-grid" data-page="products">
        <!-- Products will be injected here -->
    </div>
    
    <!-- Pagination -->
    <div class="pagination-container" id="pagination-container">
        <!-- Pagination will be injected here -->
    </div>
</section>
@endsection

@push('scripts')
<script>
// Sticky filter bar scroll detection
document.addEventListener('DOMContentLoaded', function() {
    const stickyBar = document.querySelector('.sticky-filter-bar');
    const productsSection = document.getElementById('products');
    
    if (!stickyBar || !productsSection) return;
    
    // Get the initial position of the sticky bar
    const stickyBarTop = stickyBar.offsetTop;
    
    // Simple scroll handler to detect stuck state
    function checkSticky() {
        const scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
        
        // If we've scrolled past the sticky bar's original position, it's stuck
        if (scrollPosition > stickyBarTop - 64) {
            stickyBar.classList.add('is-stuck');
        } else {
            stickyBar.classList.remove('is-stuck');
        }
    }
    
    // Check on scroll
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                checkSticky();
                ticking = false;
            });
            ticking = true;
        }
    });
    
    // Check initial state
    checkSticky();
});
</script>
@endpush
