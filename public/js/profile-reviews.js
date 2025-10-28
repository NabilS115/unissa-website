// Review carousel and read-more handlers for profile page
(function(){
    const P = window.__profileReviews || {};
    let currentReview = 0;
    const totalReviews = parseInt(P.totalReviews || 0, 10) || 0;

    function setTrackWidths() {
        const track = document.getElementById('reviews-track');
        if (!track || totalReviews <= 1) return;
        track.style.width = `${totalReviews * 100}%`;
        track.querySelectorAll('.w-full').forEach((slide) => {
            slide.style.width = `${100 / totalReviews}%`;
        });
    }

    function updateReviewDots() {
        document.querySelectorAll('.review-dot').forEach((dot, index) => {
            if (index === currentReview) dot.className = 'review-dot w-8 h-3 rounded-full bg-teal-500 transition-all duration-200';
            else dot.className = 'review-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 transition-all duration-200';
        });
    }

    function moveReview(dir) {
        if (totalReviews <= 1) return;
        const track = document.getElementById('reviews-track');
        currentReview = (currentReview + dir + totalReviews) % totalReviews;
        const translateX = -(currentReview * (100 / totalReviews));
        if (track) track.style.transform = `translateX(${translateX}%)`;
        updateReviewDots();
    }

    function goToReview(index) {
        if (totalReviews <= 1) return;
        const track = document.getElementById('reviews-track');
        currentReview = index;
        const translateX = -(currentReview * (100 / totalReviews));
        if (track) track.style.transform = `translateX(${translateX}%)`;
        updateReviewDots();
    }

    function initializeReadMoreButtons() {
        document.querySelectorAll('.read-more-btn').forEach(btn => {
            btn.addEventListener('click', function(e){
                e.preventDefault(); e.stopPropagation();
                const reviewId = this.getAttribute('data-review-id');
                const truncatedText = document.querySelector(`.review-text-${reviewId}`);
                const fullText = document.querySelector(`.review-full-${reviewId}`);
                const buttonText = this.querySelector('span') || this;
                const icon = this.querySelector('svg');
                if (buttonText && buttonText.textContent.includes('Read more')) {
                    if (truncatedText) truncatedText.classList.add('hidden');
                    if (fullText) fullText.classList.remove('hidden');
                    if (buttonText) buttonText.textContent = 'Read less';
                    if (icon) icon.style.transform = 'rotate(180deg)';
                } else {
                    if (truncatedText) truncatedText.classList.remove('hidden');
                    if (fullText) fullText.classList.add('hidden');
                    if (buttonText) buttonText.textContent = 'Read more';
                    if (icon) icon.style.transform = 'rotate(0deg)';
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function(){
        if (totalReviews > 1) setTrackWidths();
        initializeReadMoreButtons();
        // expose navigation helpers used by inline onclick attributes
        window.moveReview = moveReview;
        window.goToReview = goToReview;
        window.updateReviewDots = updateReviewDots;
    });

})();
