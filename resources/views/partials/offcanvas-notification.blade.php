<!-- Notification Offcanvas -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" id="offcanvas-notification">
  <div class="offcanvas bg-white fixed right-0 top-0 h-full w-full max-w-md shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col" id="offcanvas-notification-panel">
    <div class="offcanvas-header flex items-center justify-between px-6 py-5 flex-shrink-0">
      <h5 class="offcanvas-title text-base" id="offcanvasNotificationLabel">Notifications</h5>
      <button type="button" class="btn-close" id="close-notification-offcanvas">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <div class="flex-1 overflow-y-auto offcanvas-body px-6" id="notification-body">
      <!-- Empty state -->
      <div id="notification-empty-state">
        <div class="text-center py-8">
          <i data-lucide="bell-off" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
          <p class="text-gray-500 text-sm">No notifications yet.</p>
        </div>
      </div>
      
      <!-- Notification list -->
      <div id="notification-list" style="display: none;">
        <!-- Notifications will be populated here -->
      </div>
    </div>
    <div class="offcanvas-footer border-t border-gray-200 px-6 py-4 flex-shrink-0" id="notification-footer" style="display: none;">
      <div class="flex space-x-2">
        <button type="button" class="btn btn-outline-primary flex-1" id="mark-all-read-btn">
          <i data-lucide="check" class="lucide-small mr-2"></i>
          Mark All as Read
        </button>
        <button type="button" class="btn btn-outline-danger flex-1" id="clear-all-notifications-btn">
          <i data-lucide="trash-2" class="lucide-small mr-2"></i>
          Clear All
        </button>
      </div>
    </div>
    <script>
      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape' && window.hideoffcanvasnotification){ window.hideoffcanvasnotification(); }
      });
    </script>
  </div>
</div>
