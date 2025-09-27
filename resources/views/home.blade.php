@extends('layouts.app')

@section('title', 'David\'s Wood Furniture - Handcrafteded furniture with timeless design.')

@section('content')
<!-- hero -->
<section class="hero-container" id="home">
    <div class="w-full hero-content">
        <div class="hero-text text-center">
            <span class="badge-text rounded-full px-4 py-1 flex items-center justify-center mx-auto">
                <i class="feather-small mr-2" data-lucide="pencil-ruler"></i>
                Crafted with specialty in Tagyatay
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mt-4">
                Handcrafted wooden furniture,<br>
                refined to perfection.
            </h1>
            <p class="text-lg md:text-xl mt-3 mb-5">
                Discover heirloom-quality pieces designed with timeless shapes, natural materials,<br>and meticulous craftsmanship—made to live with you for years.
            </p>
            <a href="#products" class="bg-black text-white px-6 py-3 text-lg font-semibold rounded-xl mr-3 inline-block">Browse products</a>
            <span class="text-gray-700 text-lg">Sustainably sourced. Built to last.</span>
        </div>
        <section class="hero-section">
            <div class="hero-slider w-full">
                <div class="slide active" data-name="Handcrafted Chair" data-price="₱12,999">
                    <img data-src="{{ asset('frontend/assets/chair.png') }}" alt="Chair 1" class="img-chair lazy-load" draggable="false">
                </div>
                <div class="slide" data-name="Modern Sofa" data-price="₱24,500">
                    <img data-src="{{ asset('frontend/assets/cabinet.png') }}" alt="Sofa 1" class="img-cabinet lazy-load" draggable="false">
                </div>
                <div class="slide" data-name="Wooden Table" data-price="₱16,800">
                    <img data-src="{{ asset('frontend/assets/floor-lamp.png') }}" alt="Table 1" class="img-lamp lazy-load" draggable="false">
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="product-grid">
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
                        <img data-src="{{ asset('frontend/assets/about-1.jpg') }}" alt="Hand tools before machines" class="w-full h-full object-cover lazy-load">
                        <div class="caption text-sm p-3">
                            Hand tools before machines—feel the surface, read the grain.
                        </div>
                    </div>
                    <div class="flex-1 min-h-0 overflow-hidden rounded-lg image-card">
                        <img data-src="{{ asset('frontend/assets/about-2.jpg') }}" alt="Natural oil-wax finishes" class="w-full h-full object-cover lazy-load">
                        <div class="caption text-sm p-3">
                            Natural oil-wax finishes enhance, never mask, the wood.
                        </div>
                    </div>
                </div>
                <div class="flex-1 min-h-0 overflow-hidden rounded-lg image-card">
                    <img data-src="{{ asset('frontend/assets/about-3.jpg') }}" alt="Sunlit workshop" class="w-full h-full object-cover lazy-load">
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
