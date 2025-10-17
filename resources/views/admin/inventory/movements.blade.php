@extends('admin.layouts.app')

@section('title', 'Inventory Movements')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            Inventory Movements
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('admin.dashboard') }}" class="font-medium">Dashboard</a></li>
                <li class="font-medium text-primary">/</li>
                <li><a href="{{ route('admin.inventory.index') }}" class="font-medium">Inventory</a></li>
                <li class="font-medium text-primary">/</li>
                <li class="font-medium text-primary">Movements</li>
            </ol>
        </nav>
    </div>

    <!-- Stats Cards -->
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Movements</p>
                    <p class="text-2xl font-bold text-black dark:text-white">1,247</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                    <i data-lucide="activity" class="h-6 w-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Stock In (30 days)</p>
                    <p class="text-2xl font-bold text-green-600">+342</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                    <i data-lucide="trending-up" class="h-6 w-6 text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Stock Out (30 days)</p>
                    <p class="text-2xl font-bold text-red-600">-298</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                    <i data-lucide="trending-down" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Net Change</p>
                    <p class="text-2xl font-bold text-blue-600">+44</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                    <i data-lucide="bar-chart-3" class="h-6 w-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Filters</h3>
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Product</label>
                <select name="product_id" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    <option value="">All Products</option>
                    @foreach(\App\Models\Product::orderBy('name')->get() as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Movement Type</label>
                <select name="type" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
                    <option value="">All Types</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full rounded-lg bg-primary px-4 py-2 text-white hover:bg-opacity-90">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Movements Table -->
    <div class="rounded-xl border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex items-center justify-between border-b border-stroke p-6 dark:border-strokedark">
            <h3 class="text-lg font-semibold text-black dark:text-white">Movement History</h3>
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center gap-2 rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Export
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-stroke dark:border-strokedark">
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Date</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Product</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Type</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Quantity</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Reason</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">User</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Reference</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample data - replace with actual data from controller -->
                    <tr class="border-b border-stroke dark:border-strokedark hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ now()->format('M d, Y H:i') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded bg-gray-100 dark:bg-gray-800"></div>
                                <div>
                                    <p class="font-medium text-black dark:text-white">Oak Dining Table</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: OAK-DT-001</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                Stock In
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm font-medium text-green-600">+5</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Supplier Delivery</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Admin User</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">PO-12345</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">-</td>
                    </tr>

                    <tr class="border-b border-stroke dark:border-strokedark hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ now()->subHours(2)->format('M d, Y H:i') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded bg-gray-100 dark:bg-gray-800"></div>
                                <div>
                                    <p class="font-medium text-black dark:text-white">Cherry Bookshelf</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: CHR-BS-002</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                Stock Out
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm font-medium text-red-600">-2</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Sale</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">System</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">ORD-67890</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">-</td>
                    </tr>

                    <tr class="border-b border-stroke dark:border-strokedark hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ now()->subDays(1)->format('M d, Y H:i') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded bg-gray-100 dark:bg-gray-800"></div>
                                <div>
                                    <p class="font-medium text-black dark:text-white">Walnut Coffee Table</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: WAL-CT-003</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                Adjustment
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm font-medium text-blue-600">+3</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Inventory Count</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Admin User</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">-</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Found additional inventory</td>
                    </tr>

                    <tr class="border-b border-stroke dark:border-strokedark hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ now()->subDays(2)->format('M d, Y H:i') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded bg-gray-100 dark:bg-gray-800"></div>
                                <div>
                                    <p class="font-medium text-black dark:text-white">Pine Nightstand</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: PIN-NS-004</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                Stock Out
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm font-medium text-red-600">-1</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Damaged Goods</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Admin User</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">-</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Water damage during storage</td>
                    </tr>

                    <tr class="border-b border-stroke dark:border-strokedark hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ now()->subDays(3)->format('M d, Y H:i') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded bg-gray-100 dark:bg-gray-800"></div>
                                <div>
                                    <p class="font-medium text-black dark:text-white">Mahogany Dresser</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: MAH-DR-005</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                Stock In
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm font-medium text-green-600">+2</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Production Completion</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">System</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">PROD-11111</td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between border-t border-stroke p-6 dark:border-strokedark">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">1,247</span> results
            </div>
            <div class="flex items-center gap-2">
                <button class="rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800" disabled>
                    Previous
                </button>
                <button class="rounded-lg bg-primary px-3 py-2 text-sm text-white">1</button>
                <button class="rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">2</button>
                <button class="rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">3</button>
                <span class="px-2 text-sm text-gray-500">...</span>
                <button class="rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">125</button>
                <button class="rounded-lg border border-stroke px-3 py-2 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
