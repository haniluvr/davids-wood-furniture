<!-- components/offcanvas-cart.html -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" id="offcanvas-cart">
  <div class="offcanvas bg-white fixed right-0 top-0 h-full w-full max-w-md shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col" id="offcanvas-cart-panel">
    <div class="offcanvas-header flex items-center justify-between px-6 py-5 flex-shrink-0">
      <h5 class="offcanvas-title text-base" id="offcanvasCartLabel">Your Cart</h5>
      <button type="button" class="btn-close" id="close-cart-offcanvas">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <div class="flex-1 overflow-y-auto offcanvas-body px-6" id="cart-body">
      <!-- Cart items will be loaded here -->
      <div id="cart-empty-state">
        <p class="empty-state-text">Your cart is empty.</p>
      </div>
      <div id="cart-items" style="display: none;">
        <!-- Cart items will be populated here -->
      </div>
    </div>
    <div class="offcanvas-footer hidden flex-shrink-0 px-6 py-4 border-t border-gray-200" id="cart-footer">
      <div class="subtotal">
        <span>Subtotal</span>
        <span id="cart-subtotal">₱0.00</span>
      </div>
      <div class="flex gap-3">
        <button type="button" class="select-all-btn" id="select-all-cart-items">
          Select All
        </button>
        <button class="checkout-btn" id="cart-checkout-btn" onclick="handleCartCheckout()">Checkout</button>
      </div>
      <div class="small-text">Taxes and shipping calculated at checkout.</div>
    </div>
    <script>
      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape' && window.hideoffcanvascart){ window.hideoffcanvascart(); }
      });
    </script>
  </div>
</div>
