<!-- components/modal-quick-view.html -->
 <!-- make preview a carousel-->
<div class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" id="modalQuickView">
  <div class="modal-content bg-white rounded-2xl shadow-lg max-w-4xl w-full mx-4" style="background-color: #f8f9fa;">
    <div class="flex items-center justify-between p-6">
      <h5 class="text-xl font-semibold" id="quickViewLabel">Product Name</h5>
      <button type="button" class="text-gray-400 hover:text-gray-600" id="close-modalQuickView-modal">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="w-full">
          <img src="" alt="Product Image" class="w-full rounded-2xl" id="quick-view-image">
          <p class="text-gray-500 text-sm mt-2">preview 1</p>
        </div>
        <div class="w-full">
          <div class="flex justify-between items-center mb-3">
            <p class="mb-0 text-gray-500" id="quick-view-desc"></p>
            <div class="flex items-center">
              <span class="rating mr-2">★</span>
              <span class="mr-2" id="quick-view-rating">4.5</span>
              <span class="font-bold" id="quick-view-price">₱1,199.99</span>
            </div>
          </div>

          <div class="border-t border-gray-200 pt-3 mb-3">
            <div class="flex items-center mb-2">
              <i data-lucide="box" class="mr-1"></i>
              <strong class="mr-1">Material:</strong>
              <span id="quick-view-material">Solid Wood</span>
            </div>
            <div class="flex items-center">
              <i data-lucide="proportions" class="mr-1"></i>
              <strong class="mr-1">Dimensions:</strong>
              <span id="quick-view-dimensions">40cm W × 40cm D × 40cm H</span>
            </div>
          </div>

          <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
            <div class="flex">
              <button class="px-3 py-2 border border-gray-300 rounded-l-lg hover:bg-gray-50" type="button" id="decrease-qty">−</button>
              <input type="number" class="w-16 px-3 py-2 text-center border-t border-b border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500" value="1" min="1" id="quantity-input">
              <button class="px-3 py-2 border border-gray-300 rounded-r-lg hover:bg-gray-50" type="button" id="increase-qty">+</button>
            </div>
          </div>

          <div class="flex gap-2">
            <button class="bg-black text-white px-6 py-3 rounded-lg flex-1 flex items-center justify-center" id="modalAddToCart" data-product-id="" style="cursor: pointer !important;">
              <i data-lucide="shopping-cart" class="mr-1"></i> Add to cart
            </button>
            <button class="bg-gray-100 text-gray-800 px-6 py-3 rounded-lg flex-1" onclick="if(window.hidemodalQuickView){window.hidemodalQuickView()}">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
