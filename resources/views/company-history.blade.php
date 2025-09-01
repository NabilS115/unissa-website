@extends('layouts.app')

@section('title', 'Company History & Values')

@section('content')
   
            <!-- Banner Section -->
            <section class="w-full h-72 flex flex-col items-center justify-center mb-8 relative">
                <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80" alt="Company Banner" class="absolute inset-0 w-full h-full object-cover opacity-70">
                <div class="relative z-10 text-center">
                    <h2 class="text-5xl font-extrabold text-white drop-shadow-lg mb-4">Our Story & Values</h2>
                    <p class="text-xl text-white drop-shadow-md">Learn about our journey and what drives us.</p>
                </div>
            </section>

            <div class="w-full py-12 px-6" style="background-color: #14b8a6;">
                <h1 class="text-4xl font-extrabold text-white mb-6">Our History</h1>
                <p class="text-lg text-white mb-8">
                    Founded in 1985 in a humble kitchen on the outskirts of Bandar Seri Begawan, our company began as a family-run eatery serving traditional recipes passed down through generations. The aroma of freshly cooked nasi lemak and the warmth of our hospitality quickly made us a local favorite. As word spread, so did our ambitions. By the early 1990s, we expanded to new locations, introducing innovative dishes inspired by both local heritage and global flavors.
                </p>
                <p class="text-lg text-white mb-8">
                    The turn of the millennium marked a new era: we embraced technology, launched our first website, and began sourcing sustainable ingredients from trusted farmers. Our commitment to community was unwavering—we sponsored food drives, supported local schools, and hosted annual cultural festivals celebrating Bruneian cuisine. Through challenges and triumphs, our team grew into a close-knit family, united by a passion for food and service.
                </p>
                <p class="text-lg text-white mb-8">
                    Today, our company stands as a beacon of culinary excellence in Brunei and beyond. We continue to innovate, blending tradition with modernity, and remain dedicated to our founding values. Every meal we serve is a tribute to our journey—a story of resilience, creativity, and the joy of bringing people together around the table.
                </p>
                
            </div>

    
        <h2 class=" font-extrabold text-teal-600 text-4xl text-center">Our Values</h2>
            <!-- Integrity -->
            <div class="w-full h-112 flex items-center bg-teal-600 rounded-none px-0">
                <div class="w-1/2 h-full flex items-center justify-center">
                    <img src="/images/integrity.jpeg" alt="Integrity" class="h-full w-full object-cover rounded-none" />
                </div>
                <div class="w-1/2 h-full flex flex-col justify-center text-left px-8">
                    <span class="font-semibold text-white text-2xl">Integrity</span>
                    <p class="text-white mt-2">We uphold the highest standards of honesty and transparency in all our operations.</p>
                </div>
            </div>
            <!-- Quality -->
            <div class="w-full h-112 flex items-center bg-white rounded-none px-0">
                <div class="w-1/2 h-full flex flex-col justify-center text-left px-8">
                    <span class="font-semibold text-teal-600 text-2xl">Quality</span>
                    <p class="text-teal-700 mt-2">Our commitment to excellence is reflected in every dish we serve.</p>
                </div>
                <div class="w-1/2 h-full flex items-center justify-center">
                    <img src="/images/quality.png" alt="Quality" class="h-full w-full object-cover rounded-none" />
                </div>
            </div>
            <!-- Innovation -->
            <div class="w-full h-112 flex items-center bg-teal-600 rounded-none px-0">
                <div class="w-1/2 h-full flex items-center justify-center">
                    <img src="/images/innovation.png" alt="Innovation" class="h-full w-full object-cover rounded-none" />
                </div>
                <div class="w-1/2 h-full flex flex-col justify-center text-left px-8">
                    <span class="font-semibold text-white text-2xl">Innovation</span>
                    <p class="text-white mt-2">We embrace creativity and forward-thinking to continually improve our offerings.</p>
                </div>
            </div>
            <!-- Community -->
            <div class="w-full h-112 flex items-center bg-white rounded-none px-0">
                <div class="w-1/2 h-full flex flex-col justify-center text-left px-8">
                    <span class="font-semibold text-teal-600 text-2xl">Community</span>
                    <p class="text-teal-700 mt-2">We believe in giving back and fostering strong relationships with our customers and partners.</p>
                </div>
                <div class="w-1/2 h-full flex items-center justify-center">
                    <img src="/images/community.jpg" alt="Community" class="h-full w-full object-cover rounded-none" />
                </div>
            </div>
            <!-- Sustainability -->
            <div class="w-full h-112 flex items-center bg-teal-600 rounded-none px-0">
                <div class="w-1/2 h-full flex items-center justify-center">
                    <img src="/images/sustainability.png" alt="Sustainability" class="h-full w-full object-cover rounded-none" />
                </div>
                <div class="w-1/2 h-full flex flex-col justify-center text-left px-8">
                    <span class="font-semibold text-white text-2xl">Sustainability</span>
                    <p class="text-white mt-2">We strive to minimize our environmental impact and promote responsible practices.</p>
                </div>
            </div>
        </div>
@endsection