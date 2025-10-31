@extends('admin.layouts.app')

@section('title', 'Media Library')

@section('content')
<!-- Header Section -->
<div class="max-w-6xl mx-auto mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg">
                <i data-lucide="image" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Media Library</h1>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Manage images for CMS pages and blog posts</p>
            </div>
        </div>
        <button onclick="uploadNewImage()" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-transparent bg-gradient-to-r from-emerald-600 to-teal-600 text-sm font-medium text-white shadow-lg transition-all duration-200 hover:from-emerald-700 hover:to-teal-700 hover:shadow-xl">
            <i data-lucide="upload" class="w-4 h-4"></i>
            Upload New Image
        </button>
    </div>
</div>

<div class="max-w-6xl mx-auto">
    <!-- Upload Section -->
    <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden mb-8">
        <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-gray-800 dark:to-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl">
                    <i data-lucide="upload" class="w-5 h-5 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Upload Images</h3>
            </div>
            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Upload new images to your media library</p>
        </div>
        <div class="p-8">
            <form id="imageUploadForm" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- File Upload Area -->
                <div>
                    <label class="mb-2.5 block text-stone-700 dark:text-stone-300">
                        Images <span class="text-red-500">*</span>
                    </label>
                    <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-emerald-500 transition-colors duration-200 dark:border-strokedark">
                        <div class="space-y-4">
                            <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">Drop images here or click to browse</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Supports: JPEG, PNG, JPG, GIF, WebP, AVIF (Max 5MB each)</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Maximum 10 images at once</p>
                            </div>
                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                            <button type="button" id="browseBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                </svg>
                                Browse Files
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Selected Files Preview -->
                <div id="filePreview" class="hidden">
                    <h5 class="text-md font-medium text-stone-900 dark:text-white mb-4">Selected Files:</h5>
                    <div id="previewContainer" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- File previews will be added here -->
                    </div>
                </div>

                <!-- Upload Progress -->
                <div id="uploadProgress" class="hidden">
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div id="progressBar" class="bg-emerald-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p id="progressText" class="text-sm text-gray-600 dark:text-gray-400 mt-2">Uploading...</p>
                </div>

                <!-- Upload Button -->
                <div class="flex justify-end">
                    <button type="submit" id="uploadBtn" class="flex items-center gap-2 rounded-xl border border-emerald-600 bg-emerald-600 px-6 py-3 text-white hover:bg-emerald-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Upload Images
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Images Gallery Section -->
    <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
        <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                    <i data-lucide="images" class="w-5 h-5 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-stone-900 dark:text-white">All Images</h3>
            </div>
            <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Browse and manage your uploaded images</p>
        </div>
        <div class="p-8">
            <!-- Filters -->
            <div class="mb-6">
                <div class="flex gap-4">
                    <input type="text" id="searchImages" placeholder="Search images..." class="flex-1 px-4 py-3 border border-stone-200 bg-white rounded-xl text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400">
                    <select id="sortImages" class="px-4 py-3 border border-stone-200 bg-white rounded-xl text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="name">Name A-Z</option>
                        <option value="size">Size</option>
                    </select>
                </div>
            </div>
            
            <!-- Images Grid -->
            <div id="imagesGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <!-- Images will be loaded here -->
            </div>
            
            <!-- Loading State -->
            <div id="loadingState" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600"></div>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Loading images...</p>
            </div>
            
            <!-- Empty State -->
            <div id="emptyState" class="text-center py-12 hidden">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No images</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by uploading your first image.</p>
            </div>
        </div>
    </div>
</div>

