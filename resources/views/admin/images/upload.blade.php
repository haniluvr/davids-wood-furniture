@extends('admin.layouts.app')

@section('title', 'Image Upload')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Image Upload
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li class="font-medium text-primary">Image Upload</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="max-w-4xl mx-auto">
    <!-- Upload Form -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Upload Images</h4>
        
        <form id="imageUploadForm" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Upload Type -->
            <div>
                <label for="type" class="mb-2.5 block text-black dark:text-white">
                    Upload Type <span class="text-red-500">*</span>
                </label>
                <select id="type" name="type" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" required>
                    <option value="">Select Type</option>
                    <option value="product">Product Images</option>
                    <option value="user">User Images</option>
                    <option value="general">General Images</option>
                </select>
            </div>

            <!-- Product Selection (if product type) -->
            <div id="productSelection" class="hidden">
                <label for="product_id" class="mb-2.5 block text-black dark:text-white">
                    Product
                </label>
                <select id="product_id" name="product_id" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                    <option value="">Select Product (Optional)</option>
                    <!-- Products will be loaded via AJAX -->
                </select>
            </div>

            <!-- File Upload Area -->
            <div>
                <label class="mb-2.5 block text-black dark:text-white">
                    Images <span class="text-red-500">*</span>
                </label>
                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition-colors duration-200 dark:border-gray-600">
                    <div class="space-y-4">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center dark:bg-gray-700">
                            <i data-lucide="upload" class="w-8 h-8 text-gray-500"></i>
                        </div>
                        <div>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">Drop images here or click to browse</p>
                            <p class="text-sm text-gray-500">Supports: JPEG, PNG, JPG, GIF, WebP, AVIF (Max 5MB each)</p>
                            <p class="text-sm text-gray-500">Maximum 10 images at once</p>
                        </div>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                        <button type="button" id="browseBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <i data-lucide="folder-open" class="w-4 h-4 mr-2"></i>
                            Browse Files
                        </button>
                    </div>
                </div>
            </div>

            <!-- Selected Files Preview -->
            <div id="filePreview" class="hidden">
                <h5 class="text-md font-medium text-black dark:text-white mb-4">Selected Files:</h5>
                <div id="previewContainer" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- File previews will be added here -->
                </div>
            </div>

            <!-- Upload Progress -->
            <div id="uploadProgress" class="hidden">
                <div class="bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                    <div id="progressBar" class="bg-primary h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p id="progressText" class="text-sm text-gray-600 dark:text-gray-400 mt-2">Uploading...</p>
            </div>

            <!-- Upload Button -->
            <div class="flex justify-end">
                <button type="submit" id="uploadBtn" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-6 py-3 text-white hover:bg-primary/90 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    Upload Images
                </button>
            </div>
        </form>
    </div>

    <!-- Upload Results -->
    <div id="uploadResults" class="hidden mt-6">
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Upload Results</h4>
            <div id="resultsContainer">
                <!-- Results will be displayed here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('images');
    const browseBtn = document.getElementById('browseBtn');
    const filePreview = document.getElementById('filePreview');
    const previewContainer = document.getElementById('previewContainer');
    const uploadForm = document.getElementById('imageUploadForm');
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const uploadResults = document.getElementById('uploadResults');
    const resultsContainer = document.getElementById('resultsContainer');
    const typeSelect = document.getElementById('type');
    const productSelection = document.getElementById('productSelection');
    const productSelect = document.getElementById('product_id');

    let selectedFiles = [];

    // Show/hide product selection based on type
    typeSelect.addEventListener('change', function() {
        if (this.value === 'product') {
            productSelection.classList.remove('hidden');
            loadProducts();
        } else {
            productSelection.classList.add('hidden');
        }
    });

    // Load products for selection
    function loadProducts() {
        fetch('/admin/api/products')
            .then(response => response.json())
            .then(data => {
                productSelect.innerHTML = '<option value="">Select Product (Optional)</option>';
                data.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name;
                    productSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading products:', error);
            });
    }

    // Browse button click
    browseBtn.addEventListener('click', () => {
        fileInput.click();
    });

    // File input change
    fileInput.addEventListener('change', handleFiles);

    // Drag and drop events
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-primary', 'bg-primary/5');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary', 'bg-primary/5');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary', 'bg-primary/5');
        const files = Array.from(e.dataTransfer.files);
        handleFiles({ target: { files } });
    });

    // Handle file selection
    function handleFiles(event) {
        const files = Array.from(event.target.files);
        selectedFiles = files;
        displayFilePreviews(files);
    }

    // Display file previews
    function displayFilePreviews(files) {
        previewContainer.innerHTML = '';
        
        if (files.length === 0) {
            filePreview.classList.add('hidden');
            return;
        }

        filePreview.classList.remove('hidden');

        files.forEach((file, index) => {
            const preview = document.createElement('div');
            preview.className = 'relative border border-gray-200 rounded-lg p-2 dark:border-gray-600';
            
            const img = document.createElement('img');
            img.className = 'w-full h-24 object-cover rounded';
            img.src = URL.createObjectURL(file);
            
            const info = document.createElement('div');
            info.className = 'mt-2 text-xs text-gray-600 dark:text-gray-400';
            info.innerHTML = `
                <p class="font-medium truncate">${file.name}</p>
                <p>${(file.size / 1024 / 1024).toFixed(2)} MB</p>
            `;
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600';
            removeBtn.innerHTML = '×';
            removeBtn.addEventListener('click', () => {
                selectedFiles.splice(index, 1);
                displayFilePreviews(selectedFiles);
            });
            
            preview.appendChild(img);
            preview.appendChild(info);
            preview.appendChild(removeBtn);
            previewContainer.appendChild(preview);
        });
    }

    // Form submission
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (selectedFiles.length === 0) {
            alert('Please select at least one image.');
            return;
        }

        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('type', typeSelect.value);
        
        if (typeSelect.value === 'product' && productSelect.value) {
            formData.append('product_id', productSelect.value);
        }

        selectedFiles.forEach(file => {
            formData.append('images[]', file);
        });

        // Show progress
        uploadProgress.classList.remove('hidden');
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Uploading...';

        try {
            const response = await fetch('/admin/images/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                displayUploadResults(result.images);
                uploadForm.reset();
                selectedFiles = [];
                displayFilePreviews([]);
            } else {
                alert('Upload failed: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            alert('Upload failed: ' + error.message);
        } finally {
            uploadProgress.classList.add('hidden');
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i data-lucide="upload" class="w-4 h-4"></i> Upload Images';
        }
    });

    // Display upload results
    function displayUploadResults(images) {
        resultsContainer.innerHTML = '';
        
        images.forEach(image => {
            const result = document.createElement('div');
            result.className = 'border border-gray-200 rounded-lg p-4 dark:border-gray-600';
            result.innerHTML = `
                <div class="flex items-center gap-4">
                    <img src="${image.url}" alt="${image.filename}" class="w-16 h-16 object-cover rounded">
                    <div class="flex-1">
                        <h6 class="font-medium text-black dark:text-white">${image.filename}</h6>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${(image.size / 1024 / 1024).toFixed(2)} MB</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${image.url}</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="copyToClipboard('${image.url}')" class="p-2 text-primary hover:bg-primary/10 rounded">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                        <button onclick="deleteImage('${image.path}')" class="p-2 text-red-500 hover:bg-red-50 rounded">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            `;
            resultsContainer.appendChild(result);
        });
        
        uploadResults.classList.remove('hidden');
    }
});

// Utility functions
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('URL copied to clipboard!');
    });
}

function deleteImage(path) {
    if (confirm('Are you sure you want to delete this image?')) {
        fetch('/admin/images/' + encodeURIComponent(path), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload();
            } else {
                alert('Delete failed: ' + result.message);
            }
        })
        .catch(error => {
            alert('Delete failed: ' + error.message);
        });
    }
}
</script>
@endsection
