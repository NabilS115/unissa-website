// Footer helpers (back-to-top)
(function(){
    const btn = document.getElementById('backToTopBtn');
    if (!btn) return;

    function onScroll() {
        if (window.scrollY > 50) btn.style.display = 'block';
        else btn.style.display = 'none';
    }

    window.addEventListener('scroll', onScroll);
    // Initialize visibility
    setTimeout(onScroll, 50);
})();
