@extends('layouts.app')

@section('title', 'Unissa Cafe - Terms of Service')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Terms of Service</h1>
                <p class="text-gray-600">Last updated: {{ date('F j, Y') }}</p>
            </div>
            
            <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-teal-500 rounded-full mr-2"></div>
                    Universiti Islam Sultan Sharif Ali (UNISSA) - Brunei
                </div>
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                    Tijarah Co Sdn Bhd & Unissa Cafe
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-xl shadow-sm p-8">
            <div class="prose prose-gray max-w-none">
                
                <!-- Agreement and Acceptance -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">1. Agreement and Acceptance</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </section>

                <!-- Description of Services -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">2. Description of Services</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 space-y-1">
                        <li>Nemo enim ipsam voluptatem quia voluptas sit aspernatur</li>
                        <li>Neque porro quisquam est, qui dolorem ipsum quia dolor</li>
                        <li>Ut enim ad minima veniam, quis nostrum exercitationem</li>
                        <li>At vero eos et accusamus et iusto odio dignissimos</li>
                    </ul>
                </section>

                <!-- User Accounts -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">3. User Accounts and Responsibilities</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
                    </p>
                </section>

                <!-- Ordering and Payment -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">4. Ordering and Payment Terms</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.
                    </p>
                </section>

                <!-- Refund Policy -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">5. Refund and Cancellation Policy</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                    </p>
                </section>

                <!-- Acceptable Use -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">6. Acceptable Use Policy</h2>
                    <p class="text-gray-700 leading-relaxed">
                        At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias.
                    </p>
                </section>

                <!-- Food Safety Notice -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">7. Food Safety Information</h2>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-yellow-800">Important Notice</h4>
                                <p class="text-yellow-700 text-sm mt-1">
                                    Please inform us of any food allergies when placing your order. All food is prepared following halal standards.
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Halal certification and allergen information available upon request.
                    </p>
                </section>

                <!-- Contact Information -->
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">8. Contact Information</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Lorem ipsum dolor sit amet. If you have questions about these terms, please contact us:
                    </p>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">UNISSA (University)</h4>
                                <p class="text-sm text-gray-600">
                                    Email: legal@unissa.edu.my<br>
                                    Phone: +60 6-798 8000
                                </p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Tijarah Co Sdn Bhd</h4>
                                <p class="text-sm text-gray-600">
                                    Email: legal@tijarah.com<br>
                                    Phone: +60 3-1234 5678<br>
                                    Customer Service: support@unissacafe.com
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Effective Date -->
                <div class="border-t pt-6 mt-8">
                    <p class="text-sm text-gray-500 text-center">
                        These Terms of Service are effective as of {{ date('F j, Y') }}.
                    </p>
                </div>

            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-8">
            <a href="/" class="inline-flex items-center px-6 py-3 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection