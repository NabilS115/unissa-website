    <button id="backToTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'});" class="fixed bottom-56 right-6 px-4 py-2 rounded bg-[#0d9488] text-white font-semibold border border-white shadow hover:bg-white hover:text-[#0d9488] transition z-50" style="display:none;">
        ↑
    </button>
    <script>
        const backToTopBtn = document.getElementById('backToTopBtn');
        window.addEventListener('scroll', function() {
            const scrollY = window.scrollY;
            const windowHeight = window.innerHeight;
            const bodyHeight = document.body.offsetHeight;
            if (scrollY + windowHeight >= bodyHeight - 10) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
    </script>

<footer class="w-full bg-teal-600 text-white py-8 px-4 flex flex-col md:flex-row items-center justify-between gap-8" style="background-color:#0d9488;">
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 bg-red-600 border-4 border-black flex items-center justify-center mr-2"></div>
        <div class="flex flex-col">
            <span class="font-bold text-lg">Something<br>Company</span>
        </div>
    </div>
    <div class="flex flex-col md:flex-row gap-8 w-full md:w-auto justify-center">
        <div class="bg-[#007070] bg-opacity-80 rounded-2xl px-8 py-6 flex flex-col justify-center min-w-[160px]">
            <a href="/" class="text-white mb-2 hover:underline">Home</a>
            <a href="/food-list" class="text-white mb-2 hover:underline">Catalog</a>
            <a href="/company-history" class="text-white mb-2 hover:underline">About</a>
            <a href="/contact" class="text-white hover:underline">Contact</a>
        </div>
        <div class="bg-[#007070] bg-opacity-80 rounded-2xl px-8 py-6 flex flex-col items-center min-w-[200px]">
            <span class="font-bold mb-2">Follow Us</span>
            <div class="flex gap-4 mb-4">
                <a href="#" class="text-white"><svg width="24" height="24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg></a>
                <a href="#" class="text-white"><svg width="24" height="24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg></a>
                <a href="#" class="text-white"><svg width="24" height="24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg></a>
                <a href="#" class="text-white"><svg width="24" height="24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg></a>
            </div>
            <div class="flex items-center gap-2 mb-2">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm2 0v.01l8 5.99 8-5.99V6H4zm16 2.24l-8 5.99-8-5.99V18h16V8.24z"/></svg>
                <a href="mailto:somthing@gmail.com" class="underline">something@gmail.com</a>
            </div>
            <div class="flex items-center gap-2">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 3A2.5 2.5 0 004 5.5v13A2.5 2.5 0 006.5 21h11a2.5 2.5 0 002.5-2.5v-13A2.5 2.5 0 0017.5 3h-11zm0 2h11A.5.5 0 0118 5.5v13a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5v-13A.5.5 0 016.5 5zM12 19a1 1 0 110-2 1 1 0 010 2z"/></svg>
                <span>+673 928 0182</span>
            </div>
        </div>
    </div>
</footer>
