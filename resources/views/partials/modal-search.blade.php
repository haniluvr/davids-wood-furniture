<!-- Search Modal -->
<div class="modal fade hidden" id="modal-search" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header flex items-center justify-between">
                <h5 class="modal-title" id="searchModalLabel">Search Products</h5>
                <button type="button" class="btn-close border-none" id="close-search-modal" aria-label="Close">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="search-form">
                    <div class="mb-4">
                        <label for="search-input" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input 
                            type="text" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            id="search-input" 
                            name="search" 
                            placeholder="Search for products..."
                            required
                        >
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="mb-4">
                        <button 
                            type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200"
                        >
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
