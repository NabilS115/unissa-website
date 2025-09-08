@extends('layouts.app')

@section('title', 'Homemade Pancakes Review')

@section('content')
<div class="max-w-7xl mx-auto bg-white rounded-lg shadow p-12 mt-8">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Left: Image, Description, Ratings -->
        <div class="flex-1 flex flex-col items-center lg:items-start">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80" alt="Homemade Pancakes" class="w-full max-w-lg h-96 object-cover rounded-lg border mb-6">
            <div class="w-full">
                <div class="text-3xl font-bold mb-2 text-center lg:text-left">Homemade Pancakes</div>
                <div class="font-semibold mb-1 text-center lg:text-left">Product Description</div>
                <div class="text-gray-700 text-base mb-6 text-center lg:text-left">
                    Fluffy and golden, our homemade pancakes are made with simple, wholesome ingredients. Perfectly soft on the inside with a light crisp edge, they're great for breakfast or brunch.
                </div>
            </div>
            <div class="w-full flex flex-col md:flex-row items-center md:items-end gap-8 mb-6">
                <div class="flex flex-col gap-2 w-full md:w-1/2">
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600 w-4">1</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded">
                            <div class="h-2 bg-yellow-400 rounded" style="width: 4%"></div>
                        </div>
                        <span class="text-gray-600 w-8 text-right">4</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600 w-4">2</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded">
                            <div class="h-2 bg-yellow-400 rounded" style="width: 7%"></div>
                        </div>
                        <span class="text-gray-600 w-8 text-right">7</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600 w-4">3</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded">
                            <div class="h-2 bg-yellow-400 rounded" style="width: 12%"></div>
                        </div>
                        <span class="text-gray-600 w-8 text-right">12</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600 w-4">4</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded">
                            <div class="h-2 bg-yellow-400 rounded" style="width: 34%"></div>
                        </div>
                        <span class="text-gray-600 w-8 text-right">34</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600 w-4">5</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded">
                            <div class="h-2 bg-yellow-400 rounded" style="width: 50%"></div>
                        </div>
                        <span class="text-gray-600 w-8 text-right">50</span>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center w-full md:w-1/2">
                    <div class="text-5xl font-bold text-gray-700 mb-2">4.11</div>
                    <div class="flex items-center mb-1">
                        <span class="text-yellow-400 text-2xl">★★★★☆</span>
                    </div>
                    <div class="text-gray-500 text-sm text-center">Average rating<br>Based on 107 rating</div>
                </div>
            </div>
            <div class="flex justify-center lg:justify-start mb-6 w-full">
                <button id="write-review-btn" class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold px-6 py-2 rounded-lg shadow transition">Write a review!</button>
            </div>
        </div>
        <!-- Right: Reviews -->
        <div class="flex-1">
            <hr class="my-6 lg:hidden">
            <div class="space-y-6" id="reviews-list">
                <div class="bg-white rounded-lg border shadow p-4 flex gap-4 items-start">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Danish Naufal" class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <span class="font-semibold">Danish Naufal</span>
                                <span class="text-yellow-400 ml-2 text-sm">★ 4.9</span>
                            </div>
                            <span class="text-gray-400 text-xs">10 Sept</span>
                        </div>
                        <div class="text-gray-700 mb-2">The best pancake that i ever eaten for the past 10 years</div>
                        <div class="flex items-center gap-4 text-gray-500 text-sm">
                            <span>👍 4</span>
                            <a href="#" class="underline">Report</a>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border shadow p-4 flex gap-4 items-start">
                    <img src="https://randomuser.me/api/portraits/men/33.jpg" alt="Hitstonecold Ayeeee" class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <span class="font-semibold">Hitstonecold Ayeeee</span>
                                <span class="text-yellow-400 ml-2 text-sm">★ 4.1</span>
                            </div>
                            <span class="text-gray-400 text-xs">23 Aug</span>
                        </div>
                        <div class="text-gray-700 mb-2">It is worth the price. not much too say</div>
                        <div class="flex items-center gap-4 text-gray-500 text-sm">
                            <span>👍 8</span>
                            <a href="#" class="underline">Report</a>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border shadow p-4 flex gap-4 items-start">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Aisyah Rahman" class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <span class="font-semibold">Aisyah Rahman</span>
                                <span class="text-yellow-400 ml-2 text-sm">★ 5.0</span>
                            </div>
                            <span class="text-gray-400 text-xs">2 Jul</span>
                        </div>
                        <div class="text-gray-700 mb-2">Absolutely delicious! The pancakes are fluffy and taste just like home.</div>
                        <div class="flex items-center gap-4 text-gray-500 text-sm">
                            <span>👍 12</span>
                            <a href="#" class="underline">Report</a>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border shadow p-4 flex gap-4 items-start">
                    <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Nurul Huda" class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <span class="font-semibold">Nurul Huda</span>
                                <span class="text-yellow-400 ml-2 text-sm">★ 4.7</span>
                            </div>
                            <span class="text-gray-400 text-xs">15 Jun</span>
                        </div>
                        <div class="text-gray-700 mb-2">Very tasty and soft pancakes. My kids loved them!</div>
                        <div class="flex items-center gap-4 text-gray-500 text-sm">
                            <span>👍 6</span>
                            <a href="#" class="underline">Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Write Review Modal -->
    <div id="review-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
            <button id="close-review-modal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
            <h3 class="text-xl font-bold mb-4">Write a Review</h3>
            <form id="review-form">
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Rating</label>
                    <select name="rating" class="w-full border rounded px-3 py-2">
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Review</label>
                    <textarea name="review" class="w-full border rounded px-3 py-2" rows="4" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="cancel-review" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-yellow-400 hover:bg-yellow-500 text-white font-semibold">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Modal logic
    const modal = document.getElementById('review-modal');
    document.getElementById('write-review-btn').onclick = () => { modal.classList.remove('hidden'); };
    document.getElementById('close-review-modal').onclick = () => { modal.classList.add('hidden'); };
    document.getElementById('cancel-review').onclick = () => { modal.classList.add('hidden'); };

    document.getElementById('review-form').onsubmit = function(e) {
        e.preventDefault();
        const rating = this.rating.value;
        const reviewText = this.review.value;
        const reviewsList = document.getElementById('reviews-list');
        const newReview = document.createElement('div');
        newReview.className = "bg-white rounded-lg border shadow p-4 flex gap-4 items-start";
        // Use user's profile photo from blade variable
        const profilePhoto = "{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}";
        newReview.innerHTML = `
            <img src="${profilePhoto}" alt="You" class="w-12 h-12 rounded-full object-cover">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-1">
                    <div>
                        <span class="font-semibold">You</span>
                        <span class="text-yellow-400 ml-2 text-sm">★ ${rating}</span>
                    </div>
                    <span class="text-gray-400 text-xs">${new Date().toLocaleDateString()}</span>
                </div>
                <div class="text-gray-700 mb-2">${reviewText}</div>
                <div class="flex items-center gap-4 text-gray-500 text-sm">
                    <span>👍 0</span>
                    <a href="#" class="underline">Report</a>
                </div>
            </div>
        `;
        reviewsList.prepend(newReview);
        modal.classList.add('hidden');
        this.reset();
    };
</script>
@endsection
