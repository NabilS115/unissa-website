@extends('layouts.app')

@section('title', 'Printing Services - UNISSA Cafe')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .file-upload-area {
        border: 2px dashed #cbd5e1;
        transition: all 0.3s ease;
    }
    .file-upload-area.dragover {
        border-color: #0d9488;
        background-color: #f0fdfa;
    }
    .print-job-card {
        transition: all 0.3s ease;
    }
    .print-job-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')

@if(auth()->check() && auth()->user()->role === 'admin')
    <!-- Admin Edit Button -->
    <div class="fixed top-20 right-4 z-50">
        <a href="{{ route('content.printing') }}" 
           class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
           style="background-color: #0d9488 !important;">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Page
        </a>
    </div>
@endif

<div class="container mx-auto px-6 py-8">

    <!-- Enhanced Hero Section -->
    <div class="relative bg-gradient-to-br from-teal-600 via-emerald-600 to-cyan-600 py-20 mb-12 rounded-3xl overflow-hidden shadow-2xl">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full"></div>
        <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white/10 rounded-full"></div>
        
        <div class="relative z-10 text-center px-4">
            <div class="mb-6 animate-fade-in-up">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 drop-shadow-lg">
                    <span class="text-white">{{ \App\Models\ContentBlock::get('printing_hero_title', '🖨️ Printing Services', 'text', 'printing') }}</span>
                </h1>
            </div>
            
            <p class="text-xl md:text-2xl text-white/90 mb-10 max-w-3xl mx-auto leading-relaxed drop-shadow-md">
                {!! \App\Models\ContentBlock::get('printing_hero_subtitle', 'Professional printing services at <strong>UNISSA Cafe</strong>. Upload your documents, photos, or presentations and get high-quality prints while you enjoy our cafe!', 'text', 'printing') !!}
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                <a href="{{ route('unissa-cafe.homepage') }}" 
                   class="group inline-flex items-center gap-3 bg-white text-teal-600 hover:bg-gray-50 px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    ← Back to Homepage
                </a>
                <a href="{{ route('unissa-cafe.catalog') }}" 
                   class="group inline-flex items-center gap-3 bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 border-2 border-white/30 hover:border-white/50 transform hover:-translate-y-1">
                    Browse Products
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <!-- Main Content Section -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Upload Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="p-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Upload Your Files
                        </h2>

                        @auth
                            <form id="print-upload-form" enctype="multipart/form-data">
                                @csrf
                                <!-- File Upload Area -->
                                <div class="file-upload-area rounded-2xl p-12 text-center mb-8" id="file-upload-area">
                                    <input type="file" id="file-input" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" required>
                                    <svg class="mx-auto h-20 w-20 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-2xl font-medium text-gray-900 mb-3">{{ \App\Models\ContentBlock::get('upload_instructions', 'Drop your files here or click to browse', 'text', 'printing') }}</p>
                                    <p class="text-gray-600 mb-6 text-lg">{{ \App\Models\ContentBlock::get('supported_formats', 'Supported: PDF, DOC, DOCX, JPG, PNG (max 10MB)', 'text', 'printing') }}</p>
                                    <button type="button" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1" onclick="document.getElementById('file-input').click()">
                                        Choose File
                                    </button>
                                </div>

                                <!-- File Info Display -->
                                <div id="file-info" class="hidden mb-8 p-6 bg-blue-50 border border-blue-200 rounded-2xl">
                                    <div class="flex items-center">
                                        <svg class="w-12 h-12 text-blue-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div>
                                            <p class="font-bold text-blue-900 text-lg" id="file-name"></p>
                                            <p class="text-blue-700" id="file-size"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Print Specifications -->
                                <div class="grid md:grid-cols-2 gap-6 mb-8">
                                    <!-- Paper Size -->
                                    <div>
                                        <label class="block text-lg font-bold text-gray-900 mb-3">Paper Size</label>
                                        <select name="paper_size" required class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg">
                                            <option value="A4" selected>A4 (210 × 297 mm)</option>
                                            <option value="A3">A3 (297 × 420 mm)</option>
                                            <option value="Letter">Letter (8.5 × 11 inch)</option>
                                            <option value="Legal">Legal (8.5 × 14 inch)</option>
                                        </select>
                                    </div>

                                    <!-- Color Option -->
                                    <div>
                                        <label class="block text-lg font-bold text-gray-900 mb-3">Color Option</label>
                                        <select name="color_option" required class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg" onchange="updatePricing()">
                                            <option value="black_white" selected>Black & White</option>
                                            <option value="color">Color</option>
                                        </select>
                                    </div>

                                    <!-- Paper Type -->
                                    <div>
                                        <label class="block text-lg font-bold text-gray-900 mb-3">Paper Type</label>
                                        <select name="paper_type" required class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg" onchange="updatePricing()">
                                            <option value="regular" selected>Regular Paper</option>
                                            <option value="photo">Photo Paper</option>
                                            <option value="cardstock">Cardstock</option>
                                        </select>
                                    </div>

                                    <!-- Copies -->
                                    <div>
                                        <label class="block text-lg font-bold text-gray-900 mb-3">Number of Copies</label>
                                        <input type="number" name="copies" value="1" min="1" max="{{ \App\Models\ContentBlock::get('max_copies', '100', 'text', 'printing') }}" required class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg" onchange="updatePricing()">
                                    </div>

                                    <!-- Orientation -->
                                    <div>
                                        <label class="block text-lg font-bold text-gray-900 mb-3">Orientation</label>
                                        <select name="orientation" required class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg">
                                            <option value="portrait" selected>Portrait</option>
                                            <option value="landscape">Landscape</option>
                                        </select>
                                    </div>

                                    <!-- Price Display -->
                                    <div>
                                        <label class="block text-lg font-bold text-gray-900 mb-3">Estimated Price</label>
                                        <div class="text-3xl font-bold text-green-600 bg-green-50 border border-green-200 rounded-xl px-6 py-4" id="price-display">
                                            B$0.10
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="mb-8">
                                    <label class="block text-lg font-bold text-gray-900 mb-3">Special Instructions (Optional)</label>
                                    <textarea name="notes" rows="4" class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg resize-none" placeholder="Any special printing instructions..."></textarea>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white text-xl font-bold py-6 px-8 rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 disabled:opacity-50" id="upload-btn">
                                    <span id="upload-text">Upload & Calculate Price</span>
                                    <span id="upload-loading" class="hidden">
                                        <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                </button>
                            </form>
                        @else
                            <!-- Login Required -->
                            <div class="text-center py-16">
                                <svg class="mx-auto h-20 w-20 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Login Required</h3>
                                <p class="text-gray-600 mb-8 text-lg">{{ \App\Models\ContentBlock::get('login_required_message', 'Please login to use our printing services.', 'text', 'printing') }}</p>
                                <a href="{{ route('login') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    Login to Continue
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Pricing Info -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            Pricing Guide
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="font-medium">B&W Regular</span>
                                <span class="font-bold text-green-600">B${{ \App\Models\ContentBlock::get('price_bw_regular', '0.10', 'text', 'printing') }}/page</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="font-medium">Color Regular</span>
                                <span class="font-bold text-green-600">B${{ \App\Models\ContentBlock::get('price_color_regular', '0.25', 'text', 'printing') }}/page</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="font-medium">B&W Photo Paper</span>
                                <span class="font-bold text-green-600">B${{ \App\Models\ContentBlock::get('price_bw_photo', '0.50', 'text', 'printing') }}/page</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="font-medium">Color Photo Paper</span>
                                <span class="font-bold text-green-600">B${{ \App\Models\ContentBlock::get('price_color_photo', '1.00', 'text', 'printing') }}/page</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="font-medium">B&W Cardstock</span>
                                <span class="font-bold text-green-600">B${{ \App\Models\ContentBlock::get('price_bw_cardstock', '0.25', 'text', 'printing') }}/page</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="font-medium">Color Cardstock</span>
                                <span class="font-bold text-green-600">B${{ \App\Models\ContentBlock::get('price_color_cardstock', '0.50', 'text', 'printing') }}/page</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Info -->
                <div class="bg-gradient-to-br from-teal-50 to-green-50 rounded-2xl shadow-lg overflow-hidden border border-teal-100">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-teal-900 mb-6">📋 Supported Formats</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center text-teal-800 font-medium">
                                <span class="mr-3 text-xl">📄</span> PDF Documents
                            </li>
                            <li class="flex items-center text-teal-800 font-medium">
                                <span class="mr-3 text-xl">📝</span> Microsoft Word (.doc, .docx)
                            </li>
                            <li class="flex items-center text-teal-800 font-medium">
                                <span class="mr-3 text-xl">🖼️</span> Images (JPG, PNG)
                            </li>
                            <li class="flex items-center text-teal-800 font-medium">
                                <span class="mr-3 text-xl">📐</span> Maximum file size: {{ \App\Models\ContentBlock::get('max_file_size', '10', 'text', 'printing') }}MB
                            </li>
                        </ul>
                    </div>
                </div>

                @auth
                    @if($userPrintJobs->count() > 0)
                        <!-- Recent Print Jobs -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                            <div class="p-6">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6">Recent Print Jobs</h3>
                                <div class="space-y-4">
                                    @foreach($userPrintJobs as $job)
                                        <div class="print-job-card border border-gray-200 rounded-xl p-4">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <p class="font-bold text-gray-900 truncate">{{ $job->original_filename }}</p>
                                                    <p class="text-sm text-gray-500">{{ $job->created_at->diffForHumans() }}</p>
                                                    <p class="text-sm text-green-600 font-bold">B${{ number_format($job->total_price, 2) }}</p>
                                                </div>
                                                <span class="text-sm px-3 py-1 rounded-full font-medium
                                                    {{ $job->status === 'uploaded' ? 'bg-blue-100 text-blue-800' : 
                                                       ($job->status === 'ready' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ $job->status_display }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </section>
</div>

    <!-- Print Job Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full mx-4 shadow-2xl transform scale-100 transition-transform">
            <div class="p-8 text-center">
                <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Print Job Created!</h3>
                <p class="text-gray-600 mb-8 text-lg" id="success-message">Your file has been uploaded and is ready to be added to your cart.</p>
                <div class="space-y-4">
                    <button id="add-to-cart-btn" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-4 px-6 rounded-2xl font-bold text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Add to Cart - <span id="final-price"></span>
                    </button>
                    <button id="upload-another-btn" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-4 px-6 rounded-2xl font-bold text-lg transition-all duration-300 border border-gray-200">
                        Upload Another File
                    </button>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        // File upload handling
        const fileInput = document.getElementById('file-input');
        const fileUploadArea = document.getElementById('file-upload-area');
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const uploadForm = document.getElementById('print-upload-form');
        let currentPrintJob = null;

        // Drag and drop functionality
        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                displayFileInfo(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                displayFileInfo(e.target.files[0]);
            }
        });

        function displayFileInfo(file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.classList.remove('hidden');
            updatePricing();
        }

        function formatFileSize(bytes) {
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            if (bytes === 0) return '0 Bytes';
            const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
        }

        function updatePricing() {
            const colorOption = document.querySelector('select[name="color_option"]').value;
            const paperType = document.querySelector('select[name="paper_type"]').value;
            const copies = parseInt(document.querySelector('input[name="copies"]').value) || 1;

            const prices = {
                'black_white': { 
                    'regular': {{ \App\Models\ContentBlock::get('price_bw_regular', '0.10', 'text', 'printing') }}, 
                    'photo': {{ \App\Models\ContentBlock::get('price_bw_photo', '0.50', 'text', 'printing') }}, 
                    'cardstock': {{ \App\Models\ContentBlock::get('price_bw_cardstock', '0.25', 'text', 'printing') }} 
                },
                'color': { 
                    'regular': {{ \App\Models\ContentBlock::get('price_color_regular', '0.25', 'text', 'printing') }}, 
                    'photo': {{ \App\Models\ContentBlock::get('price_color_photo', '1.00', 'text', 'printing') }}, 
                    'cardstock': {{ \App\Models\ContentBlock::get('price_color_cardstock', '0.50', 'text', 'printing') }} 
                }
            };

            const pricePerPage = prices[colorOption][paperType];
            const estimatedPages = 1; // Will be calculated server-side
            const totalPrice = pricePerPage * estimatedPages * copies;

            document.getElementById('price-display').textContent = `B$${totalPrice.toFixed(2)}`;
        }

        // Form submission
        uploadForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const uploadBtn = document.getElementById('upload-btn');
            const uploadText = document.getElementById('upload-text');
            const uploadLoading = document.getElementById('upload-loading');
            
            uploadBtn.disabled = true;
            uploadText.classList.add('hidden');
            uploadLoading.classList.remove('hidden');

            try {
                const formData = new FormData(uploadForm);
                const response = await fetch('{{ route("printing.upload") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    currentPrintJob = result.print_job;
                    document.getElementById('final-price').textContent = `B$${result.total_price}`;
                    document.getElementById('success-modal').classList.remove('hidden');
                    document.getElementById('success-modal').classList.add('flex');
                } else {
                    throw new Error(result.error || 'Upload failed');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: error.message,
                    confirmButtonColor: '#0d9488'
                });
            } finally {
                uploadBtn.disabled = false;
                uploadText.classList.remove('hidden');
                uploadLoading.classList.add('hidden');
            }
        });

        // Add to cart
        document.getElementById('add-to-cart-btn').addEventListener('click', async () => {
            if (!currentPrintJob) return;

            try {
                const response = await fetch(`/printing/${currentPrintJob.id}/add-to-cart`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    window.location.href = result.redirect;
                } else {
                    throw new Error(result.error || 'Failed to add to cart');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    confirmButtonColor: '#0d9488'
                });
            }
        });

        // Upload another file
        document.getElementById('upload-another-btn').addEventListener('click', () => {
            document.getElementById('success-modal').classList.add('hidden');
            document.getElementById('success-modal').classList.remove('flex');
            uploadForm.reset();
            fileInfo.classList.add('hidden');
            updatePricing();
        });

        // Initialize pricing display
        updatePricing();
    </script>
@endpush

@endsection