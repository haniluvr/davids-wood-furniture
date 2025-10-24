@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            Edit Product
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a href="{{ admin_route('dashboard') }}" class="font-medium">Dashboard</a></li>
                <li class="font-medium text-primary">/</li>
                <li><a href="{{ admin_route('products.index') }}" class="font-medium">Products</a></li>
                <li class="font-medium text-primary">/</li>
                <li class="font-medium text-primary">Edit</li>
            </ol>
        </nav>
    </div>

    <form action="{{ admin_route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Product Name <span class="text-meta-1">*</span>
                            </label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $product->name) }}"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                required
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                SKU <span class="text-meta-1">*</span>
                            </label>
                            <input
                                type="text"
                                name="sku"
                                value="{{ old('sku', $product->sku) }}"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                required
                            >
                            @error('sku')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Category <span class="text-meta-1">*</span>
                            </label>
                            <select
                                name="category_id"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                required
                            >
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Barcode
                            </label>
                            <input
                                type="text"
                                name="barcode"
                                value="{{ old('barcode', $product->barcode) }}"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                            @error('barcode')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="mb-2.5 block text-black dark:text-white">
                            Short Description
                        </label>
                        <textarea
                            name="short_description"
                            rows="3"
                            class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            placeholder="Brief product description..."
                        >{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')
                            <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <label class="mb-2.5 block text-black dark:text-white">
                            Description <span class="text-meta-1">*</span>
                        </label>
                        <textarea
                            name="description"
                            rows="6"
                            class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            required
                        >{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Pricing</h3>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Regular Price <span class="text-meta-1">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input
                                    type="number"
                                    name="price"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('price', $product->price) }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 pl-8 pr-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    required
                                >
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Cost Price
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input
                                    type="number"
                                    name="cost_price"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('cost_price', $product->cost_price) }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 pl-8 pr-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                            @error('cost_price')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Sale Price
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input
                                    type="number"
                                    name="sale_price"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('sale_price', $product->sale_price) }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 pl-8 pr-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                            </div>
                            @error('sale_price')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Inventory</h3>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Stock Quantity <span class="text-meta-1">*</span>
                            </label>
                            <input
                                type="number"
                                name="stock_quantity"
                                min="0"
                                value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                required
                            >
                            @error('stock_quantity')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Low Stock Threshold <span class="text-meta-1">*</span>
                            </label>
                            <input
                                type="number"
                                name="low_stock_threshold"
                                min="0"
                                value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                required
                            >
                            @error('low_stock_threshold')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex items-center space-x-4">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                name="manage_stock"
                                value="1"
                                {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input"
                            >
                            <span class="ml-2 text-black dark:text-white">Manage Stock</span>
                        </label>
                    </div>
                </div>

                <!-- Physical Attributes -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Physical Attributes</h3>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Weight (lbs)
                            </label>
                            <input
                                type="number"
                                name="weight"
                                step="0.01"
                                min="0"
                                value="{{ old('weight', $product->weight) }}"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                            @error('weight')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Dimensions
                            </label>
                            <input
                                type="text"
                                name="dimensions"
                                value="{{ old('dimensions', $product->dimensions) }}"
                                placeholder="e.g., 24\" x 18\" x 6\""
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                            @error('dimensions')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">
                                Tax Class
                            </label>
                            <input
                                type="text"
                                name="tax_class"
                                value="{{ old('tax_class', $product->tax_class) }}"
                                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            >
                            @error('tax_class')
                                <p class="mt-1 text-sm text-meta-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Product Images -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Product Images</h3>
                    
                    <!-- Current Images -->
                    @if($product->images && count($product->images) > 0)
                        <div class="mb-4">
                            <h4 class="mb-3 text-sm font-medium text-black dark:text-white">Current Images</h4>
                            <div class="grid grid-cols-2 gap-3" id="current-images">
                                @foreach($product->images as $index => $image)
                                    <div class="group relative">
                                        <img src="{{ Storage::url($image) }}" alt="Product Image" class="h-24 w-full rounded-lg object-cover">
                                        <button type="button" class="absolute -right-2 -top-2 hidden h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white group-hover:flex" onclick="removeImage({{ $index }})">
                                            <i data-lucide="x" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upload Area -->
                    <div class="border-2 border-dashed border-stroke rounded-lg p-6 text-center hover:border-primary transition-colors" id="upload-area">
                        <div class="mx-auto mb-4 h-12 w-12 text-gray-400">
                            <i data-lucide="upload" class="h-full w-full"></i>
                        </div>
                        <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">Drop images here or click to upload</p>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB each</p>
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden" id="image-input">
                    </div>

                    <!-- Preview Area -->
                    <div id="image-preview" class="mt-4 grid grid-cols-2 gap-3 hidden"></div>
                </div>

                <!-- Product Status -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <h3 class="mb-6 text-xl font-semibold text-black dark:text-white">Product Status</h3>
                    
                    <div class="space-y-4">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input"
                            >
                            <span class="ml-2 text-black dark:text-white">Active</span>
                        </label>

                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                name="featured"
                                value="1"
                                {{ old('featured', $product->featured) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-stroke text-primary focus:ring-2 focus:ring-primary dark:border-strokedark dark:bg-form-input"
                            >
                            <span class="ml-2 text-black dark:text-white">Featured Product</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                    <div class="space-y-3">
                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-lg bg-primary p-3 font-medium text-white hover:bg-opacity-90"
                        >
                            Update Product
                        </button>
                        
                        <a
                            href="{{ admin_route('products.index') }}"
                            class="flex w-full justify-center rounded-lg border border-stroke bg-white p-3 font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-boxdark-2"
                        >
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('upload-area');
    const imageInput = document.getElementById('image-input');
    const imagePreview = document.getElementById('image-preview');

    // Click to upload
    uploadArea.addEventListener('click', () => imageInput.click());

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('border-primary', 'bg-primary/5');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('border-primary', 'bg-primary/5');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-primary', 'bg-primary/5');
        
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    // File input change
    imageInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.createElement('div');
                    preview.className = 'group relative';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="h-24 w-full rounded-lg object-cover">
                        <button type="button" class="absolute -right-2 -top-2 hidden h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white group-hover:flex" onclick="removePreview(this)">
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </button>
                    `;
                    imagePreview.appendChild(preview);
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

function removeImage(index) {
    if (confirm('Are you sure you want to remove this image?')) {
        // Add hidden input to mark image for removal
        const form = document.querySelector('form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'remove_images[]';
        input.value = index;
        form.appendChild(input);
        
        // Remove from display
        document.querySelector(`#current-images div:nth-child(${index + 1})`).remove();
    }
}

function removePreview(element) {
    element.parentElement.remove();
}
</script>
@endpush
@endsection