<!-- Hidden file input for upload -->
<input type="file" id="imageUpload" accept="image/*" class="hidden" onchange="handleImageUpload(this)">

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
    const imagesGrid = document.getElementById('imagesGrid');
    const loadingState = document.getElementById('loadingState');
    const emptyState = document.getElementById('emptyState');
    const searchInput = document.getElementById('searchImages');
    const sortSelect = document.getElementById('sortImages');

    let selectedFiles = [];
    let allImages = [];

    // Browse button click
    browseBtn.addEventListener('click', () => {
        fileInput.click();
    });

    // File input change
    fileInput.addEventListener('change', handleFiles);

    // Drag and drop events
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-emerald-500', 'bg-emerald-50');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-emerald-500', 'bg-emerald-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-emerald-500', 'bg-emerald-50');
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
            preview.className = 'relative border border-stone-200 dark:border-strokedark rounded-xl p-3 bg-white dark:bg-boxdark';
            
            const img = document.createElement('img');
            img.className = 'w-full h-24 object-cover rounded-lg';
            img.src = URL.createObjectURL(file);
            
            const info = document.createElement('div');
            info.className = 'mt-2 text-xs text-gray-600 dark:text-gray-400';
            info.innerHTML = `
                <p class="font-medium truncate text-stone-900 dark:text-white">${file.name}</p>
                <p>${(file.size / 1024 / 1024).toFixed(2)} MB</p>
            `;
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors duration-200';
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
        formData.append('type', 'cms');

        selectedFiles.forEach(file => {
            formData.append('images[]', file);
        });

        // Show progress
        uploadProgress.classList.remove('hidden');
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Uploading...';

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
                uploadForm.reset();
                selectedFiles = [];
                displayFilePreviews([]);
                loadImages(); // Reload images
                showNotification('Images uploaded successfully!', 'success');
            } else {
                alert('Upload failed: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            alert('Upload failed: ' + error.message);
        } finally {
            uploadProgress.classList.add('hidden');
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg> Upload Images';
        }
    });

    // Load images
    function loadImages() {
        loadingState.classList.remove('hidden');
        imagesGrid.classList.add('hidden');
        emptyState.classList.add('hidden');

        fetch('/admin/api/cms-images')
            .then(response => response.json())
            .then(data => {
                loadingState.classList.add('hidden');
                
                if (data.success && data.images.length > 0) {
                    allImages = data.images;
                    displayImages(data.images);
                    imagesGrid.classList.remove('hidden');
                } else {
                    emptyState.classList.remove('hidden');
                }
            })
            .catch(error => {
                loadingState.classList.add('hidden');
                console.error('Error loading images:', error);
                showNotification('Error loading images', 'error');
            });
    }

    // Display images
    function displayImages(images) {
        imagesGrid.innerHTML = '';
        
        images.forEach(image => {
            const imageItem = document.createElement('div');
            imageItem.className = 'relative group cursor-pointer bg-white dark:bg-boxdark rounded-xl border border-stone-200 dark:border-strokedark overflow-hidden shadow-sm hover:shadow-lg transition-all duration-200';
            imageItem.innerHTML = `
                <div class="aspect-square rounded-t-xl overflow-hidden bg-gray-100 dark:bg-gray-800">
                    <img src="${image.url}" alt="${image.filename}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                </div>
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-t-xl flex items-center justify-center">
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex gap-2">
                        <button onclick="copyImageUrl('${image.url}')" class="p-2 bg-white dark:bg-boxdark rounded-full shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" title="Copy URL">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteImage('${image.path}')" class="p-2 bg-red-500 rounded-full shadow-lg hover:bg-red-600 transition-colors duration-200" title="Delete">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-3 text-xs text-gray-600 dark:text-gray-400">
                    <p class="font-medium truncate text-stone-900 dark:text-white">${image.filename}</p>
                    <p>${(image.size / 1024 / 1024).toFixed(2)} MB</p>
                </div>
            `;
            
            imagesGrid.appendChild(imageItem);
        });
    }

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const filteredImages = allImages.filter(image => 
            image.filename.toLowerCase().includes(searchTerm)
        );
        displayImages(filteredImages);
    });

    // Sort functionality
    sortSelect.addEventListener('change', function() {
        const sortBy = this.value;
        let sortedImages = [...allImages];
        
        switch(sortBy) {
            case 'newest':
                sortedImages.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                break;
            case 'oldest':
                sortedImages.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                break;
            case 'name':
                sortedImages.sort((a, b) => a.filename.localeCompare(b.filename));
                break;
            case 'size':
                sortedImages.sort((a, b) => b.size - a.size);
                break;
        }
        
        displayImages(sortedImages);
    });

    // Load images on page load
    loadImages();
});

// Global functions
function uploadNewImage() {
    document.getElementById('imageUpload').click();
}

function handleImageUpload(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('image', input.files[0]);
        formData.append('type', 'cms');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch('/admin/images/upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload(); // Reload page to show new image
            } else {
                alert('Upload failed: ' + result.message);
            }
        })
        .catch(error => {
            alert('Upload failed: ' + error.message);
        });
    }
}

function copyImageUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        showNotification('Image URL copied to clipboard!', 'success');
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

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl text-white z-50 shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-emerald-600' : 'bg-red-600'
    }`;
    notification.innerHTML = `
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>'
                }
            </svg>
            <span class="font-medium">${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>
@endsection
