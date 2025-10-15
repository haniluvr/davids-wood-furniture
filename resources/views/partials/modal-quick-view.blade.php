<!-- Compact Quick View Modal -->
<div class="modal fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50 hidden" id="modalQuickView">
  <div class="modal-content bg-stone-50 rounded-2xl shadow-2xl max-w-4xl w-full mx-4 overflow-hidden transform transition-all duration-300 ease-out">
    
    <!-- Header -->
    <div class="relative bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="w-2 h-6 bg-gradient-to-b from-amber-400 to-orange-500 rounded-full"></div>
          <h5 class="text-xl font-bold text-gray-800" id="quickViewLabel">Product Preview</h5>
        </div>
        <button type="button" class="text-gray-400 hover:text-gray-600 hover:bg-white hover:bg-opacity-50 rounded-full p-2 transition-all duration-200" id="close-modalQuickView-modal">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Image Section -->
        <div class="space-y-3">
          <div class="relative group overflow-hidden rounded-xl">
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10"></div>
            
            <img src="" alt="Product Image" class="w-full h-80 object-cover rounded-xl shadow-lg transition-all duration-300" id="quick-view-image">
            
            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm rounded-full p-1.5 shadow-lg z-20">
              <i data-lucide="zoom-in" class="w-4 h-4 text-gray-600"></i>
            </div>
          </div>

          <!-- Image Thumbnails -->
          <div class="flex justify-start space-x-2" id="image-thumbnails">
            <!-- Thumbnails will be dynamically generated -->
          </div>
        </div>

        <!-- Product Details Section -->
        <div class="space-y-4">
          
          <!-- Price and Rating -->
          <div class="flex items-start justify-between">
            <div class="space-y-2">
              <div class="text-2xl font-bold text-gray-900" id="quick-view-price">₱0.00</div>
              <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-1" id="star-rating-container">
                  <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                  <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                  <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                  <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                  <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                </div>
                <span class="text-sm font-medium text-gray-600" id="quick-view-rating">No rating</span>
              </div>
            </div>
            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
              In Stock
            </div>
          </div>

          <!-- Description -->
          <div class="bg-gray-50 rounded-lg p-3">
            <p class="text-gray-700 text-sm leading-relaxed" id="quick-view-desc">Product description will appear here...</p>
          </div>

          <!-- Quantity Selector -->
          <div class="space-y-2">
            <label class="block text-sm font-semibold text-gray-700">Quantity</label>
            <div class="flex items-center">
              <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                <button class="px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-50 transition-colors" type="button" id="decrease-qty">
                  <i data-lucide="minus" class="w-4 h-4"></i>
                </button>
                <input type="number" class="w-12 px-2 py-2 text-center font-semibold border-0 focus:outline-none focus:ring-0 text-sm" value="1" min="1" id="quantity-input">
                <button class="px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-50 transition-colors" type="button" id="increase-qty">
                  <i data-lucide="plus" class="w-4 h-4"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex space-x-3 pt-2">
            <button class="flex-1 bg-white border border-gray-200 hover:border-red-500 text-gray-700 hover:text-red-500 px-4 py-2 rounded-md font-medium flex items-center justify-center space-x-2 transition-all duration-200 text-sm" id="modalWishlistBtn" data-product-id="">
              <i data-lucide="heart" class="w-4 h-4" id="modal-heart-icon"></i>
              <span id="modal-wishlist-text">Wishlist</span>
            </button>
            <button class="btn btn-add-to-cart flex-1 flex items-center justify-center py-2 px-0" id="modalAddToCart" data-product-id="" style="cursor: pointer !important;">
              <i data-lucide="shopping-cart" class="lucide-small"></i>
              <span class="font-medium ml-2">Add to cart</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
