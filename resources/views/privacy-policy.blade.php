@extends('layouts.app')

@section('title', 'Unissa Cafe - Privacy Policy')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Privacy Policy</h1>
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
                
                <!-- Introduction -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">1. Introduction</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </section>

                <!-- Information We Collect -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">2. Information We Collect</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4 space-y-1">
                        <li>Nemo enim ipsam voluptatem quia voluptas sit aspernatur</li>
                        <li>Neque porro quisquam est, qui dolorem ipsum quia dolor</li>
                        <li>Ut enim ad minima veniam, quis nostrum exercitationem</li>
                        <li>At vero eos et accusamus et iusto odio dignissimos</li>
                    </ul>
                </section>

                <!-- How We Use Information -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">3. How We Use Your Information</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
                    </p>
                </section>

                <!-- Information Sharing -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">4. Information Sharing and Disclosure</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.
                    </p>
                </section>

                <!-- Data Security -->
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">5. Data Security</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                    </p>
                </section>

                <!-- Contact Information -->
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">6. Contact Us</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. For questions about this privacy policy, please contact us:
                    </p>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">UNISSA (University)</h4>
                                <p class="text-sm text-gray-600">
                                    Email: privacy@unissa.edu.my<br>
                                    Phone: +60 3-1234 5678
                                </p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Tijarah Co Sdn Bhd</h4>
                                <p class="text-sm text-gray-600">
                                    Email: privacy@tijarah.com<br>
                                    Business Hours: 8:00 AM - 6:00 PM (Mon-Fri)
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

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