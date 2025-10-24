@extends('layouts.app')

@section('title', 'David\'s Wood Furniture - Handcrafteded furniture with timeless design.')

@section('content')
<style>
/* Home page specific styles */

/* Hero Section */
.hero-container {
  background-image: url({{ asset('frontend/assets/hero-bg.jpg') }});
  background-size: cover;
  background-position: bottom;
  position: relative;
  min-height: 100vh;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
}

.hero-content {
  transform: translateY(23%);
}

.badge-text {
  background-color: rgba(243, 239, 231, 0.9);
  font-size: 0.8rem;
  font-weight: 500;
  padding: 0.5rem 1rem;
  width: max-content;
  display: inline-flex;
  align-items: center;
}

.hero-container .hero-text {
  transform: translateY(-50%);
}

.btn-browse:hover {
  background-color: #0c0c0c;
}

/* Hero Carousel */
.hero-section {
  position: absolute;
  width: 100%;
  height: 80vh;
  overflow: hidden;
  transform: translateY(-67%);
  z-index: -100;
}

.hero-slider {
  position: relative;
  height: 100%;
}

.slide {
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  display: flex;
  transform: translateX(100%);
  transition: transform 0.6s ease-out, opacity 0.6s ease-out;
}

.slide.active {
  opacity: 1;
  transform: translateX(0);
}

.img-chair {
  width: 32rem;
  height: max-content;
  translate: -50% 0%;
  position: absolute;
  right: 0;
  bottom: 0;
  filter: drop-shadow(4px 4px 8px rgba(0, 0, 0, 0.5));
}

.img-cabinet {
  width: max-content;
  height: 35rem;
  translate: 50% 0%;
  position: absolute;
  bottom: 0;
  filter: drop-shadow(2px 4px 8px rgba(0, 0, 0, 0.5));
}

.img-lamp {
  width: max-content;
  height: 40rem;
  translate: -10% -10%;
  position: absolute;
  right: 0;
  bottom: 0;
  filter: drop-shadow(4px 4px 8px rgba(0, 0, 0, 0.5));
}

/* Hero Indicators */
.hero-indicators {
  position: absolute;
  bottom: 2rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 0.5rem;
}

.indicator {
  width: 12px;
  height: 4px;
  background-color: rgba(255, 255, 255, 0.3);
  border-radius: 2px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.indicator.active {
  background-color: white;
}

/* Featured Furniture Section */
.featured-furniture {
  background-color: rgba(243, 239, 231);
  padding: 5rem 13rem;
}

@media (max-width: 1200px) {
  .featured-furniture {
    padding: 3rem 2rem;
  }
}

@media (max-width: 768px) {
  .featured-furniture {
    padding: 2rem 1rem;
  }
}

.featured-furniture h2 {
  font-size: 1.75rem;
  font-weight: 600;
  color: #1a1a1a;
}

.featured-furniture p {
  color: #666;
  font-size: 0.95rem;
  margin-bottom: 2rem;
}

/* Filters */
.filter-group {
  display: flex;
  gap: 4px;
  margin-bottom: 1rem;
  background: none;
  border-radius: 25px;
  box-shadow: 0 0 2px rgba(0,0,0,0.05);
}

.filter-btn {
  padding: 6px 12px;
  font-size: 0.875rem;
  font-weight: 500;
  color: #555555;
  background: none;
  border: 1px solid transparent;
  border-radius: 25px;
  cursor: pointer;
  transition: all 0.2s ease-in-out;
}

.filter-btn:hover {
  background-color: rgba(255, 255, 255, 0.2);
  color: #1a1a1a;
}

.filter-btn.active {
  background-color: rgba(255, 255, 255, 0.2);
  box-shadow: 1px 1px 2px rgba(0,0,0,0.1);
  color: #1a1a1a;
}

.sort-group span {
  font-size: 0.9rem;
}

.sort-group select {
  font-size: 0.875rem;
  padding: 0.375rem 0.75rem;
  border-radius: 6px;
  border: 1px solid #ddd;
  background: none;
  box-shadow: 0 0 3px rgba(221, 221, 221, 0.5);
}

.sort-group .form-select {
  appearance: none;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 16px 12px;
  padding-right: 2.5rem; 
}

/* See More Button */
#see-more-btn {
  background-color: #1a1a1a;
  color: white;
  border: none;
  padding: 0.5rem 1.5rem;
  font-size: 1.1rem;
  font-weight: 500;
  transition: background-color 0.2s ease;
}

#see-more-btn:hover {
  background-color: rgba(43, 58, 42, 0.75);
}

/* About Section */
.about-container {
  background-color: rgba(243, 239, 231);
  padding: 5rem 13rem;
}

@media (max-width: 1200px) {
  .about-container {
    padding: 3rem 2rem;
  }
}

@media (max-width: 768px) {
  .about-container {
    padding: 2rem 1rem;
  }
}

.about-section {
  background-color: #fefefe;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}

.about-section .card, .container-craft {
  background-color: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.about-section .card-body {
  padding: 1.5rem;
}

.about-section h2 {
  font-size: 1.75rem;
  font-weight: 600;
  color: #1a1a1a;
}

.about-section h5 {
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.about-section .icon {
  display: inline-block;
  width: 40px;
  height: 40px;
  background-color: #f2ede5;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.5rem;
  font-size: 30px;
  color: #1a1a1a;
}

.icon-line {
  display: inline-block;
  width: 4px;
  height: 1.5rem;
  background-color: rgba(240, 178, 122, 0.7);
  margin-right: 0.5rem;
  vertical-align: middle;
  border-radius: 10px;
}

.image-card {
  position: relative;
  overflow: hidden;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.image-card img {
  transition: transform 0.3s ease;
}

.image-card:hover img {
  transform: scale(1.02);
}

.caption {
  background-image: linear-gradient(to top, rgba(69, 69, 69, 0.7), rgba(255, 255, 255, 0));
  padding: 0.5rem;
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  font-size: 0.875rem;
  color: #fff;
}

.step-number {
  height: 2rem;
  width: 2rem;
  font-size: 1rem;
  line-height: 1;
  font-weight: 600;
}

.card-title strong {
  font-size: 1.05rem;
}

.badge {
  background-color: rgba(243, 239, 231);
  font-size: 0.8rem;
  font-weight: 500;
  width: max-content;
  border: 0.5px solid #e0e0e0;
  padding: 0 0.5rem;
}

.badge .icon {
  background: none;
  height: 16px;
  width: 16px;
  color: #ffc107;
}

/* Utility classes for home page */
.feather-small {
  width: 17px;
  height: 17px;
}

.lucide-small {
  height: 18px;
  width: 18px;
}
</style>
<!-- hero -->
<section class="hero-container" id="home">
    <div class="w-full hero-content">
        <div class="hero-text text-center">
            <span class="badge-text rounded-full px-4 py-1 flex items-center justify-center mx-auto">
                <i class="feather-small mr-2" data-lucide=""></i>
                Nature's grain shaped by artistry
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mt-4">
                {{-- Handcrafted wooden furniture,<br>
                refined to perfection. --}}
                Beauty carved with intention,<br>shaped by passion.
            </h1>
            <p class="text-lg md:text-xl mt-3 mb-5">
                Discover heirloom-quality pieces designed with timeless shapes, natural materials,<br>and meticulous craftsmanship—made to live with you for years.
            </p>
            <a href="#products" class="btn-browse bg-black text-white px-6 py-3 text-lg font-semibold rounded-xl mr-3 inline-block">Browse products</a>
            <span class="text-gray-700 text-lg">Sustainably sourced. Built to last.</span>
        </div>
        <section class="hero-section">
            <div class="hero-slider w-full">
                <div class="slide active" data-name="Handcrafted Chair" data-price="₱12,999">
                    <img src="{{ asset('frontend/assets/chair.png') }}" alt="Chair 1" class="img-chair" draggable="false">
                </div>
                <div class="slide" data-name="Modern Sofa" data-price="₱24,500">
                    <img src="{{ asset('frontend/assets/cabinet.png') }}" alt="Sofa 1" class="img-cabinet" draggable="false">
                </div>
                <div class="slide" data-name="Wooden Table" data-price="₱16,800">
                    <img src="{{ asset('frontend/assets/floor-lamp.png') }}" alt="Table 1" class="img-lamp" draggable="false">
                </div>
            </div>
            <div class="hero-indicators">
                <span class="indicator active"></span>
                <span class="indicator"></span>
                <span class="indicator"></span>
            </div>
        </section>
    </div>
</section>

<!-- products -->
<section class="featured-furniture" id="products">
    <h2>Featured Furniture</h2>
    <p>Handcrafted pieces in natural wood tones. Subtle 3D interactions, refined details.</p>
    <!-- Filters -->
    <div class="flex justify-between items-center mb-5">
        <div class="filter-group">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="beds">Beds</button>
            <button class="filter-btn" data-filter="cabinets">Cabinets</button>
            <button class="filter-btn" data-filter="chairs">Chairs</button>
            <button class="filter-btn" data-filter="tables">Tables</button>
            <button class="filter-btn" data-filter="shelves">Shelves</button>
            <button class="filter-btn" data-filter="sofas">Sofas</button>
        </div>
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
    </div>
    <!-- Product Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="product-grid" data-page="home">
        <!-- Products will be injected here -->
    </div>
    <!-- See More Button -->
    <div class="text-center mt-12">
        <a href="{{ route('products') }}">
            <button class="btn rounded-xl" id="see-more-btn" href="{{ route('products') }}">See more</button>
        </a>
    </div>
</section>

<!-- about-->
<!-- about-->
<section class="about-container" id="about">
    <div class="about-section p-8 rounded-2xl flex flex-col gap-5">
        <div class="w-full">
            <div class="flex items-center mb-3">
                <div class="icon mr-3"><i data-lucide="sparkle"></i></div>
                <h2 class="mt-2">Crafted by hand. Guided by nature.</h2>
            </div>
            <p class="text-gray-500 mb-4">
                At David's Wood, every curve, joint, and finish is shaped by hand—celebrating the grain, honoring the tree, and building furniture that lives with you for decades.
            </p>
        </div>
        <div class="flex flex-1 gap-4 min-h-0">
            <div class="flex flex-col flex-1 gap-4 min-h-0 w-full lg:w-1/2">
                <div class="card border border-gray-200 rounded-2xl p-5">
                    <h5 class="mb-2"><span class="icon-line"></span> Our Story</h5>
                    <p class="text-gray-500">
                        David began in a small workshop with a single promise: to craft pieces that age beautifully. What started with a hand-sawn stool has grown into a studio where traditional joinery meets modern design—always with the grain as our guide. Today, that same care and attention flows through every joint, curve, and finish—because furniture should feel as honest as it looks.
                    </p>
                </div>
                <div class="card border border-gray-200 rounded-2xl p-5">
                    <h5 class="mb-2">
                        <span class="icon-line" style="background-color: rgba(46, 107, 74, 0.7);"></span> 
                        Commitment to Quality
                    </h5>
                    <p class="text-gray-500 mb-3">
                        We select boards by hand, aligning grain and tone across each panel. Mortise-and-tenon joinery, precision planing, and natural oil finishes ensure structural integrity without compromising feel—resulting in heirloom-grade furniture.
                    </p>
                    <ul class="list-none text-gray-500 space-y-1">
                        <li class="flex items-center gap-2">
                          <i class="lucide-small" data-lucide="check-circle"></i>
                          <span>Hand-matched grain and tone</span>
                        </li>
                        <li class="flex items-center gap-2">
                          <i class="lucide-small" data-lucide="check-circle"></i>
                          <span>Joinery-first construction</span>
                        </li>
                        <li class="flex items-center gap-2">
                          <i class="lucide-small" data-lucide="check-circle"></i>
                          <span>Low-VOC, plant-based finishes</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="lucide-small" data-lucide="check-circle"></i>
                            <span>Solid hardwoods sourced from sustainably managed forests</span>
                        </li>
                    </ul>
                </div>
                <div class="card border border-gray-200 rounded-2xl p-5">
                    <h5 class="mb-2"><span class="icon-line"></span> Sustainable Practices</h5>
                    <p class="text-gray-500 mb-3">
                        We partner with local mills and FSC-compliant suppliers, use offcuts thoughtfully, and build for longevity—because the most sustainable furniture is the one you never need to replace.
                    </p>
                    <div class="flex gap-2 flex-wrap">
                        <span class="badge bg-gray-100 text-gray-800 px-3 py-1 rounded-lg flex items-center gap-1.5">
                          <i data-lucide="ribbon"></i>
                          <span>FSC-aligned sourcing</span>
                        </span>
                        <span class="badge bg-gray-100 text-gray-800 px-3 py-1 rounded-lg flex items-center gap-1.5">
                          <i data-lucide="scroll-text"></i>
                          <span>Lifetime structural guarantee</span>
                        </span>
                      </div>
                </div>
            </div>
            <div class="flex flex-col flex-1 gap-4 min-h-0"">
                <div class="flex flex-1 gap-4 min-h-0">
                    <div class="flex-1 min-h-0 overflow-hidden rounded-lg image-card">
                        <img src="{{ asset('frontend/assets/about-1.jpg') }}" alt="Hand tools before machines" class="w-full h-full object-cover">
                        <div class="caption text-sm p-3">
                            Hand tools before machines—feel the surface, read the grain.
                        </div>
                    </div>
                    <div class="flex-1 min-h-0 overflow-hidden rounded-lg image-card">
                        <img src="{{ asset('frontend/assets/about-2.jpg') }}" alt="Natural oil-wax finishes" class="w-full h-full object-cover">
                        <div class="caption text-sm p-3">
                            Natural oil-wax finishes enhance, never mask, the wood.
                        </div>
                    </div>
                </div>
                <div class="flex-1 min-h-0 overflow-hidden rounded-lg image-card">
                    <img src="{{ asset('frontend/assets/about-3.jpg') }}" alt="Sunlit workshop" class="w-full h-full object-cover">
                    <div class="caption text-sm p-3">
                        Sunlit workshop where ideas meet the bench.
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-1 gap-4 min-h-0 mt-4">
            <div class="container-craft border border-gray-200 rounded-2xl p-6">
                <h5 class="mb-3">Craft Process</h5>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="card border border-gray-200 rounded-2xl p-5 text-center h-full">
                        <div class="card-title flex items-center">
                            <div class="step-number bg-black text-white rounded-lg inline-block text-center mr-3 p-2">1</div>
                            <strong>Select & Season</strong>
                        </div>
                        <p class="text-gray-500 text-sm m-0 text-left">Boards chosen for grain continuity; acclimated for stability.</p>
                    </div>
                    <div class="card border border-gray-200 rounded-2xl p-5 text-center h-full">
                        <div class="card-title flex items-center">
                            <div class="step-number bg-black text-white rounded-lg inline-block text-center mr-3 p-2">2</div>
                            <strong>Join & Shape</strong>
                        </div>
                        <p class="text-gray-500 text-sm m-0 text-left">Joinery cut by hand; profiles refined to the touch.</p>
                    </div>
                    <div class="card border border-gray-200 rounded-2xl p-5 text-center h-full">
                        <div class="card-title flex items-center">
                            <div class="step-number bg-black text-white rounded-lg inline-block text-center mr-3 p-2">3</div>
                            <strong>Assemble & Refine</strong>
                        </div>
                        <p class="text-gray-500 text-sm m-0 text-left">Dry-fit, clamp, and fettle for invisible seams.</p>
                    </div>
                    <div class="card border border-gray-200 rounded-2xl p-5 text-center h-full">
                        <div class="card-title flex items-center">
                            <div class="step-number bg-black text-white rounded-lg inline-block text-center mr-3 p-2">4</div>
                            <strong>Finish & Protect</strong>
                        </div>
                        <p class="text-gray-500 text-sm m-0 text-left">Oil-wax finish applied in thin coats; cured for durability.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-shrink-0 gap-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div class="w-full">
                    <div class="card border border-gray-200 rounded-2xl p-5 h-full">
                        <p class="mb-2 text-lg">"The attention to detail is extraordinary—drawers glide, edges feel soft, and the finish glows."</p>
                        <div class="flex flex-wrap items-center justify-between">
                            <small class="text-gray-500">— Lena M. Dining Table, 2024</small>
                            <div class="badge bg-gray-100 text-gray-800 ml-2 items-center rounded-full inline-flex">
                                <i data-lucide="sparkle" class="icon"></i>
                                <span><small>Verified</small></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="card border border-gray-200 rounded-2xl p-5 h-full">
                        <p class="mb-2 text-lg">"Our credenza is stunning. You can tell it was built to last from the joinery alone."</p>
                        <div class="flex flex-wrap items-center justify-between">
                            <small class="text-gray-500">— Noah & Priya Custom Credenza, 2025</small>
                            <div class="badge bg-gray-100 text-gray-800 ml-2 items-center rounded-full inline-flex">
                                <i data-lucide="sparkle" class="icon"></i>
                                <span><small>Verified</small></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="card border border-gray-200 rounded-2xl p-5 h-full">
                    <div class="flex items-center mb-2">
                        <div class="icon">
                            <i data-lucide="ribbon"></i>
                        </div>
                        <strong>Quality & Sustainability</strong>
                    </div>
                    <p class="text-gray-500 text-sm mb-2">
                        Built for lifetime use with sustainably sourced hardwoods. Every piece includes care guidance and a structural guarantee.
                      </p>
                      <ul class="list-none text-gray-500 text-sm space-y-1">
                        <li class="flex items-center gap-2">
                          <i class="lucide-small" data-lucide="check-circle"></i>
                          <span>Responsibly sourced hardwoods</span>
                        </li>
                        <li class="flex items-center gap-2">
                          <i class="lucide-small" data-lucide="check-circle"></i>
                          <span>Low-VOC finish systems</span>
                        </li>
                        <li class="flex items-center gap-2">
                          <i class="lucide-small" data-lucide="check-circle"></i>
                          <span>Lifetime structural guarantee</span>
                        </li>
                      </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Check if we should show login modal (from /login redirect)
    @if(session('show_login_modal'))
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a moment for the page to fully load
            setTimeout(function() {
                const loginModal = document.getElementById('login-modal');
                if (loginModal) {
                    loginModal.classList.remove('hidden');
                    loginModal.classList.add('flex');
                }
            }, 500);
        });
    @endif
</script>
@endpush
