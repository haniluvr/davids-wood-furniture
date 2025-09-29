<!-- Wishlist Offcanvas -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" id="offcanvas-wishlist">
  <div class="offcanvas bg-white fixed right-0 top-0 h-full w-full max-w-md shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col" id="offcanvas-wishlist-panel">
    <div class="offcanvas-header flex items-center justify-between px-6 py-5 flex-shrink-0">
      <h5 class="offcanvas-title text-base" id="offcanvasWishlistLabel">Your Favorites</h5>
      <button type="button" class="btn-close" id="close-wishlist-offcanvas">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <div class="flex-1 overflow-y-auto offcanvas-body px-6">
      <p class="empty-state-text">No favorites yet.</p>
    </div>
    <div class="offcanvas-footer border-t border-gray-200 px-6 py-4 flex-shrink-0">
      <button type="button" class="btn btn-outline-danger w-full" id="clear-wishlist-btn">
        <i data-lucide="trash-2" class="lucide-small mr-2"></i>
        Clear All Favorites
      </button>
    </div>
    <script>
      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape' && window.hideoffcanvaswishlist){ window.hideoffcanvaswishlist(); }
      });
    </script>
  </div>
</div>
