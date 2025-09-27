@extends('layouts.app')

@section('title', 'Products - David\'s Wood Furniture')

@section('content')
<!-- products -->
<section class="featured-furniture" id="products">
    <h2 class="mt-5 pt-5">Product Catalogue</h2>
    <p>Handcrafted pieces in natural wood tones. Subtle 3D interactions, refined details.</p>
    <!-- Filters -->
    <div class="flex justify-between items-center mb-4">
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
</section>
@endsection
