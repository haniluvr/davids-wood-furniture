<!-- Custom Alert Modal -->
<div id="custom-alert-modal" 
     class="fixed top-4 z-50 transition-all duration-300 opacity-0 hidden" 
     style="left: 50%; transform: translateX(-50%);">
    <div class="flex items-center border border-yellow-400 rounded-lg gap-3 px-4 py-3 shadow-lg" style="background-color: #fff3cd;">
        <!-- Warning Icon -->
        <div class="flex-shrink-0">
            <svg class="w-5 h-5" style="color: #664d03;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <!-- Alert Message -->
        <div class="flex-1">
            <p class="text-yellow-400" id="alert-modal-message" style="color: #664d03;">Welcome! Please remember to add a password in your account settings for added security.</p>
        </div>
        <!-- Close Button -->
        <div class="flex-shrink-0">
            <button id="alert-modal-ok-btn" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" style="color: #664d03;">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
