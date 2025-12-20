@extends('layouts.app')

@section('title', 'Unissa Cafe - Create Product')

@push('head')
<!-- CropperJS CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-emerald-50 to-cyan-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Modal-style Container -->
        <div class="bg-white rounded-3xl shadow-2xl border border-teal-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-600 via-teal-700 to-emerald-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add New Product
                        </h1>
                        <p class="text-teal-100 mt-1">Create a new product for the catalog</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition-all duration-200 border border-white/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Form Content -->
            <div class="p-8">
                <form x-ref="productForm" @submit="handleSubmit" enctype="multipart/form-data" method="POST" action="{{ route('admin.products.store') }}"
                      x-data="productForm()" x-init="init()" key="create-product-form">
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
                
                <!-- Form Content -->
                    <!-- Success Message -->
                    <div x-show="successMessage" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
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
                         class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <template x-for="(errorMessages, field) in errors" :key="field">
                                            <li>
                                                <span class="font-medium capitalize" x-text="field.replace('_', ' ')"></span>: 
                                                <span x-text="Array.isArray(errorMessages) ? errorMessages[0] : errorMessages"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Product Name -->
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-semibold text-gray-800">Product Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full px-4 py-3 border-2 rounded-xl focus:ring-4 transition-all duration-200
                                              {{ $errors->has('name') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'border-gray-200 focus:border-teal-500 focus:ring-teal-500/20' }}">
                                @error('name')
                                    <p class="text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <template x-if="errors.name">
                                    <p class="text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span x-text="Array.isArray(errors.name) ? errors.name[0] : errors.name"></span>
                                    </p>
                                </template>
                            </div>

                            <!-- Category -->
                            <div class="space-y-2">
                                <label for="category" class="block text-sm font-semibold text-gray-800">Category *</label>
                                <input type="text" name="category" id="category" value="{{ old('category') }}" required list="categories"
                                       class="w-full px-4 py-3 border-2 rounded-xl focus:ring-4 transition-all duration-200
                                              {{ $errors->has('category') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'border-gray-200 focus:border-teal-500 focus:ring-teal-500/20' }}">
                                <datalist id="categories">
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">
                                    @endforeach
                                </datalist>
                                @error('category')
                                    <p class="text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div class="space-y-2">
                                <label for="type" class="block text-sm font-semibold text-gray-800">Type *</label>
                                <select name="type" id="type" required
                                        class="w-full px-4 py-3 border-2 rounded-xl focus:ring-4 transition-all duration-200
                                               {{ $errors->has('type') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'border-gray-200 focus:border-teal-500 focus:ring-teal-500/20' }}">
                                    <option value="">Select Type</option>
                                    <option value="food" {{ old('type') == 'food' ? 'selected' : '' }}>Food</option>
                                    <option value="merch" {{ old('type') == 'merch' ? 'selected' : '' }}>Merchandise</option>
                                </select>
                                @error('type')
                                    <p class="text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="space-y-2">
                                <label for="price" class="block text-sm font-semibold text-gray-800">Price *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-lg font-medium">$</span>
                                    </div>
                                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                                           class="w-full pl-8 pr-4 py-3 border-2 rounded-xl focus:ring-4 transition-all duration-200
                                                  {{ $errors->has('price') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'border-gray-200 focus:border-teal-500 focus:ring-teal-500/20' }}">
                                </div>
                                @error('price')
                                    <p class="text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-semibold text-gray-800">Status *</label>
                                <select name="status" id="status" required
                                        class="w-full px-4 py-3 border-2 rounded-xl focus:ring-4 transition-all duration-200
                                               {{ $errors->has('status') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'border-gray-200 focus:border-teal-500 focus:ring-teal-500/20' }}">
                                    @foreach(App\Models\Product::getStatuses() as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', 'active') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Low Stock Threshold -->
                            <div class="space-y-2">
                                <label for="low_stock_threshold" class="block text-sm font-semibold text-gray-800">Low Stock Threshold *</label>
                                <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', 10) }}" min="0" required
                                       class="w-full px-4 py-3 border-2 rounded-xl focus:ring-4 transition-all duration-200
                                              {{ $errors->has('low_stock_alert') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'border-gray-200 focus:border-teal-500 focus:ring-teal-500/20' }}"
                                @error('low_stock_threshold')
                                    <p class="text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="lg:col-span-2 space-y-2">
                            <label for="desc" class="block text-sm font-semibold text-gray-800">Description *</label>
                            <textarea name="desc" id="desc" rows="4" required
                                      class="w-full px-4 py-3 border-2 {{ $errors->has('desc') ? 'border-red-400' : 'border-gray-200' }} rounded-xl {{ $errors->has('desc') ? 'focus:border-red-500' : 'focus:border-teal-500' }} focus:ring-4 {{ $errors->has('desc') ? 'focus:ring-red-500/20' : 'focus:ring-teal-500/20' }} transition-all duration-200 resize-none">{{ old('desc') }}</textarea>
                            @error('desc')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Product Image -->
                        <div class="lg:col-span-2 space-y-4">
                            <label class="block text-sm font-semibold text-gray-800">Product Image *</label>
                            
                            <div class="border-2 border-dashed border-teal-300 rounded-xl p-8 text-center hover:border-teal-400 transition-colors duration-200 bg-teal-50/50">
                                <div class="space-y-4">
                                    <div class="mx-auto w-16 h-16 bg-gradient-to-br from-teal-500 to-emerald-500 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <label for="img" class="cursor-pointer">
                                            <span class="bg-gradient-to-r from-teal-600 to-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-teal-700 hover:to-emerald-700 transition-all duration-200 inline-block">
                                                Choose Image
                                            </span>
                                            <input id="img" name="img" type="file" accept="image/*" class="hidden"
                                                   @change="startCrop($event)">
                                        </label>
                                        <p class="text-sm text-gray-600 mt-2">or drag and drop your image here</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                            @error('img')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    
                    <!-- Image Cropper Container -->
                    <div x-show="showCropper" class="mb-4" x-cloak>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                            <div class="cropper-wrapper" style="max-height: 400px; overflow: hidden;">
                                <img id="cropper-img" class="max-w-full block mx-auto" style="max-height: 350px;">
                            </div>
                        </div>
                        <div class="flex justify-between mt-3">
                            <button type="button" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-4 py-2 rounded-2xl text-sm transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl" 
                                    @click="resetCropper()">Reset</button>
                            <button type="button" class="bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white px-4 py-2 rounded-2xl text-sm transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl" 
                                    @click="finishCrop()">Apply Crop</button>
                        </div>
                    </div>
                    
                    <!-- Preview Container -->
                    <div x-show="croppedUrl" class="mb-4" x-cloak>
                        <label class="block text-sm font-medium mb-2">Preview as Product Card:</label>
                        <!-- Mini Product Card Preview -->
                        <div class="w-48 bg-white rounded-xl shadow-md overflow-hidden border">
                            <div class="relative overflow-hidden">
                                <img :src="croppedUrl" class="w-full h-36 object-cover">
                                <div class="absolute top-2 left-2">
                                    <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">New</span>
                                </div>
                            </div>
                            <div class="p-3">
                                <h3 class="text-sm font-bold text-gray-800 mb-1">New Product</h3>
                                <p class="text-xs text-gray-600">Product preview</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden input for cropped image data -->
                    <input type="hidden" name="cropped_image" id="cropped-data">
                    
                    @error('img')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                        <!-- Stock Management Options -->
                        <div class="lg:col-span-2 border-t-2 border-teal-100 pt-8 mt-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                Stock Management
                            </h3>
                            
                            <div class="space-y-6">
                                <!-- Active Status -->
                                <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-200">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                           class="w-5 h-5 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                    <label for="is_active" class="text-sm font-semibold text-gray-900">Product is active</label>
                                </div>

                                <!-- Track Stock -->
                                <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-200">
                                    <input type="checkbox" name="track_stock" id="track_stock" value="1" {{ old('track_stock', true) ? 'checked' : '' }}
                                           class="w-5 h-5 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                    <label for="track_stock" class="text-sm font-semibold text-gray-900">Track stock quantity (recommended)</label>
                                </div>

                                <!-- Stock Quantity -->
                                <div id="stock_quantity_field" class="space-y-2">
                                    <label for="stock_quantity" class="block text-sm font-semibold text-gray-800">Initial Stock Quantity</label>
                                    <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0"
                                           class="w-full px-4 py-3 border-2 {{ $errors->has('stock_quantity') ? 'border-red-400' : 'border-gray-200' }} rounded-xl {{ $errors->has('stock_quantity') ? 'focus:border-red-500' : 'focus:border-teal-500' }} focus:ring-4 {{ $errors->has('stock_quantity') ? 'focus:ring-red-500/20' : 'focus:ring-teal-500/20' }} transition-all duration-200">
                                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                        💡 <strong>Tip:</strong> Status will automatically be set to "Out of Stock" if quantity is 0, or "Available" if quantity > 0
                                    </p>
                                    @error('stock_quantity')
                                        <p class="text-sm text-red-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-center pt-6 pb-8 border-t border-gray-200">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white rounded-2xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        // Bootstrap for admin product create page
        window.__adminProductCreate = {
            csrf: '{{ csrf_token() }}',
            storeUrl: '{{ route("admin.products.store") }}',
            redirectUrl: '{{ route("admin.products.index") }}'
        };
    </script>
    <script src="/js/admin-product-create.js"></script>
    <!-- CropperJS JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
@endpush
@endsection