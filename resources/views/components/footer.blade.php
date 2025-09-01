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
                <a href="#" class="text-white" title="Instagram">
                    <svg width="24" height="24" viewBox="0 0 448 512" fill="currentColor"><path d="M224.1 141c-63.6 0-115.1 51.5-115.1 115.1S160.5 371.3 224.1 371.3 339.2 319.8 339.2 256.1 287.7 141 224.1 141zm0 186.6c-39.6 0-71.7-32.1-71.7-71.7s32.1-71.7 71.7-71.7 71.7 32.1 71.7 71.7-32.1 71.7-71.7 71.7zm146.4-194.3c0 14.9-12.1 27-27 27s-27-12.1-27-27 12.1-27 27-27 27 12.1 27 27zm76.1 27.2c-1.7-35.3-9.9-66.7-36.2-92.9S388.6 9.7 353.3 8c-35.3-1.7-138.6-1.7-173.9 0-35.3 1.7-66.7 9.9-92.9 36.2S9.7 123.4 8 158.7c-1.7 35.3-1.7 138.6 0 173.9 1.7 35.3 9.9 66.7 36.2 92.9s57.6 34.5 92.9 36.2c35.3 1.7 138.6 1.7 173.9 0 35.3-1.7 66.7-9.9 92.9-36.2s34.5-57.6 36.2-92.9c1.7-35.3 1.7-138.6 0-173.9zM398.8 388c-7.8 19.6-22.9 34.7-42.5 42.5-29.5 11.7-99.5 9-132.3 9s-102.7 2.6-132.3-9c-19.6-7.8-34.7-22.9-42.5-42.5-11.7-29.5-9-99.5-9-132.3s-2.6-102.7 9-132.3c7.8-19.6 22.9-34.7 42.5-42.5 29.5-11.7 99.5-9 132.3-9s102.7-2.6 132.3 9c19.6 7.8 34.7 22.9 42.5 42.5 11.7 29.5 9 99.5 9 132.3s2.6 102.7-9 132.3z"/></svg>
                </a>
                <a href="#" class="text-white" title="Facebook">
                    <svg width="24" height="24" viewBox="0 0 320 512" fill="currentColor"><path d="M279.14 288l14.22-92.66h-88.91V127.89c0-25.35 12.42-50.06 52.24-50.06H293V6.26S259.5 0 225.36 0c-73.22 0-121.36 44.38-121.36 124.72V195.3H22.89V288h81.11v224h100.2V288z"/></svg>
                </a>
                <a href="#" class="text-white" title="X">
                    <svg viewBox="0 0 48 48" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="24" cy="24" r="24" fill="white"/>
                        <path d="M33.6 13.2L24 22.8L14.4 13.2H11.2L21.6 24L11.2 34.8H14.4L24 25.2L33.6 34.8H36.8L26.4 24L36.8 13.2H33.6Z" fill="#0d9488"/>
                    </svg>
                </a>
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
