// review.js - extracted from review.blade.php
// Expects window.__review = { csrf, productId, isAuthenticated, currentUserId }

(function() {
    const cfg = window.__review || {};
    const csrf = cfg.csrf || '';
    const productId = cfg.productId;
    const isAuthenticated = !!cfg.isAuthenticated;

    // Back button helper
    window.goBack = function() {
        const previousPage = sessionStorage.getItem('previousPage');
        const previousPageTitle = sessionStorage.getItem('previousPageTitle');

        if (previousPage) {
            sessionStorage.removeItem('previousPage');
            sessionStorage.removeItem('previousPageTitle');
            window.location.href = previousPage;
            return;
        }

        const savedState = sessionStorage.getItem('catalogState');
        if (savedState) {
            try {
                const state = JSON.parse(savedState);
                if (state.source === 'homepage') {
                    sessionStorage.removeItem('catalogState');
                    window.location.href = '/';
                    return;
                }
                if (state.source === 'catalog' || state.sourcePage === '/catalog') {
                    sessionStorage.setItem('restoreCatalogState', savedState);
                    window.location.href = '/catalog';
                    return;
                }
                if (state.sourcePage) {
                    sessionStorage.setItem('restoreCatalogState', savedState);
                    window.location.href = state.sourcePage;
                    return;
                }
                sessionStorage.setItem('restoreCatalogState', savedState);
                window.location.href = '/catalog';
            } catch (e) {
                console.error('Error parsing catalog state:', e);
                window.location.href = '/catalog';
            }
        } else if (document.referrer && document.referrer !== window.location.href) {
            window.history.back();
        } else {
            window.location.href = '/catalog';
        }
    };

    // Update back-button text on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        const previousPage = sessionStorage.getItem('previousPage');
        const previousPageTitle = sessionStorage.getItem('previousPageTitle');
        const savedState = sessionStorage.getItem('catalogState');
        const backButtonText = document.getElementById('back-button-text');
        if (!backButtonText) return;

        if (previousPage && previousPageTitle) {
            if (previousPageTitle.includes('UNISSA') && previousPage.includes(window.location.origin)) {
                backButtonText.textContent = 'Back to Homepage';
            } else {
                backButtonText.textContent = 'Back to Previous Page';
            }
        } else if (savedState) {
            try {
                const state = JSON.parse(savedState);
                if (state.source === 'homepage') backButtonText.textContent = 'Back to Homepage';
                else if (state.source === 'catalog') {
                    const tabName = state.tab === 'food' ? 'Food' : 'Merchandise';
                    backButtonText.textContent = `Back to ${tabName} Catalog`;
                } else backButtonText.textContent = 'Back to Catalog';
            } catch (e) {
                console.error('Error parsing saved state:', e);
            }
        }
    });

    // Modal controls
    const modal = document.getElementById('review-modal');
    const writeReviewBtn = document.getElementById('write-review-btn');
    if (writeReviewBtn && modal) writeReviewBtn.onclick = () => { modal.classList.remove('hidden'); };

    const closeReviewModal = document.getElementById('close-review-modal');
    const cancelReview = document.getElementById('cancel-review');
    if (closeReviewModal && modal) closeReviewModal.onclick = () => { modal.classList.add('hidden'); };
    if (cancelReview && modal) cancelReview.onclick = () => { modal.classList.add('hidden'); };

    // Review form submit
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.onsubmit = async function(e) {
            e.preventDefault();
            const rating = this.rating.value;
            const reviewText = this.review.value;
            if (!rating || !reviewText.trim()) {
                alert('Please provide both a rating and review text.');
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.textContent : null;
            if (submitBtn) { submitBtn.textContent = 'Submitting...'; submitBtn.disabled = true; }

            try {
                const response = await fetch(`/review/${productId}/add`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ rating: parseInt(rating), review: reviewText.trim(), product_id: productId })
                });

                const data = await response.json().catch(() => ({}));
                if (response.ok) {
                    alert('Review submitted successfully!');
                    if (modal) modal.classList.add('hidden');
                    window.location.reload();
                } else {
                    console.error('Server response:', data);
                    alert(data.message || 'Failed to submit review. Please try again.');
                }
            } catch (error) {
                console.error('Network error:', error);
                alert('Network error. Please check your connection and try again.');
            } finally {
                if (submitBtn) { submitBtn.textContent = originalText; submitBtn.disabled = false; }
            }
        };
    }

    // Delete review buttons
    document.querySelectorAll('.delete-review-btn').forEach(btn => {
        btn.onclick = async function(e) {
            e.preventDefault();
            if (!confirm('Delete this review?')) return;
            const reviewId = this.getAttribute('data-id');
            if (!reviewId) { alert('Review ID not found.'); return; }
            try {
                const res = await fetch(`/reviews/${reviewId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
                if (res.ok) {
                    const reviewElement = document.querySelector(`[data-review-id="${reviewId}"]`);
                    if (reviewElement) reviewElement.remove();
                    window.location.reload();
                } else {
                    const errorData = await res.json().catch(() => ({}));
                    alert(errorData.message || 'Failed to delete review.');
                }
            } catch (error) {
                console.error('Delete review error:', error);
                alert('Network error occurred.');
            }
        };
    });

    // Read more toggles
    document.querySelectorAll('.read-more-btn').forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
            const reviewId = this.getAttribute('data-review-id');
            const truncatedText = document.querySelector(`.review-text-${reviewId}`);
            const fullText = document.querySelector(`.review-full-${reviewId}`);
            if (!truncatedText || !fullText) return;
            if (this.textContent === 'Read more') {
                truncatedText.classList.add('hidden');
                fullText.classList.remove('hidden');
                this.textContent = 'Read less';
            } else {
                truncatedText.classList.remove('hidden');
                fullText.classList.add('hidden');
                this.textContent = 'Read more';
            }
        };
    });

    // Star rating controls
    const starBtns = Array.from(document.querySelectorAll('.star-btn'));
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');
    const ratingTexts = ['Poor','Fair','Good','Very Good','Excellent'];

    if (starBtns.length > 0 && ratingInput && ratingText) {
        starBtns.forEach((btn, index) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const rating = index + 1;
                ratingInput.value = rating;
                ratingText.textContent = ratingTexts[index];
                starBtns.forEach((star, i) => {
                    star.classList.toggle('text-yellow-400', i < rating);
                    star.classList.toggle('text-gray-300', i >= rating);
                });
            });
            btn.addEventListener('mouseenter', () => {
                const rating = index + 1;
                starBtns.forEach((star, i) => star.classList.toggle('text-yellow-400', i < rating));
            });
            btn.addEventListener('mouseleave', () => {
                const currentRating = parseInt(ratingInput.value) || 5;
                starBtns.forEach((star, i) => star.classList.toggle('text-yellow-400', i < currentRating));
            });
        });
        starBtns.forEach((star, i) => { if (i < 5) { star.classList.add('text-yellow-400'); star.classList.remove('text-gray-300'); } });
    }

    // Helpful button
    document.querySelectorAll('.helpful-btn').forEach(btn => {
        btn.onclick = async function(e) {
            e.preventDefault();
            if (!isAuthenticated) { alert('Please login to mark reviews as helpful'); return; }
            const reviewId = this.getAttribute('data-review-id');
            if (!reviewId) { console.warn('Review ID not found'); return; }
            const countSpan = this.querySelector('.helpful-count');
            if (!countSpan) { console.warn('Helpful count span not found'); return; }
            try {
                const res = await fetch(`/reviews/${reviewId}/helpful`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' } });
                const data = await res.json().catch(() => ({}));
                if (res.ok) {
                    countSpan.textContent = `(${data.helpful_count || 0})`;
                    if (data.action === 'added') { this.classList.add('text-blue-600'); this.classList.remove('text-gray-500'); }
                    else { this.classList.remove('text-blue-600'); this.classList.add('text-gray-500'); }
                } else {
                    console.error('Helpful button error:', data);
                    alert(data.message || 'Failed to mark as helpful');
                }
            } catch (error) {
                console.error('Helpful button network error:', error);
                alert('Network error. Please try again.');
            }
        };
    });

})();
