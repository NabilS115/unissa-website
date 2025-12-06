(() => {
  function safe(fn) {
    try { fn(); } catch (e) { console.error(e); }
  }

  function getBootstrap() {
    return window.__productDetail || {};
  }

  document.addEventListener('DOMContentLoaded', () => {
    const bs = getBootstrap();

    // ----- Edit Review Modal Logic -----
    safe(() => {
      const editReviewModal = document.getElementById('edit-review-modal');
      const closeEditReviewModal = document.getElementById('close-edit-review-modal');
      const cancelEditReview = document.getElementById('cancel-edit-review');
      const editReviewForm = document.getElementById('edit-review-form');
      const editStarBtns = document.querySelectorAll('.edit-star-btn');
      const editRatingInput = document.getElementById('edit-rating-input');
      const editRatingText = document.getElementById('edit-rating-text');
      const editReviewTextarea = document.getElementById('edit-review-textarea');
      const editReviewIdInput = document.getElementById('edit-review-id');
      const editRatingTexts = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

      document.querySelectorAll('.edit-review-btn').forEach(btn => {
        btn.onclick = function(e) {
          e.preventDefault();
          const reviewId = this.getAttribute('data-id') || this.getAttribute('data-review-id') || this.dataset.reviewId;
          const rating = parseInt(this.getAttribute('data-rating') || this.dataset.rating || 5, 10);
          const review = this.getAttribute('data-review') || this.dataset.review || '';
          if (editReviewModal && editReviewIdInput && editRatingInput && editReviewTextarea) {
            editReviewIdInput.value = reviewId;
            editRatingInput.value = rating;
            editReviewTextarea.value = review;
            editStarBtns.forEach((star, i) => {
              if (i < rating) {
                star.classList.add('text-yellow-400');
                star.classList.remove('text-gray-300');
              } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
              }
            });
            if (editRatingText) editRatingText.textContent = editRatingTexts[rating-1];
            editReviewModal.style.display = 'flex';
          }
        };
      });

      if (closeEditReviewModal && editReviewModal) {
        closeEditReviewModal.onclick = () => { editReviewModal.style.display = 'none'; };
      }
      if (cancelEditReview && editReviewModal) {
        cancelEditReview.onclick = () => { editReviewModal.style.display = 'none'; };
      }

      if (editStarBtns.length > 0 && editRatingInput && editRatingText) {
        editStarBtns.forEach((btn, index) => {
          btn.addEventListener('click', (e) => {
            e.preventDefault();
            const rating = index + 1;
            editRatingInput.value = rating;
            editRatingText.textContent = editRatingTexts[index];
            editStarBtns.forEach((star, i) => {
              if (i < rating) {
                star.classList.add('text-yellow-400');
                star.classList.remove('text-gray-300');
              } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
              }
            });
          });
          btn.addEventListener('mouseenter', () => {
            const rating = index + 1;
            editStarBtns.forEach((star, i) => {
              if (i < rating) {
                star.classList.add('text-yellow-400');
                star.classList.remove('text-gray-300');
              } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
              }
            });
          });
          btn.addEventListener('mouseleave', () => {
            const currentRating = parseInt(editRatingInput.value) || 5;
            editStarBtns.forEach((star, i) => {
              if (i < currentRating) {
                star.classList.add('text-yellow-400');
                star.classList.remove('text-gray-300');
              } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
              }
            });
          });
        });
      }

      if (editReviewForm) {
        editReviewForm.onsubmit = async function(e) {
          e.preventDefault();
          const reviewId = editReviewIdInput.value;
          const rating = editRatingInput.value;
          const reviewText = editReviewTextarea.value;
          if (!reviewId || !rating || !reviewText.trim()) {
            alert('Please provide both a rating and review text.');
            return;
          }
          const submitBtn = this.querySelector('button[type="submit"]');
          const originalText = submitBtn ? submitBtn.textContent : '';
          if (submitBtn) { submitBtn.textContent = 'Updating...'; submitBtn.disabled = true; }
          try {
            const formData = new FormData();
            formData.append('_token', bs.csrfToken || '');
            formData.append('_method', 'PUT');
            formData.append('rating', parseInt(rating));
            formData.append('review', reviewText.trim());
            const response = await fetch(`/reviews/${reviewId}`, { method: 'POST', body: formData });
            if (response.ok) { if (editReviewModal) { editReviewModal.style.display = 'none'; } window.location.reload(); } else { const text = await response.text(); console.error('Server response:', text); alert('Failed to update review. Please try again.'); }
          } catch (error) { console.error('Network error:', error); alert('Network error. Please check your connection and try again.'); }
          finally { if (submitBtn) { submitBtn.textContent = originalText; submitBtn.disabled = false; } }
        };
      }
    });

    // ----- Write Review Modal / Submission -----
    safe(() => {
      window.goBack = function() {
        if (window.history.length > 1) { window.history.back(); } else { window.location.href = '/catalog'; }
      };

      const modal = document.getElementById('review-modal');
      const writeReviewBtn = document.getElementById('write-review-btn');
      
      if (writeReviewBtn && modal) { 
        writeReviewBtn.onclick = (e) => { 
          e.preventDefault();
          modal.style.display = 'flex'; 
        };
      }
      const closeReviewModal = document.getElementById('close-review-modal');
      const cancelReview = document.getElementById('cancel-review');
      if (closeReviewModal && modal) { closeReviewModal.onclick = () => { modal.style.display = 'none'; }; }
      if (cancelReview && modal) { cancelReview.onclick = () => { modal.style.display = 'none'; }; }

      const reviewForm = document.getElementById('review-form');
      if (reviewForm) {
        reviewForm.onsubmit = async function(e) {
          e.preventDefault();
          const rating = this.rating.value;
          const reviewText = this.review.value;
          if (!rating || !reviewText.trim()) { alert('Please provide both a rating and review text.'); return; }
          const submitBtn = this.querySelector('button[type="submit"]');
          const originalText = submitBtn ? submitBtn.textContent : '';
          if (submitBtn) { submitBtn.textContent = 'Submitting...'; submitBtn.disabled = true; }
          try {
            const formData = new FormData();
            formData.append('_token', bs.csrfToken || '');
            formData.append('rating', parseInt(rating));
            formData.append('review', reviewText.trim());
            const productId = bs.productId || null;
            const response = await fetch(`/product/${productId}/add-review`, { method: 'POST', body: formData });
            if (response.ok) { if (modal) modal.style.display = 'none'; window.location.reload(); } else { const text = await response.text(); console.error('Server response:', text); alert('Failed to submit review. Please try again.'); }
          } catch (error) { console.error('Network error:', error); alert('Network error. Please check your connection and try again.'); }
          finally { if (submitBtn) { submitBtn.textContent = originalText; submitBtn.disabled = false; } }
        };
      }
    });

    // ----- Server-rendered success toast (close + auto-hide) -----
    safe(() => {
      const toast = document.getElementById('success-toast');
      const closeBtn = document.getElementById('close-success-toast');
      if (toast) {
        if (closeBtn) {
          closeBtn.addEventListener('click', function() { toast.style.display = 'none'; });
        }
        setTimeout(function() { if (toast) toast.style.display = 'none'; }, 4000);
      }
    });

    // ----- Review interactions: delete, read-more, star buttons, helpful -----
    safe(() => {
      document.querySelectorAll('.delete-review-btn').forEach(btn => {
        btn.onclick = async function(e) {
          e.preventDefault();
          if (!confirm('Delete this review?')) return;
          const reviewId = this.getAttribute('data-id');
          if (!reviewId) { alert('Review ID not found.'); return; }
          try {
            const res = await fetch(`/reviews/${reviewId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': bs.csrfToken || '', 'Accept': 'application/json' } });
            if (res.ok) { const reviewElement = document.querySelector(`[data-review-id="${reviewId}"]`); if (reviewElement) reviewElement.remove(); window.location.reload(); } else { const errorData = await res.json().catch(()=>({})); alert(errorData.message || 'Failed to delete review.'); }
          } catch (error) { console.error('Delete review error:', error); alert('Network error occurred.'); }
        };
      });

      document.querySelectorAll('.read-more-btn').forEach(btn => {
        btn.onclick = function(e) {
          e.preventDefault();
          const reviewId = this.getAttribute('data-review-id');
          const truncatedText = document.querySelector(`.review-text-${reviewId}`);
          const fullText = document.querySelector(`.review-full-${reviewId}`);
          if (this.textContent === 'Read more') { truncatedText && truncatedText.classList.add('hidden'); fullText && fullText.classList.remove('hidden'); this.textContent = 'Read less'; } else { truncatedText && truncatedText.classList.remove('hidden'); fullText && fullText.classList.add('hidden'); this.textContent = 'Read more'; }
        };
      });

      const starBtns = document.querySelectorAll('.star-btn');
      const ratingInput = document.getElementById('rating-input');
      const ratingText = document.getElementById('rating-text');
      const ratingTexts = ['Poor','Fair','Good','Very Good','Excellent'];
      if (starBtns.length > 0 && ratingInput && ratingText) {
        starBtns.forEach((btn, index) => {
          btn.addEventListener('click', (e) => { e.preventDefault(); const rating = index + 1; ratingInput.value = rating; ratingText.textContent = ratingTexts[index]; starBtns.forEach((star, i) => { if (i < rating) { star.classList.remove('text-gray-300'); star.classList.add('text-yellow-400'); } else { star.classList.remove('text-yellow-400'); star.classList.add('text-gray-300'); } }); });
          btn.addEventListener('mouseenter', () => { const rating = index + 1; starBtns.forEach((star, i) => { if (i < rating) { star.classList.add('text-yellow-400'); star.classList.remove('text-gray-300'); } else { star.classList.remove('text-yellow-400'); star.classList.add('text-gray-300'); } }); });
          btn.addEventListener('mouseleave', () => { const currentRating = parseInt(ratingInput.value) || 5; starBtns.forEach((star, i) => { if (i < currentRating) { star.classList.add('text-yellow-400'); star.classList.remove('text-gray-300'); } else { star.classList.remove('text-yellow-400'); star.classList.add('text-gray-300'); } }); });
        });
        starBtns.forEach((star, i) => { if (i < 5) { star.classList.add('text-yellow-400'); star.classList.remove('text-gray-300'); } });
      }

      document.querySelectorAll('.helpful-btn').forEach(btn => {
        btn.onclick = async function(e) {
          e.preventDefault();
          if (bs.auth) {
            const reviewId = this.getAttribute('data-review-id');
            if (!reviewId) { console.warn('Review ID not found'); return; }
            const countSpan = this.querySelector('.helpful-count'); if (!countSpan) { console.warn('Helpful count span not found'); return; }
            try {
              const res = await fetch(`/reviews/${reviewId}/helpful`, { method: 'POST', headers: { 'X-CSRF-TOKEN': bs.csrfToken || '', 'Accept': 'application/json', 'Content-Type': 'application/json' } });
              const data = await res.json();
              if (res.ok) { countSpan.textContent = `(${data.helpful_count || 0})`; if (data.action === 'added') { this.classList.add('text-blue-600'); this.classList.remove('text-gray-500'); } else { this.classList.remove('text-blue-600'); this.classList.add('text-gray-500'); } } else { console.error('Helpful button error:', data); alert(data.message || 'Failed to mark as helpful'); }
            } catch (error) { console.error('Helpful button network error:', error); alert('Network error. Please try again.'); }
          } else {
            alert('Please login to mark reviews as helpful');
          }
        };
      });
    });

    // ----- Order controls / quantity / price / form submission / payment formatting -----
    safe(() => {
      const quantityInput = document.getElementById('quantity');
      const decreaseBtn = document.getElementById('decrease-qty');
      const increaseBtn = document.getElementById('increase-qty');
      const unitPriceElement = document.getElementById('unit-price');
      const totalPriceElement = document.getElementById('total-price');
      const unitPrice = parseFloat(bs.unitPrice || 0);
      let inputTimeout;

      function updateTotalPrice() {
        let quantity = parseInt(quantityInput ? quantityInput.value : 1, 10);
        if (isNaN(quantity) || quantity < 1) quantity = 1;
        if (quantity > 100) quantity = 100;
        const total = unitPrice * quantity;
        if (totalPriceElement) totalPriceElement.textContent = '$' + total.toFixed(2);
      }

      if (decreaseBtn && quantityInput) {
        decreaseBtn.addEventListener('click', function() { clearTimeout(inputTimeout); let currentValue = parseInt(quantityInput.value) || 1; if (currentValue > 1) { quantityInput.value = currentValue - 1; updateTotalPrice(); } });
      }
      if (increaseBtn && quantityInput) {
        increaseBtn.addEventListener('click', function() { clearTimeout(inputTimeout); let currentValue = parseInt(quantityInput.value) || 1; if (currentValue < 100) { quantityInput.value = currentValue + 1; updateTotalPrice(); } });
      }
      if (quantityInput) {
        quantityInput.addEventListener('keyup', function() { updateTotalPrice(); });
        quantityInput.addEventListener('blur', function() { let value = this.value.replace(/[^0-9]/g, ''); if (value === '' || value === '0') value = '1'; let numValue = parseInt(value); if (isNaN(numValue) || numValue < 1) numValue = 1; if (numValue > 100) numValue = 100; this.value = numValue; updateTotalPrice(); });
        quantityInput.addEventListener('paste', function(e) { e.preventDefault(); let paste = (e.clipboardData || window.clipboardData).getData('text'); let numericValue = paste.replace(/[^0-9]/g, ''); if (numericValue) { let value = parseInt(numericValue); if (value < 1) value = 1; if (value > 100) value = 100; this.value = value; updateTotalPrice(); } });
        quantityInput.addEventListener('keypress', function(e) { if ([8,9,27,13,46].indexOf(e.keyCode) !== -1 || (e.keyCode === 65 && e.ctrlKey === true) || (e.keyCode === 67 && e.ctrlKey === true) || (e.keyCode === 86 && e.ctrlKey === true) || (e.keyCode === 88 && e.ctrlKey === true)) { return; } if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) { e.preventDefault(); } });
      }

      const orderForm = document.getElementById('order-form');
      if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
          const customerName = document.getElementById('customer_name');
          const customerEmail = document.getElementById('customer_email');
          const customerPhone = document.getElementById('customer_phone');
          const quantity = document.getElementById('quantity');
          if (!customerName.value.trim()) { e.preventDefault(); alert('Please enter your full name.'); customerName.focus(); return; }
          if (!customerEmail.value.trim()) { e.preventDefault(); alert('Please enter your email address.'); customerEmail.focus(); return; }
          if (!customerPhone.value.trim()) { e.preventDefault(); alert('Please enter your phone number.'); customerPhone.focus(); return; }
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; if (!emailRegex.test(customerEmail.value)) { e.preventDefault(); alert('Please enter a valid email address.'); customerEmail.focus(); return; }
          const qty = parseInt(quantity.value); if (qty < 1 || qty > 100) { e.preventDefault(); alert('Quantity must be between 1 and 100.'); quantity.focus(); return; }
          const submitBtn = this.querySelector('button[type="submit"]'); if (submitBtn) { const originalText = submitBtn.innerHTML; submitBtn.innerHTML = '<svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Processing Order...'; submitBtn.disabled = true; setTimeout(()=>{ if (submitBtn.disabled) { submitBtn.innerHTML = originalText; submitBtn.disabled = false; } },10000); }
        });
      }

      function updatePaymentMethod() {
        const cashPaymentInfo = document.getElementById('cash-payment-info');
        const onlinePaymentInfo = document.getElementById('online-payment-info');
        const creditCardForm = document.getElementById('credit-card-form');
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const submitButton = document.querySelector('button[type="submit"] span');
        paymentMethods.forEach(method => {
          if (method.checked && method.value === 'cash') {
            if (cashPaymentInfo) cashPaymentInfo.style.display = 'block';
            if (onlinePaymentInfo) onlinePaymentInfo.style.display = 'none';
            if (creditCardForm) creditCardForm.style.display = 'none';
            if (submitButton) submitButton.textContent = 'Place Order Now';
          } else if (method.checked && method.value === 'online') {
            if (cashPaymentInfo) cashPaymentInfo.style.display = 'none';
            if (onlinePaymentInfo) onlinePaymentInfo.style.display = 'block';
            if (creditCardForm) creditCardForm.style.display = 'block';
            if (submitButton) submitButton.textContent = 'Pay Now & Place Order';
          }
        });
      }

      function formatCardNumber(input) { let value = input.value.replace(/\D/g, ''); value = value.substring(0,16); value = value.replace(/(.{4})/g,'$1 '); input.value = value.trim(); }
      function formatExpiryDate(input) { let value = input.value.replace(/\D/g, ''); if (value.length >= 2) value = value.substring(0,2) + '/' + value.substring(2,4); input.value = value; }
      function formatCVV(input) { input.value = input.value.replace(/\D/g,'').substring(0,4); }

      // card formatting listeners (only if elements exist)
      const cardNumberInput = document.getElementById('card_number');
      const expiryInput = document.getElementById('card_expiry');
      const cvvInput = document.getElementById('card_cvv');
      if (cardNumberInput) cardNumberInput.addEventListener('input', function(){ formatCardNumber(this); });
      if (expiryInput) expiryInput.addEventListener('input', function(){ formatExpiryDate(this); });
      if (cvvInput) cvvInput.addEventListener('input', function(){ formatCVV(this); });

      // Add event listeners for payment method changes (if they exist on the page)
      const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
      if (paymentMethodInputs.length > 0) {
        paymentMethodInputs.forEach(function(input) {
          input.addEventListener('change', updatePaymentMethod);
        });
        // Initialize payment method display
        updatePaymentMethod();
      }

      // Checkout / Add to cart button setup (only for authenticated flows)
      if (bs.auth) {
        if (window.checkoutButtonSetup) return; window.checkoutButtonSetup = true;
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
          checkoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.disabled) return;
            const quantityInput = document.getElementById('quantity');
            const quantity = quantityInput ? quantityInput.value : '1';
            const productId = bs.productId || null;
            if (!productId) { alert('Error: Product ID is missing!'); return; }
            this.disabled = true; this.style.opacity = '0.7'; this.innerHTML = '<span>Adding to Cart...</span>';
            const formData = new FormData(); formData.append('product_id', productId); formData.append('quantity', parseInt(quantity)); formData.append('_token', bs.csrfToken || '');
            fetch('/cart/add-simple', { method: 'POST', headers: { 'X-CSRF-TOKEN': bs.csrfToken || '', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: formData })
            .then(response => { if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`); const contentType = response.headers.get('content-type'); if (!contentType || !contentType.includes('application/json')) throw new Error('Response is not JSON'); return response.json(); })
            .then(data => {
              if (data.success) {
                this.innerHTML = '<span>✓ Added to Cart!</span>'; this.style.backgroundColor = '#10b981'; if (window.updateCartCount) { window.updateCartCount(data.cart_count); } else { const cartCount = document.getElementById('cart-count'); if (cartCount) { cartCount.textContent = data.cart_count; cartCount.style.display = data.cart_count > 0 ? 'flex' : 'none'; } }
                showNotification(data.message, 'success');
                setTimeout(()=>{ this.disabled = false; this.style.opacity = '1'; this.style.backgroundColor = ''; this.innerHTML = '<span>Add to Cart</span>'; },3000);
              } else { throw new Error(data.message || 'Failed to add to cart'); }
            })
            .catch(error => {
              console.error('Error details:', error);
              this.innerHTML = '<span>Error - Try Again</span>'; this.style.backgroundColor = '#ef4444'; let errorMessage = 'Failed to add item to cart. Please try again.'; if (String(error).includes('HTTP error')) { errorMessage = 'Server error. Please check if you are logged in.'; } else if (String(error).includes('JSON')) { errorMessage = 'Server response error. Please try refreshing the page.'; } showNotification(errorMessage, 'error'); setTimeout(()=>{ this.disabled = false; this.style.opacity = '1'; this.style.backgroundColor = ''; this.innerHTML = '<span>Add to Cart</span>'; },3000);
            });
          });
        }
      }

      // ----- Notification function -----
      window.showNotification = function(message, type='success') {
        const existingNotification = document.getElementById('cart-notification'); if (existingNotification) existingNotification.remove();
        const notification = document.createElement('div');
        notification.id = 'cart-notification';
        notification.className = `fixed top-4 left-1/2 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform -translate-x-1/2 opacity-0 ${ type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }`;
        notification.innerHTML = `
          <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
              ${""}
            </div>
            <span class="font-medium">${message}</span>
          </div>
        `;
        document.body.appendChild(notification);
        setTimeout(()=>{ notification.style.opacity = '1'; },100);
        setTimeout(()=>{ notification.style.opacity = '0'; setTimeout(()=>{ notification.remove(); },300); },4000);
      };

    });
  });

})();
