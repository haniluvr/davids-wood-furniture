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
                                <option value="{{ $subcategory->id }}" {{ (old('subcategory_id', $selectedSubcategoryId ?? $product->subcategory_id) == $subcategory->id) ? 'selected' : '' }}>
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

        <!-- Product Details -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-indigo-50 to-cyan-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-cyan-600 rounded-xl">
                        <i data-lucide="ruler" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Product Details</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Specify physical characteristics of the product</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="weight" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Weight (kg)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="weight" 
                                   id="weight"
                                   step="0.01"
                                   min="0"
                                   value="{{ old('weight', $product->weight) }}"
                                   placeholder="0.00"
                                   class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('weight') border-red-300 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-stone-500 text-sm">kg</span>
                            </div>
                        </div>
                        @error('weight')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="dimensions" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Dimensions
                        </label>
                        <input type="text" 
                               name="dimensions" 
                               id="dimensions"
                               value="{{ old('dimensions', $product->dimensions) }}"
                               placeholder="e.g., 100cm x 50cm x 30cm"
                               class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('dimensions') border-red-300 @enderror">
                        @error('dimensions')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="material" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Material
                        </label>
                        <input type="text" 
                               name="material" 
                               id="material"
                               value="{{ old('material', $product->material) }}"
                               placeholder="e.g., Oak Wood, Pine, MDF"
                               class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('material') border-red-300 @enderror">
                        @error('material')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Room Categories (Tagging Style) -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Room Categories
                    </label>
                    <p class="text-xs text-stone-500 dark:text-gray-400 mb-3">Select one or more rooms where this product can be used</p>
                    @php
                        $roomOptions = [
                            'bedroom' => ['name' => 'Bedroom', 'icon' => 'bed-double'],
                            'living-room' => ['name' => 'Living Room', 'icon' => 'sofa'],
                            'dining-room' => ['name' => 'Dining Room', 'icon' => 'utensils'],
                            'bathroom' => ['name' => 'Bathroom', 'icon' => 'bath'],
                            'office' => ['name' => 'Office', 'icon' => 'lamp-desk'],
                            'garden-and-balcony' => ['name' => 'Garden & Balcony', 'icon' => 'flower'],
                        ];
                        $selectedRooms = old('room_category', $product->room_category ?? []);
                        if (!is_array($selectedRooms)) {
                            $selectedRooms = [];
                        }
                    @endphp
                    <div class="flex flex-wrap gap-2" id="room-tags-container">
                        @foreach($roomOptions as $roomSlug => $roomData)
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="room_category[]" 
                                       value="{{ $roomSlug }}"
                                       {{ in_array($roomSlug, $selectedRooms) ? 'checked' : '' }}
                                       class="sr-only room-checkbox">
                                <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-all duration-200 
                                    {{ in_array($roomSlug, $selectedRooms) 
                                        ? 'bg-primary text-white shadow-md' 
                                        : 'bg-stone-100 text-stone-700 hover:bg-stone-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                                    <i data-lucide="{{ $roomData['icon'] }}" class="w-4 h-4 mr-2"></i>
                                    {{ $roomData['name'] }}
                                    @if(in_array($roomSlug, $selectedRooms))
                                        <i data-lucide="check" class="w-4 h-4 ml-2"></i>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('room_category')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @error('room_category.*')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
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
                        <h4 class="text-sm font-medium text-stone-700 dark:text-stone-300">Current Images <span class="text-xs text-stone-500 dark:text-gray-400 font-normal">(Drag to reorder)</span></h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="current-images">
                                @foreach($product->images as $index => $image)
                                    <div class="image-preview-item cursor-move" data-image-index="{{ $index }}" data-image-path="{{ $image }}">
                                        <div class="relative">
                                            <div class="absolute top-2 left-2 z-10 bg-black bg-opacity-50 rounded-full p-1">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                </svg>
                                            </div>
                                        <div class="aspect-square rounded-xl overflow-hidden bg-stone-100 dark:bg-gray-800 border border-stone-200 dark:border-strokedark">
                                            @php $imageUrl = Storage::url($image); @endphp
                                            <img src="{{ $imageUrl }}?v={{ time() }}" alt="Product Image" class="w-full h-full object-cover">
                                        </div>
                                        <div class="delete-overlay absolute inset-0 bg-black bg-opacity-50 rounded-xl flex items-center justify-center">
                                            <button type="button" class="delete-button" onclick="removeCurrentImage({{ $index }})" title="Click to mark/unmark for removal" id="delete-btn-{{ $index }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="delete-icon-{{ $index }}">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                        </button>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-xs text-stone-500 dark:text-gray-400 truncate">Current Image {{ $index + 1 }}</p>
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
                            <p class="text-xs text-stone-500 dark:text-gray-500">PNG, JPG, GIF, WebP, AVIF up to 2MB each • Multiple files supported</p>
                        </div>
                    </label>
                    
                    <!-- Image Preview Area -->
                    <div id="image-preview-container" class="mt-4 hidden">
                        <div class="mb-2">
                            <h4 class="text-sm font-medium text-stone-700 dark:text-stone-300">New Images: <span class="text-xs text-stone-500 dark:text-gray-400 font-normal">(Drag to reorder)</span></h4>
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

/* Sortable drag styles */
.sortable-ghost {
    opacity: 0.4;
}

.sortable-drag {
    opacity: 0.8;
    cursor: grabbing !important;
}

.image-preview-item.sortable-drag {
    transform: rotate(5deg);
}
</style>

<!-- SortableJS Library for Drag and Drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure subcategory is preselected based on SKU if empty
    try {
        const subcatSelect = document.getElementById('subcategory_id');
        if (subcatSelect && !subcatSelect.value) {
            const skuHidden = document.querySelector('input[name="sku"]');
            const sku = skuHidden ? (skuHidden.value || '').replace(/[^0-9]/g, '') : '';
            if (sku.length >= 3) {
                const derivedSubId = parseInt(sku.substring(1, 3), 10).toString();
                const option = Array.from(subcatSelect.options).find(o => o.value === derivedSubId);
                if (option) {
                    subcatSelect.value = derivedSubId;
                }
            }
        }
    } catch (e) {
        console.error('Subcategory preselect failed:', e);
    }
    const uploadArea = document.getElementById('image-upload-area');
    const fileInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview-container');
    const filesDT = new DataTransfer();
    
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
        
        const previewGrid = document.getElementById('image-preview-grid');
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
        const totalFiles = imageFiles.length;
        
        imageFiles.forEach((file, index) => {
            if (file.size > 2 * 1024 * 1024) { // 2MB limit
                alert(`File "${file.name}" is too large. Please select files under 2MB.`);
                return;
            }
            
            const exists = Array.from(filesDT.files).some(f => f.name === file.name && f.size === file.size);
            if (exists) return;
            filesDT.items.add(file);
            fileInput.files = filesDT.files;

            const reader = new FileReader();
            reader.onload = function(e) {
                processedCount++;
                
                // Remove loading state when first image is processed
                if (processedCount === 1) {
                    loadingDiv.remove();
                }
                
                const previewDiv = document.createElement('div');
                previewDiv.className = 'image-preview-item cursor-move';
                previewDiv.dataset.filename = file.name;
                previewDiv.dataset.fileIndex = filesDT.files.length - 1;
                previewDiv.innerHTML = `
                    <div class="relative">
                        <div class="absolute top-2 left-2 z-10 bg-black bg-opacity-50 rounded-full p-1">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                            </svg>
                        </div>
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
                    </div>
                    <div class="mt-2 space-y-1">
                        <p class="text-xs text-stone-500 dark:text-gray-400 truncate font-medium">${file.name}</p>
                        <p class="text-xs text-green-600 dark:text-green-400">✓ Ready</p>
                    </div>
                    `;
                previewGrid.appendChild(previewDiv);
                
                // Initialize or reinitialize sortable after each image is added
                // This ensures sortable works even if images load at different times
                setTimeout(function() {
                    initializeNewImagesSortable();
                }, 50);
                };
                reader.readAsDataURL(file);
        });
    }
    
    // Make removeImagePreview globally available
    window.removeImagePreview = function(button) {
        const previewDiv = button.closest('.image-preview-item');
        const filename = previewDiv.dataset.filename;
        const newDT = new DataTransfer();
        Array.from(filesDT.files).forEach(f => {
            if (f.name !== filename) newDT.items.add(f);
        });
        fileInput.files = newDT.files;
        filesDT.items.clear();
        Array.from(newDT.files).forEach(f => filesDT.items.add(f));
        previewDiv.remove();
        if (document.getElementById('image-preview-grid').querySelectorAll('.image-preview-item').length === 0) {
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
                
        // Defer creating hidden inputs to submit time; visual mark only here
            }
        }
    };
    
    // Function to ensure all marked images are added to form
    function ensureRemoveImagesInputs() {
        const form = document.getElementById('product-edit-form');
        const currentImages = document.getElementById('current-images');
        const markedImages = currentImages.querySelectorAll('[data-marked-for-removal="true"]');
        
        // Remove existing remove_images inputs to avoid duplicates
        form.querySelectorAll('input[name="remove_images"], input[name="remove_images[]"]').forEach(i => i.remove());
        
        // Build comma-delimited list of indices to remove
        const indices = Array.from(markedImages).map(el => Array.from(currentImages.children).indexOf(el)).filter(i => i >= 0);
        if (indices.length > 0) {
            const compressed = document.createElement('input');
            compressed.type = 'hidden';
            compressed.name = 'remove_images';
            compressed.value = indices.join(',');
            form.appendChild(compressed);
        }
        
    }
    
    // Initialize Sortable for Current Images
    const currentImagesContainer = document.getElementById('current-images');
    if (currentImagesContainer && typeof Sortable !== 'undefined') {
        window.currentImagesSortable = new Sortable(currentImagesContainer, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            filter: '.delete-button, .delete-overlay',
            preventOnFilter: false,
            onEnd: function(evt) {
                // Update image order indices after reordering
                updateCurrentImagesOrder();
            }
        });
    }
    
    // Function to initialize sortable for new images
    function initializeNewImagesSortable() {
        const previewGrid = document.getElementById('image-preview-grid');
        if (previewGrid && typeof Sortable !== 'undefined') {
            if (window.newImagesSortable) {
                window.newImagesSortable.destroy();
            }
            window.newImagesSortable = new Sortable(previewGrid, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                filter: '.delete-button, .delete-overlay',
                preventOnFilter: false,
                onEnd: function(evt) {
                    // Reorder files in DataTransfer object to match new order
                    reorderNewImages();
                }
            });
        }
    }
    
    // Update current images order and store in hidden input
    function updateCurrentImagesOrder() {
        const currentImages = document.getElementById('current-images');
        if (!currentImages) return;
        
        const imageItems = Array.from(currentImages.querySelectorAll('.image-preview-item'));
        const imagePaths = imageItems.map(item => item.dataset.imagePath).filter(Boolean);
        
        // Store order in hidden input
        let orderInput = document.getElementById('current-images-order');
        if (!orderInput) {
            orderInput = document.createElement('input');
            orderInput.type = 'hidden';
            orderInput.id = 'current-images-order';
            orderInput.name = 'current_images_order';
            document.getElementById('product-edit-form').appendChild(orderInput);
        }
        orderInput.value = JSON.stringify(imagePaths);
    }
    
    // Reorder new images files in DataTransfer to match visual order
    function reorderNewImages() {
        const previewGrid = document.getElementById('image-preview-grid');
        if (!previewGrid) return;
        
        const imageItems = Array.from(previewGrid.querySelectorAll('.image-preview-item'));
        const filenames = imageItems.map(item => item.dataset.filename).filter(Boolean);
        
        // Create new DataTransfer with files in the correct order
        const newDT = new DataTransfer();
        filenames.forEach(filename => {
            const file = Array.from(filesDT.files).find(f => f.name === filename);
            if (file) {
                newDT.items.add(file);
            }
        });
        
        // Update both DataTransfer and file input
        filesDT.items.clear();
        Array.from(newDT.files).forEach(f => filesDT.items.add(f));
        fileInput.files = filesDT.files;
    }
    
    // Make removeImagePreview update sortable after removal
    const originalRemoveImagePreview = window.removeImagePreview;
    window.removeImagePreview = function(button) {
        originalRemoveImagePreview(button);
        // Reinitialize sortable after removal
        setTimeout(() => {
            if (document.getElementById('image-preview-grid').querySelectorAll('.image-preview-item').length > 0) {
                initializeNewImagesSortable();
            }
        }, 100);
    };
    
    // Ensure all marked images are added to form before submission
    document.getElementById('product-edit-form').addEventListener('submit', function(e) {
        // Ensure all marked images are added to form before submission
        ensureRemoveImagesInputs();
        
        // Update current images order before submission
        updateCurrentImagesOrder();
        
        // Reorder new images before submission
        reorderNewImages();
    });

    // Room tag styling - update visual appearance when checkbox changes
    document.querySelectorAll('.room-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const span = this.nextElementSibling;
            if (this.checked) {
                span.classList.remove('bg-stone-100', 'text-stone-700', 'hover:bg-stone-200', 'dark:bg-gray-700', 'dark:text-gray-300', 'dark:hover:bg-gray-600');
                span.classList.add('bg-primary', 'text-white', 'shadow-md');
                // Add check icon if not present
                if (!span.querySelector('i[data-lucide="check"]')) {
                    const checkIcon = document.createElement('i');
                    checkIcon.setAttribute('data-lucide', 'check');
                    checkIcon.className = 'w-4 h-4 ml-2';
                    span.appendChild(checkIcon);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            } else {
                span.classList.remove('bg-primary', 'text-white', 'shadow-md');
                span.classList.add('bg-stone-100', 'text-stone-700', 'hover:bg-stone-200', 'dark:bg-gray-700', 'dark:text-gray-300', 'dark:hover:bg-gray-600');
                // Remove check icon if present
                const checkIcon = span.querySelector('i[data-lucide="check"]');
                if (checkIcon) {
                    checkIcon.remove();
                }
            }
        });
    });

});
</script>
@endsection
