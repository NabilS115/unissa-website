@extends('layouts.app')

@section('title', 'Create Product')

@push('head')
<!-- CropperJS CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Product</h1>
                    <p class="text-gray-600 mt-2">Add a new product to your inventory</p>
                    <div class="flex items-center gap-2 mt-3">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-blue-600">
                            <strong>Smart Tracking:</strong> Stock status is tracked immediately upon creation and auto-updated based on quantity
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Products
                </a>
            </div>
        </div>

        <!-- Create Form -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form x-ref="productForm" @submit.prevent="submitProduct" enctype="multipart/form-data" class="space-y-6" x-data="{ isSubmitting: false, errors: {}, successMessage: '', showErrors: false, showCropper: false, croppedUrl: '' }">
                @csrf
                
                <!-- Success Message -->
                <div x-show="successMessage" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm font-medium text-green-800" x-text="successMessage"></p>
                    </div>
                </div>
                
                <!-- Error Messages -->
                <div x-show="Object.keys(errors).length > 0" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <template x-for="(error, field) in errors" :key="field">
                                    <p x-text="`${field}: ${Array.isArray(error) ? error[0] : error}`"></p>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               :class="errors.name ? 'w-full border border-red-500 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500' : 'w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500'">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <template x-if="errors.name">
                            <p class="mt-1 text-sm text-red-600" x-text="Array.isArray(errors.name) ? errors.name[0] : errors.name"></p>
                        </template>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <input type="text" name="category" id="category" value="{{ old('category') }}" required list="categories"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                        <datalist id="categories">
                            @foreach($categories as $category)
                                <option value="{{ $category }}">
                            @endforeach
                        </datalist>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select name="type" id="type" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                            <option value="">Select Type</option>
                            <option value="food" {{ old('type') == 'food' ? 'selected' : '' }}>Food</option>
                            <option value="merch" {{ old('type') == 'merch' ? 'selected' : '' }}>Merchandise</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                                   class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            @foreach(App\Models\Product::getStatuses() as $value => $label)
                                <option value="{{ $value }}" {{ old('status', 'active') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Low Stock Threshold -->
                    <div>
                        <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-2">Low Stock Threshold *</label>
                        <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', 10) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('low_stock_threshold') border-red-500 @enderror">
                        @error('low_stock_threshold')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="desc" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="desc" id="desc" rows="4" required
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('desc') border-red-500 @enderror">{{ old('desc') }}</textarea>
                    @error('desc')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Image -->
                <div>
                    <label for="img" class="block text-sm font-medium text-gray-700 mb-2">
                        Product Image * 
                        <span class="text-xs text-gray-500">(click & drag to crop)</span>
                    </label>
                    
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="img" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload a file</span>
                                    <input id="img" name="img" type="file" accept="image/*" required 
                                           class="sr-only @error('img') border-red-500 @enderror"
                                           @change="startCrop($event)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 20MB</p>
                        </div>
                    </div>
                    
                    <!-- Cropper Preview -->
                    <div x-show="showCropper" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="mt-4 bg-gray-50 border-2 border-gray-200 rounded-lg p-4">
                        
                        <div class="flex flex-col items-center justify-center">
                            <div id="cropper-preview" class="w-full max-w-lg h-64 bg-white border border-gray-300 rounded-lg overflow-hidden">
                                <img id="cropper-img" class="w-full h-full object-contain" />
                            </div>
                            
                            <button type="button" 
                                    onclick="finishCrop()"
                                    class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Crop & Preview
                            </button>
                        </div>
                    </div>
                    
                    <!-- Cropped Preview -->
                    <div x-show="croppedUrl" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="mt-4 bg-white border border-gray-200 rounded-lg p-4">
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cropped Preview:</label>
                        <div class="flex justify-center">
                            <img :src="croppedUrl" 
                                 class="w-64 h-48 object-cover rounded-lg border border-gray-300 shadow-sm" />
                        </div>
                    </div>
                    
                    @error('img')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock Management Options -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Management</h3>
                    
                    <div class="space-y-4">
                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Product is active</label>
                        </div>

                        <!-- Track Stock -->
                        <div class="flex items-center">
                            <input type="checkbox" name="track_stock" id="track_stock" value="1" {{ old('track_stock', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="track_stock" class="ml-2 block text-sm text-gray-900">Track stock quantity (recommended)</label>
                        </div>

                        <!-- Stock Quantity -->
                        <div id="stock_quantity_field">
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">Initial Stock Quantity</label>
                            <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stock_quantity') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">
                                <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Status will automatically be set to "Out of Stock" if quantity is 0, or "Available" if quantity > 0
                            </p>
                            @error('stock_quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            :disabled="isSubmitting"
                            :class="isSubmitting ? 'px-6 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed' : 'px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors'">
                        <span x-show="!isSubmitting">Create Product</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Image cropping functionality
let cropper = null;
let croppedBlob = null;

function startCrop(event) {
    const file = event.target.files[0];
    
    if (!file) return;
    
    // Show cropper
    const formData = document.querySelector('[x-ref="productForm"]').__x.$data;
    formData.showCropper = true;
    
    // Clean up previous cropper
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    
    const img = document.getElementById('cropper-img');
    const reader = new FileReader();
    
    reader.onload = function(e) {
        img.src = e.target.result;
        
        // Initialize cropper after image loads
        img.onload = function() {
            cropper = new Cropper(img, {
                aspectRatio: 4/3,
                viewMode: 1,
                autoCropArea: 1,
                responsive: true,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        };
    };
    
    reader.readAsDataURL(file);
}

function finishCrop() {
    if (cropper) {
        cropper.getCroppedCanvas({
            width: 800,
            height: 600,
            imageSmoothingEnabled: false,
            imageSmoothingQuality: 'high',
        }).toBlob(function(blob) {
            croppedBlob = blob;
            const croppedUrl = URL.createObjectURL(blob);
            
            // Update Alpine.js data
            const formData = document.querySelector('[x-ref="productForm"]').__x.$data;
            formData.croppedUrl = croppedUrl;
        }, 'image/jpeg', 0.9);
    }
}

// Form submission functionality
function submitProduct() {
    const formElement = document.querySelector('[x-ref="productForm"]');
    const formData = new FormData(formElement);
    const alpineData = formElement.__x.$data;
    
    // Set submitting state
    alpineData.isSubmitting = true;
    alpineData.errors = {};
    
    // Add cropped image data if available
    if (croppedBlob) {
        // Convert blob to base64
        const reader = new FileReader();
        reader.onload = function() {
            formData.set('cropped_image', reader.result);
            sendFormData(formData);
        };
        reader.readAsDataURL(croppedBlob);
    } else {
        sendFormData(formData);
    }
}

function sendFormData(formData) {
    fetch('{{ route('admin.products.store') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const alpineData = document.querySelector('[x-ref="productForm"]').__x.$data;
        
        if (data.success) {
            // Show success message
            alpineData.successMessage = data.message || 'Product created successfully!';
            alpineData.errors = {};
            
            // Redirect after showing success message
            setTimeout(() => {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    window.location.href = '{{ route('admin.products.index') }}';
                }
            }, 1500);
        } else {
            // Handle validation errors
            if (data.errors) {
                alpineData.errors = data.errors;
                
                // Show first error
                const firstError = Object.values(data.errors)[0];
                if (Array.isArray(firstError)) {
                    alert(firstError[0]);
                } else {
                    alert(firstError);
                }
            } else if (data.error) {
                alert(data.error);
            } else {
                alert('Failed to create product. Please try again.');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error. Please try again.');
    })
    .finally(() => {
        const alpineData = document.querySelector('[x-ref="productForm"]').__x.$data;
        alpineData.isSubmitting = false;
    });
}

// Existing stock tracking functionality
document.getElementById('track_stock').addEventListener('change', function() {
    const stockField = document.getElementById('stock_quantity_field');
    const stockInput = document.getElementById('stock_quantity');
    
    if (this.checked) {
        stockField.classList.remove('hidden');
        stockInput.required = true;
    } else {
        stockField.classList.add('hidden');
        stockInput.required = false;
        stockInput.value = 0;
    }
});

// Initialize on page load
if (document.getElementById('track_stock').checked) {
    document.getElementById('stock_quantity_field').classList.remove('hidden');
    document.getElementById('stock_quantity').required = true;
}
</script>

<!-- CropperJS JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
@endpush
@endsection