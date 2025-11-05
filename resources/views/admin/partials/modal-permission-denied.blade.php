<!-- Permission Denied Modal -->
<div x-data="{ open: @if(session('permission_denied')) true @else false @endif }" 
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-99999 flex items-center justify-center bg-black bg-opacity-50"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.away="open = false"
     @keydown.escape.window="open = false">
    <div class="relative bg-white dark:bg-boxdark rounded-2xl shadow-2xl max-w-md w-full mx-4"
         @click.stop
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-stroke dark:border-strokedark">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 dark:bg-red-900/30">
                    <i data-lucide="shield-x" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Access Denied</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Permission Required</p>
                </div>
            </div>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <p class="text-gray-700 dark:text-gray-300 mb-4">
                @if(session('permission_denied'))
                    {{ session('permission_denied')['message'] }}
                @else
                    You do not have permission to access this resource.
                @endif
            </p>
            @if(session('permission_denied') && isset(session('permission_denied')['permission']))
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 mb-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Required Permission:</p>
                <p class="text-sm font-mono text-gray-900 dark:text-white">{{ session('permission_denied')['permission'] }}</p>
            </div>
            @endif
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Please contact your administrator if you believe you should have access to this resource.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t border-stroke dark:border-strokedark">
            <button @click="open = false" 
                    class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors font-medium">
                Understood
            </button>
        </div>
    </div>
</div>

<script>
// Show modal if permission denied in session
document.addEventListener('DOMContentLoaded', function() {
    @if(session('permission_denied'))
        // Modal will auto-show via Alpine.js x-data
    @endif
    
    // Handle AJAX permission denied responses
    document.addEventListener('ajax:error', function(event) {
        if (event.detail && event.detail.status === 403) {
            const response = event.detail.responseJSON || {};
            if (response.message && response.message.includes('permission')) {
                showPermissionModal(response.message, response.permission);
            }
        }
    });
    
    // Intercept fetch requests for permission denied
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        return originalFetch.apply(this, args).then(response => {
            if (response.status === 403) {
                response.clone().json().then(data => {
                    if (data.message && data.message.includes('permission')) {
                        showPermissionModal(data.message, data.permission);
                    }
                }).catch(() => {});
            }
            return response;
        });
    };
});

function showPermissionModal(message, permission) {
    // Create or update modal
    let modal = document.getElementById('permission-denied-modal');
    if (!modal) {
        // Create modal dynamically if it doesn't exist
        modal = document.createElement('div');
        modal.id = 'permission-denied-modal';
        modal.innerHTML = `
            <div x-data="{ open: true }" 
                 x-show="open" 
                 x-cloak
                 class="fixed inset-0 z-99999 flex items-center justify-center bg-black bg-opacity-50"
                 @click.away="open = false"
                 @keydown.escape.window="open = false">
                <div class="relative bg-white dark:bg-boxdark rounded-2xl shadow-2xl max-w-md w-full mx-4"
                     @click.stop>
                    <div class="flex items-center justify-between p-6 border-b border-stroke dark:border-strokedark">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 dark:bg-red-900/30">
                                <i data-lucide="shield-x" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Access Denied</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Permission Required</p>
                            </div>
                        </div>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 dark:text-gray-300 mb-4" x-text="message"></p>
                        <div x-show="permission" class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 mb-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Required Permission:</p>
                            <p class="text-sm font-mono text-gray-900 dark:text-white" x-text="permission"></p>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Please contact your administrator if you believe you should have access to this resource.
                        </p>
                    </div>
                    <div class="flex items-center justify-end gap-3 p-6 border-t border-stroke dark:border-strokedark">
                        <button @click="open = false" 
                                class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors font-medium">
                            Understood
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        // Initialize Alpine.js on the new element
        if (typeof Alpine !== 'undefined') {
            Alpine.initTree(modal);
        }
    }
    
    // Update modal content
    const modalData = Alpine.$data(modal.querySelector('[x-data]'));
    if (modalData) {
        modalData.open = true;
        modalData.message = message;
        modalData.permission = permission;
    }
    
    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
    }
}
</script>
