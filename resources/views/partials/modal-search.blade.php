<!-- Search Modal -->
<div class="modal fade hidden" id="modal-search" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-stone-50 border-0 rounded-3xl shadow-2xl">
            <div class="modal-header flex items-center justify-between p-6 pb-2">
                <div>
                    <h5 class="modal-title text-base font-bold text-gray-900 mb-2" id="searchModalLabel">Search products</h5>
                </div>
                <button type="button" class="btn-close border-none text-gray-500 hover:text-gray-700 transition-colors" id="close-search-modal" aria-label="Close">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="modal-body p-6">
                <p class="text-sm text-gray-600 mb-5">Find solid wood furniture and accessories from David's Wood.</p>
                <!-- Search Input -->
                <div class="mb-4">
                    <input 
                        type="text" 
                        class="w-full border-2 border-green-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-600 bg-white text-gray-900 placeholder-gray-500 text-sm" 
                        id="search-input" 
                        name="search" 
                        placeholder="Search by name..."
                        autocomplete="off"
                    >
                </div>
                
                <!-- Search Results Container -->
                <div id="search-results" class="bg-white rounded-xl border border-gray-200 max-h-[300px] overflow-y-auto search-results">
                    <div id="search-placeholder" class="text-gray-500 text-sm">
                        Start typing to see results.
                    </div>
                    <div id="search-loading" class="p-6 text-center text-gray-500 hidden">
                        <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-green-600"></div>
                        <p class="mt-2">Searching...</p>
                    </div>
                    <div id="search-results-list" class="hidden">
                        <!-- Dynamic search results will be inserted here -->
                    </div>
                    <div id="search-no-results" class="p-6 text-center text-gray-500 hidden">
                        No products found matching your search.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
