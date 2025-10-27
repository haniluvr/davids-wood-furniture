@extends('admin.layouts.app')

@section('title', 'Edit Product')


@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                    <i data-lucide="edit" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Edit Product</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update product information and settings</p>
                </div>
            </div>
            <a href="{{ admin_route('products.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Products
            </a>
        </div>
    </div>


    <form action="{{ admin_route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="product-edit-form">
        @csrf
        @method('PUT')


                <!-- Basic Information -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                        <i data-lucide="package" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Basic Information</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update the essential details about your product</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Product Name <span class="text-red-500">*</span>
                            </label>
                    <input type="text" 
                                name="name"
                           id="name"
                                value="{{ old('name', $product->name) }}"
                                required
                           placeholder="Enter product name"
                           class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('name') border-red-300 @enderror">
                            @error('name')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="category_id" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Category <span class="text-red-500">*</span>
                            </label>
                        <select name="category_id" 
                                id="category_id"
                                required
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('category_id') border-red-300 @enderror">
                            <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    <div class="space-y-2">
                        <label for="subcategory_id" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Subcategory <span class="text-red-500">*</span>
                            </label>
                        <select name="subcategory_id" 
                                id="subcategory_id"
                                required
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('subcategory_id') border-red-300 @enderror">
                            <option value="">Select a subcategory</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subcategory_id')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="barcode" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Barcode</label>
                        <input type="text" 
                               name="barcode" 
                               id="barcode"
                               value="{{ old('barcode', $product->barcode) }}"
                               placeholder="Enter barcode"
                               class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('barcode') border-red-300 @enderror">
                        @error('barcode')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">Current SKU</label>
                        <div class="w-full rounded-xl border border-stone-200 bg-stone-50 dark:bg-gray-800 px-4 py-3 text-sm text-stone-500 dark:text-gray-400">
                            <span>{{ $product->sku }}</span>
                        </div>
                        <input type="hidden" name="sku" value="{{ $product->sku }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="short_description" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Short Description</label>
                    <textarea name="short_description" 
                              id="short_description"
                            rows="3"
                              placeholder="Brief description of the product"
                              class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('short_description') border-red-300 @enderror">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                <div class="space-y-2">
                    <label for="description" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Description <span class="text-red-500">*</span>
                        </label>
                    <textarea name="description" 
                              id="description"
                              rows="5"
                            required
                              placeholder="Detailed description of the product"
                              class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('description') border-red-300 @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                </div>
                    </div>
                </div>

        <!-- Pricing & Category -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-green-50 to-blue-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                        <i data-lucide="dollar-sign" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Pricing</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update your product pricing</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="price" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Regular Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-stone-500 text-lg font-medium">₱</span>
                            </div>
                            <input type="number" 
                                    name="price"
                                   id="price"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('price', $product->price) }}"
                                    required
                                   placeholder="0.00"
                                   class="pl-10 w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('price') border-red-300 @enderror">
                            </div>
                            @error('price')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    <div class="space-y-2">
                        <label for="cost_price" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Cost Price</label>
                            <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-stone-500 text-lg font-medium">₱</span>
                            </div>
                            <input type="number" 
                                    name="cost_price"
                                   id="cost_price"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('cost_price', $product->cost_price) }}"
                                   placeholder="0.00"
                                   class="pl-10 w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('cost_price') border-red-300 @enderror">
                            </div>
                            @error('cost_price')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    <div class="space-y-2">
                        <label for="sale_price" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Sale Price</label>
                            <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-stone-500 text-lg font-medium">₱</span>
                            </div>
                            <input type="number" 
                                    name="sale_price"
                                   id="sale_price"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('sale_price', $product->sale_price) }}"
                                   placeholder="0.00"
                                   class="pl-10 w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('sale_price') border-red-300 @enderror">
                            </div>
                            @error('sale_price')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                    </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl">
                        <i data-lucide="package-2" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Inventory Management</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Configure stock tracking and inventory settings</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="stock_quantity" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Stock Quantity <span class="text-red-500">*</span>
                            </label>
                        <input type="number" 
                                name="stock_quantity"
                               id="stock_quantity"
                                min="0"
                                value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                required
                               placeholder="0"
                               class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('stock_quantity') border-red-300 @enderror">
                            @error('stock_quantity')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    <div class="space-y-2">
                        <label for="low_stock_threshold" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Low Stock Threshold <span class="text-red-500">*</span>
                            </label>
                        <input type="number" 
                                name="low_stock_threshold"
                               id="low_stock_threshold"
                                min="0"
                                value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}"
                                required
                               placeholder="10"
                               class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('low_stock_threshold') border-red-300 @enderror">
                            @error('low_stock_threshold')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                <div class="flex items-center p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                    <input type="checkbox" 
                                name="manage_stock"
                           id="manage_stock"
                                value="1"
                                {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}
                           class="h-5 w-5 text-primary focus:ring-primary border-stone-300 rounded">
                    <label for="manage_stock" class="ml-3 block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Track stock quantity for this product
                        </label>
                    </div>
                </div>
                        </div>

        <!-- Product Images -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                        <i data-lucide="image" class="w-5 h-5 text-white"></i>
                        </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Product Images</h3>
                        </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update product images</p>
                    </div>
            <div class="p-8 space-y-6">
                    <!-- Current Images -->
                    @if($product->images && count($product->images) > 0)
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-stone-700 dark:text-stone-300">Current Images</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="current-images">
                                @foreach($product->images as $index => $image)
                                    <div class="image-preview-item">
                                        <div class="aspect-square rounded-xl overflow-hidden bg-stone-100 dark:bg-gray-800 border border-stone-200 dark:border-strokedark">
                                            @if(Storage::dynamic()->exists($image))
                                                @php $imageUrl = Storage::getDynamicUrl($image); @endphp
                                                <img src="{{ $imageUrl }}?v={{ time() }}" alt="Product Image" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-stone-200 dark:bg-gray-700">
                                                    <div class="text-center">
                                                        <i data-lucide="image" class="w-8 h-8 text-stone-400 dark:text-gray-500 mx-auto mb-2"></i>
                                                        <p class="text-xs text-stone-500 dark:text-gray-400">Image not found</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="delete-overlay absolute inset-0 bg-black bg-opacity-50 rounded-xl flex items-center justify-center">
                                            <button type="button" class="delete-button" onclick="removeCurrentImage({{ $index }})" title="Click to mark/unmark for removal" id="delete-btn-{{ $index }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="delete-icon-{{ $index }}">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                        </button>
                                        </div>
                                        <p class="mt-2 text-xs text-stone-500 dark:text-gray-400 truncate">
                                            @if(Storage::dynamic()->exists($image))
                                                Current Image {{ $index + 1 }}
                                            @else
                                                Missing Image {{ $index + 1 }}
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                <!-- Upload New Images -->
                <div class="space-y-2">
                    <label for="images" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Add New Images</label>
                    <label for="images" id="image-upload-area" class="mt-2 block relative flex justify-center px-6 pt-8 pb-8 border-2 border-dashed border-stone-300 dark:border-strokedark rounded-2xl hover:border-primary transition-colors duration-200 cursor-pointer group">
                        <input id="images" name="images[]" type="file" class="hidden" multiple accept="image/*">
                        <div class="space-y-3 text-center">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900 dark:to-pink-900 rounded-2xl group-hover:scale-110 transition-transform duration-200">
                                <i data-lucide="upload" class="w-8 h-8 text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div class="text-sm text-stone-600 dark:text-gray-400">
                                <span class="font-medium text-primary">Click to upload</span>
                                <span class="mx-2">or</span>
                                <span class="font-medium">drag and drop</span>
                            </div>
                            <p class="text-xs text-stone-500 dark:text-gray-500">PNG, JPG, GIF up to 2MB each • Multiple files supported</p>
                        </div>
                    </label>
                    
                    <!-- Image Preview Area -->
                    <div id="image-preview-container" class="mt-4 hidden">
                        <div class="mb-2">
                            <h4 class="text-sm font-medium text-stone-700 dark:text-stone-300">New Images:</h4>
                        </div>
                        <div id="image-preview-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <!-- Preview images will be inserted here -->
                        </div>
                    </div>

                    @error('images.*')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
                </div>

        <!-- Settings -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl">
                        <i data-lucide="settings" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Product Settings</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Configure product visibility and features</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="flex items-center p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                        <input type="checkbox" 
                                name="is_active"
                               id="is_active"
                                value="1"
                                {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="h-5 w-5 text-primary focus:ring-primary border-stone-300 rounded">
                        <label for="is_active" class="ml-3 block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Active (visible to customers)
                        </label>
                    </div>

                    <div class="flex items-center p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                        <input type="checkbox" 
                                name="featured"
                               id="featured"
                                value="1"
                                {{ old('featured', $product->featured) ? 'checked' : '' }}
                               class="h-5 w-5 text-primary focus:ring-primary border-stone-300 rounded">
                        <label for="featured" class="ml-3 block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Featured product
                        </label>
                    </div>
                </div>
                    </div>
                </div>

                <!-- Actions -->
        <div class="flex justify-end space-x-4 pt-6">
            <a href="{{ admin_route('products.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 border border-stone-200 bg-white text-sm font-medium text-stone-700 rounded-xl transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                <i data-lucide="save" class="w-4 h-4"></i>
                            Update Product
                        </button>
        </div>
    </form>
</div>

<style>
.image-preview-item {
    position: relative;
}

.image-preview-item .delete-overlay {
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

.image-preview-item:hover .delete-overlay {
    opacity: 1;
}

.image-preview-item .delete-button {
    background-color: #ef4444;
    color: white;
    border-radius: 50%;
    padding: 8px;
    transition: background-color 0.2s ease-in-out;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.image-preview-item .delete-button:hover {
    background-color: #dc2626;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('image-upload-area');
    const fileInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview-container');
    
    // SKU Generation and Subcategory Loading
    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');
    
    // Subcategories are pre-loaded, no need to load them on page load
    // The subcategory should already be selected from the server-side rendering
    
    // Category change handler
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        
        // Clear subcategory options
        subcategorySelect.innerHTML = '<option value="">Select a subcategory</option>';
        
        if (categoryId) {
            // Load subcategories for selected category
            loadSubcategories(categoryId);
        }
    });
    
    // Load subcategories function
    function loadSubcategories(categoryId) {
        fetch(`{{ admin_route('categories.subcategories', ['category' => ':category']) }}`.replace(':category', categoryId))
            .then(response => response.json())
            .then(data => {
                subcategorySelect.innerHTML = '<option value="">Select a subcategory</option>';
                data.forEach(subcategory => {
                    const option = document.createElement('option');
                    option.value = subcategory.id;
                    option.textContent = subcategory.name;
                    subcategorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading subcategories:', error);
                // Fallback: show a message to add subcategories
                subcategorySelect.innerHTML = '<option value="">No subcategories available</option>';
            });
    }
    
    // File input change
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });
    
    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-primary', 'bg-primary/5');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-primary', 'bg-primary/5');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-primary', 'bg-primary/5');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
        handleFiles(files);
        }
    });

    function handleFiles(files) {
        const fileArray = Array.from(files);
        const imageFiles = fileArray.filter(file => file.type.startsWith('image/'));
        
        if (imageFiles.length === 0) {
            alert('Please select only image files.');
            return;
        }
        
        // Clear previous previews
        const previewGrid = document.getElementById('image-preview-grid');
        previewGrid.innerHTML = '';
        previewContainer.classList.remove('hidden');
        
        // Show loading state
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'col-span-full text-center py-4';
        loadingDiv.innerHTML = `
            <div class="inline-flex items-center gap-2 text-stone-600 dark:text-gray-400">
                <div class="animate-spin rounded-full h-4 w-4 border-2 border-primary border-t-transparent"></div>
                <span class="text-sm">Processing ${imageFiles.length} image(s)...</span>
            </div>
        `;
        previewGrid.appendChild(loadingDiv);
        
        let processedCount = 0;
        
        imageFiles.forEach((file, index) => {
            if (file.size > 2 * 1024 * 1024) { // 2MB limit
                alert(`File "${file.name}" is too large. Please select files under 2MB.`);
                return;
            }
            
                const reader = new FileReader();
            reader.onload = function(e) {
                processedCount++;
                
                // Remove loading state when first image is processed
                if (processedCount === 1) {
                    loadingDiv.remove();
                }
                
                const previewDiv = document.createElement('div');
                previewDiv.className = 'image-preview-item';
                previewDiv.innerHTML = `
                    <div class="aspect-square rounded-xl overflow-hidden bg-stone-100 dark:bg-gray-800 border border-stone-200 dark:border-strokedark">
                        <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-full object-cover">
                    </div>
                    <div class="delete-overlay absolute inset-0 bg-black bg-opacity-50 rounded-xl flex items-center justify-center">
                        <button type="button" class="delete-button" onclick="removeImagePreview(this)" title="Remove image">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-2 space-y-1">
                        <p class="text-xs text-stone-500 dark:text-gray-400 truncate font-medium">${file.name}</p>
                        <p class="text-xs text-green-600 dark:text-green-400">✓ Ready to upload</p>
                    </div>
                    `;
                previewGrid.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
        });
    }
    
    // Make removeImagePreview globally available
    window.removeImagePreview = function(button) {
        const previewDiv = button.closest('.image-preview-item');
        previewDiv.remove();
        
        // Hide preview container if no images left
        const remainingImages = document.getElementById('image-preview-grid').querySelectorAll('.image-preview-item');
        if (remainingImages.length === 0) {
            previewContainer.classList.add('hidden');
        }
    };
    
    // Make removeCurrentImage globally available
    window.removeCurrentImage = function(index) {
        const currentImages = document.getElementById('current-images');
        const imageElement = currentImages.children[index];
        const form = document.querySelector('form');
        
        if (imageElement) {
            const isMarkedForRemoval = imageElement.getAttribute('data-marked-for-removal') === 'true';
            
            if (isMarkedForRemoval) {
                // Unmark for removal - restore normal appearance
                imageElement.style.opacity = '1';
                imageElement.style.border = '';
                imageElement.removeAttribute('data-marked-for-removal');
                
                // Change icon back to trash
                const deleteIcon = document.getElementById(`delete-icon-${index}`);
                if (deleteIcon) {
                    deleteIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>';
                }
                
                // Remove the hidden input
                const existingInput = form.querySelector(`input[data-image-index="${index}"]`);
                if (existingInput) {
                    existingInput.remove();
                }
            } else {
                // Mark for removal - add visual indicator
                imageElement.style.opacity = '0.5';
                imageElement.style.border = '2px solid red';
                imageElement.setAttribute('data-marked-for-removal', 'true');
                
                // Change icon to checkmark
                const deleteIcon = document.getElementById(`delete-icon-${index}`);
                if (deleteIcon) {
                    deleteIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
                }
                
        // Add hidden input to mark image for removal
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'remove_images[]';
        input.value = index;
                input.setAttribute('data-image-index', index);
        form.appendChild(input);
            }
        }
    };
    
    // Function to ensure all marked images are added to form
    function ensureRemoveImagesInputs() {
        const form = document.querySelector('form');
        const currentImages = document.getElementById('current-images');
        const markedImages = currentImages.querySelectorAll('[data-marked-for-removal="true"]');
        
        // Remove existing remove_images inputs to avoid duplicates
        const existingInputs = form.querySelectorAll('input[name="remove_images[]"]');
        existingInputs.forEach(input => input.remove());
        
        // Add inputs for all marked images
        markedImages.forEach((imageElement, index) => {
            const imageIndex = Array.from(currentImages.children).indexOf(imageElement);
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_images[]';
            input.value = imageIndex;
            input.setAttribute('data-image-index', imageIndex);
            form.appendChild(input);
        });
        
    }
    
    // Ensure all marked images are added to form before submission
    document.getElementById('product-edit-form').addEventListener('submit', function(e) {
        // Ensure all marked images are added to form before submission
        ensureRemoveImagesInputs();
    });


});
</script>
@endsection
