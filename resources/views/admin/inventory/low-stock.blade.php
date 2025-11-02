@extends('admin.layouts.app')

@section('title', 'Low Stock Alerts')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-stone-200">
        <div class="mx-auto max-w-screen-2xl">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-stone-900">Low Stock Alerts</h1>
                    <p class="mt-1 text-sm text-stone-600">Monitor products that are running low on inventory</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="inline-flex items-center px-4 py-2 border border-stone-300 rounded-lg text-sm font-medium text-stone-700 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export
                    </button>
                    <button class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Bulk Reorder
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-screen-2xl pt-6">

    <!-- Alert Summary -->
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Critical Stock</p>
                    <p class="text-2xl font-bold text-red-600">12</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                    <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Low Stock</p>
                    <p class="text-2xl font-bold text-yellow-600">28</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <i data-lucide="alert-circle" class="h-6 w-6 text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Out of Stock</p>
                    <p class="text-2xl font-bold text-red-600">3</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                    <i data-lucide="x-circle" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Value at Risk</p>
                    <p class="text-2xl font-bold text-black dark:text-white">₱24,580</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                    <i data-lucide="dollar-sign" class="h-6 w-6 text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Filters</h3>
        <form method="GET" class="flex flex-wrap items-end gap-4 justify-between">
            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Alert Level</label>
                <select name="level" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    <option value="">All Levels</option>
                    <option value="critical" {{ request('level') == 'critical' ? 'selected' : '' }}>Critical (0-5 units)</option>
                    <option value="low" {{ request('level') == 'low' ? 'selected' : '' }}>Low (6-20 units)</option>
                    <option value="out" {{ request('level') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <select name="category_id" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    <option value="">All Categories</option>
                    @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort By</label>
                <select name="sort" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stock (Low to High)</option>
                    <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stock (High to Low)</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A to Z)</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z to A)</option>
                    <option value="value_desc" {{ request('sort') == 'value_desc' ? 'selected' : '' }}>Value (High to Low)</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-opacity-90 whitespace-nowrap">
                    Apply Filters
                </button>
                <a href="{{ admin_route('inventory.low-stock') }}" class="inline-flex items-center justify-center rounded-lg border border-stroke px-4 py-2.5 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Low Stock Products -->
    <div class="rounded-xl border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center justify-between border-b border-stroke p-6 dark:border-strokedark">
            <h3 class="text-lg font-semibold text-black dark:text-white">Low Stock Products</h3>
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center gap-2 rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Export
                </button>
                <button class="inline-flex items-center gap-2 rounded-lg bg-primary px-3 py-2 text-sm text-white hover:bg-opacity-90">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Bulk Reorder
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-stroke dark:border-strokedark">
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">
                            <input type="checkbox" class="rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input">
                        </th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Product</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Current Stock</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Threshold</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Alert Level</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Days Until Stockout</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Suggested Reorder</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Critical Stock Items -->
                    <tr class="border-b border-stroke dark:border-strokedark hover:bg-red-50 dark:hover:bg-red-900/10">
                        <td class="py-3 px-4">
                            <input type="checkbox" class="rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input">
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded bg-gray-100 dark:bg-gray-800"></div>
                                <div>
                                    <p class="font-medium text-black dark:text-white">Oak Dining Table</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: OAK-DT-001</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-lg font-bold text-red-600">2</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">10</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                Critical
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-red-600 font-medium">3 days</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">20 units</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="#" class="text-primary hover:text-primary/80">
                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                </a>
                                <a href="#" class="text-green-600 hover:text-green-700">
                                    <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-stroke dark:border-strokedark hover:bg-red-50 dark:hover:bg-red-900/10">
                        <td class="py-3 px-4">
                            <input type="checkbox" class="rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input">
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded bg-gray-100 dark:bg-gray-800"></div>
                                <div>
                                    <p class="font-medium text-black dark:text-white">Cherry Bookshelf</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: CHR-BS-002</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-lg font-bold text-red-600">0</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">5</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                Out of Stock
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-red-600 font-medium">0 days</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">15 units</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="#" class="text-primary hover:text-primary/80">
                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                </a>
                                <a href="#" class="text-green-600 hover:text-green-700">
                                    <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Low Stock Items -->
                    <tr class="border-b border-stroke dark:border-strokedark hover:bg-yellow-50 dark:hover:bg-yellow-900/10">
                        <td class="py-3 px-4">
                            <input type="checkbox" class="rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input">
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded bg-gray-100 dark:bg-gray-800"></div>
                                <div>
                                    <p class="font-medium text-black dark:text-white">Walnut Coffee Table</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: WAL-CT-003</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-lg font-bold text-yellow-600">8</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">15</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                Low Stock
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-yellow-600 font-medium">12 days</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">25 units</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="#" class="text-primary hover:text-primary/80">
                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                </a>
                                <a href="#" class="text-green-600 hover:text-green-700">
                                    <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-stroke dark:border-strokedark hover:bg-yellow-50 dark:hover:bg-yellow-900/10">
                        <td class="py-3 px-4">
                            <input type="checkbox" class="rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input">
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded bg-gray-100 dark:bg-gray-800"></div>
                                <div>
                                    <p class="font-medium text-black dark:text-white">Pine Nightstand</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: PIN-NS-004</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-lg font-bold text-yellow-600">12</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">20</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                Low Stock
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-yellow-600 font-medium">18 days</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">30 units</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="#" class="text-primary hover:text-primary/80">
                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                </a>
                                <a href="#" class="text-green-600 hover:text-green-700">
                                    <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-stroke dark:border-strokedark hover:bg-yellow-50 dark:hover:bg-yellow-900/10">
                        <td class="py-3 px-4">
                            <input type="checkbox" class="rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input">
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded bg-gray-100 dark:bg-gray-800"></div>
                                <div>
                                    <p class="font-medium text-black dark:text-white">Mahogany Dresser</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: MAH-DR-005</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-lg font-bold text-yellow-600">15</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">25</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                Low Stock
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-yellow-600 font-medium">22 days</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">35 units</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="#" class="text-primary hover:text-primary/80">
                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                </a>
                                <a href="#" class="text-green-600 hover:text-green-700">
                                    <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between border-t border-stroke p-6 dark:border-strokedark">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">43</span> results
            </div>
            <div class="flex items-center gap-2">
                <button class="rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800" disabled>
                    Previous
                </button>
                <button class="rounded-lg bg-primary px-3 py-2 text-sm text-white">1</button>
                <button class="rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">2</button>
                <button class="rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">3</button>
                <span class="px-2 text-sm text-gray-500">...</span>
                <button class="rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">9</button>
                <button class="rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">
                    Next
                </button>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
