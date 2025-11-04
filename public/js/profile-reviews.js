// Review carousel and read-more handlers for profile page
(function(){
    const P = window.__profileReviews || {};
    let currentReview = 0;
    const totalReviews = parseInt(P.totalReviews || 0, 10) || 0;

    function setTrackWidths() {
        const track = document.getElementById('reviews-track');
        const carousel = document.getElementById('reviews-carousel');
        if (!track || !carousel || totalReviews <= 1) return;
        
        // Set track width to accommodate all reviews
        track.style.width = `${totalReviews * 100}%`;
        track.style.display = 'flex';
        
        // Set each slide to take proper width - CSS media queries will handle the responsive behavior
        track.querySelectorAll('.w-full, [class*="w-full"]').forEach((slide) => {
            slide.style.width = `${100 / totalReviews}%`;
            slide.style.flexShrink = '0';
        });
        
        // Ensure proper initial position
        track.style.transform = 'translateX(0%)';
        track.style.transition = 'transform 0.5s ease-in-out';
    }

    function updateReviewDots() {
        document.querySelectorAll('.review-dot').forEach((dot, index) => {
            if (index === currentReview) {
                dot.className = 'review-dot w-8 h-3 rounded-full bg-teal-500 transition-all duration-200';
                dot.style.width = '24px';
                dot.style.height = '12px';
            } else {
                dot.className = 'review-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 transition-all duration-200';
                dot.style.width = '12px';
                dot.style.height = '12px';
            }
        });
    }

    function moveReview(dir) {
        if (totalReviews <= 1) return;
        const track = document.getElementById('reviews-track');
        if (!track) return;
        
        currentReview = (currentReview + dir + totalReviews) % totalReviews;
        
        // Check if mobile
        const isMobile = window.innerWidth <= 768;
        let translateX;
        
        if (isMobile) {
            // Mobile: move by 100% per review
            translateX = -(currentReview * 100);
        } else {
            // Desktop: original calculation
            translateX = -(currentReview * (100 / totalReviews));
        }
        
        track.style.transform = `translateX(${translateX}%)`;
        updateReviewDots();
    }

    function goToReview(index) {
        if (totalReviews <= 1 || index < 0 || index >= totalReviews) return;
        const track = document.getElementById('reviews-track');
        if (!track) return;
        
        currentReview = index;
        
        // Check if mobile
        const isMobile = window.innerWidth <= 768;
        let translateX;
        
        if (isMobile) {
            // Mobile: move by 100% per review
            translateX = -(currentReview * 100);
        } else {
            // Desktop: original calculation
            translateX = -(currentReview * (100 / totalReviews));
        }
        
        track.style.transform = `translateX(${translateX}%)`;
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

    // Initialize carousel when DOM is ready
    function initCarousel() {
        if (totalReviews > 1) {
            setTrackWidths();
            updateReviewDots();
        }
        initializeReadMoreButtons();
    }

    document.addEventListener('DOMContentLoaded', initCarousel);
    
    // Also initialize on window load and resize for mobile compatibility
    window.addEventListener('load', initCarousel);
    window.addEventListener('resize', function() {
        setTimeout(initCarousel, 100);
    });
    
    // expose navigation helpers used by inline onclick attributes
    window.moveReview = moveReview;
    window.goToReview = goToReview;
    window.updateReviewDots = updateReviewDots;
    window.__reviewCarousel = {
        moveReview: moveReview,
        goToReview: goToReview,
        updateReviewDots: updateReviewDots,
        initCarousel: initCarousel
    };

})();
