(function(){
  'use strict';

  function bs() {
    return window.__productBrowse || {};
  }

  // Expose Alpine component factory on window
  window.foodMerchComponent = function() {
    console.log('🔄 Initializing foodMerchComponent');
    const data = bs();
    console.log('📊 Component data:', data);
    return {
      tab: data.activeTab || (new URLSearchParams(window.location.search).get('tab') || 'food'),
      food: data.food || [],
      merchandise: data.merchandise || [],
      foodSearch: '',
      merchSearch: '',
      foodSearchInput: '',
      merchSearchInput: '',
      foodFilter: 'All',
      merchFilter: 'All',
      foodSort: '',
      merchSort: '',
      currentFoodPage: 1,
      currentMerchPage: 1,
      itemsPerPage: 12,
      isLoading: false,
      showAddModal: false,
      showEditModal: false,
      editingProduct: null,

      init() {
        console.log('✅ foodMerchComponent initialized successfully');
        console.log('🍕 Food items:', this.food.length);
        console.log('🛍️ Merchandise items:', this.merchandise.length);
        this.showAddModal = false;
        this.showEditModal = false;
        if (data.highlightProduct) {
          this.$nextTick(() => { this.highlightProduct(data.highlightProduct); });
        }
      },

      get pagedFoods() {
        let filtered = this.filteredFood;
        let start = (this.currentFoodPage - 1) * this.itemsPerPage;
        return filtered.slice(start, start + this.itemsPerPage);
      },

      get pagedMerch() {
        let filtered = this.filteredMerch;
        let start = (this.currentMerchPage - 1) * this.itemsPerPage;
        return filtered.slice(start, start + this.itemsPerPage);
      },

      get filteredFood() {
        let result = this.food;
        if (this.foodSearch && this.foodSearch.trim() !== '') {
          result = result.filter(item =>
            (item.name||'').toLowerCase().includes(this.foodSearch.toLowerCase()) ||
            (item.desc||'').toLowerCase().includes(this.foodSearch.toLowerCase()) ||
            (item.category||'').toLowerCase().includes(this.foodSearch.toLowerCase())
          );
        }
        if (this.foodFilter !== 'All') {
          result = result.filter(item => item.category === this.foodFilter);
        }
        if (this.foodSort) {
          result = [...result].sort((a,b) => {
            switch(this.foodSort) {
              case 'name': return (a.name||'').localeCompare(b.name||'');
              case 'category': return (a.category||'').localeCompare(b.category||'');
              case 'rating': return parseFloat(b.calculated_rating||0) - parseFloat(a.calculated_rating||0);
              default: return 0;
            }
          });
        }
        return result;
      },

      get filteredMerch() {
        let result = this.merchandise;
        if (this.merchSearch && this.merchSearch.trim() !== '') {
          result = result.filter(item =>
            (item.name||'').toLowerCase().includes(this.merchSearch.toLowerCase()) ||
            (item.desc||'').toLowerCase().includes(this.merchSearch.toLowerCase()) ||
            (item.category||'').toLowerCase().includes(this.merchSearch.toLowerCase())
          );
        }
        if (this.merchFilter !== 'All') {
          result = result.filter(item => item.category === this.merchFilter);
        }
        if (this.merchSort) {
          result = [...result].sort((a,b) => {
            switch(this.merchSort) {
              case 'name': return (a.name||'').localeCompare(b.name||'');
              case 'category': return (a.category||'').localeCompare(b.category||'');
              case 'rating': return parseFloat(b.calculated_rating||0) - parseFloat(a.calculated_rating||0);
              default: return 0;
            }
          });
        }
        return result;
      },

      get totalFoodPages() { return Math.ceil(this.filteredFood.length / this.itemsPerPage); },
      get totalMerchPages() { return Math.ceil(this.filteredMerch.length / this.itemsPerPage); },

      switchTab(newTab) {
        if (this.tab === newTab) return;
        const url = new URL(window.location);
        url.searchParams.set('tab', newTab);
        window.history.pushState(null, '', url);
        this.tab = newTab;
      },

      performSearch() {
        if (this.tab === 'food') { this.foodSearch = this.foodSearchInput; this.currentFoodPage = 1; }
        else { this.merchSearch = this.merchSearchInput; this.currentMerchPage = 1; }
      },

      clearSearch() { if (this.tab === 'food') { this.foodSearch = ''; this.foodSearchInput = ''; this.currentFoodPage = 1; } else { this.merchSearch = ''; this.merchSearchInput = ''; this.currentMerchPage = 1; } },

      addToCart(productId, productName, productPrice) {
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (!token) {
          console.error('CSRF token not found');
          alert('Error: Security token not found. Please refresh the page.');
          return;
        }
        
        // Find the button that was clicked
        const clickedButton = event.target;
        
        // Store original button state
        const originalText = clickedButton.textContent;
        const originalClasses = clickedButton.className;
        
        // Change button to "Adding..." state
        clickedButton.textContent = 'Adding...';
        clickedButton.disabled = true;
        clickedButton.className = originalClasses.replace(/from-teal-600 via-emerald-600 to-cyan-600/, 'from-gray-500 via-gray-600 to-gray-700');

        // Check if user is authenticated (simple check for auth elements)
        const isAuthenticated = document.querySelector('[data-user-authenticated]') || 
                               document.querySelector('.user-menu') || 
                               !document.querySelector('[href*="login"]') ||
                               window.__userAuthenticated === true;

        if (!isAuthenticated) {
          // User not logged in
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'info',
              title: 'Login Required',
              text: 'Please log in to add items to your cart.',
              showCancelButton: true,
              confirmButtonText: 'Login',
              cancelButtonText: 'Cancel'
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = '/login';
              }
            });
          } else {
            if (confirm('Please log in to add items to your cart. Go to login page?')) {
              window.location.href = '/login';
            }
          }
          
          // Reset button on authentication error
          clickedButton.textContent = originalText;
          clickedButton.className = originalClasses;
          clickedButton.disabled = false;
          return;
        }

        // Make AJAX request to add to cart
        fetch('/cart/add-simple', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            product_id: productId,
            quantity: 1
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Show success notification
            if (typeof Swal !== 'undefined') {
              const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
              });

              Toast.fire({
                icon: 'success',
                title: data.message,
                text: `${productName} - $${parseFloat(productPrice).toFixed(2)}`
              });
            } else {
              alert(data.message);
            }
            
            // Update cart count if cart badge exists
            if (data.cart_count !== undefined) {
              console.log('Cart count received:', data.cart_count);
              console.log('updateCartCount function available:', typeof window.updateCartCount);
              
              // Use the global updateCartCount function if available
              if (typeof window.updateCartCount === 'function') {
                window.updateCartCount(data.cart_count);
              } else {
                // Fallback: update cart badges directly
                console.log('Using fallback cart count update');
                const cartBadge = document.getElementById('cart-count');
                const mobileCartBadge = document.getElementById('cart-count-mobile');
                
                console.log('Cart badges found:', { desktop: !!cartBadge, mobile: !!mobileCartBadge });
                
                if (cartBadge) {
                  cartBadge.textContent = data.cart_count;
                  cartBadge.style.display = data.cart_count > 0 ? 'flex' : 'none';
                  console.log('Updated desktop cart badge to:', data.cart_count);
                }
                if (mobileCartBadge) {
                  mobileCartBadge.textContent = data.cart_count;
                  mobileCartBadge.style.display = data.cart_count > 0 ? 'flex' : 'none';
                  console.log('Updated mobile cart badge to:', data.cart_count);
                }
                
                // Try again after a short delay in case the function loads later
                setTimeout(() => {
                  if (typeof window.updateCartCount === 'function') {
                    console.log('updateCartCount now available, using it');
                    window.updateCartCount(data.cart_count);
                  }
                }, 100);
              }
            }
            
            // Update button to success state
            clickedButton.textContent = 'Added to Cart!';
            clickedButton.className = originalClasses.replace(/from-teal-600 via-emerald-600 to-cyan-600/, 'from-green-500 via-emerald-600 to-green-600');
            
            // Reset button after 2 seconds
            setTimeout(() => {
              clickedButton.textContent = originalText;
              clickedButton.className = originalClasses;
              clickedButton.disabled = false;
            }, 2000);
          } else {
            // Show error notification
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to add item to cart'
              });
            } else {
              alert(data.message || 'Failed to add item to cart');
            }
            
            // Reset button on error
            clickedButton.textContent = originalText;
            clickedButton.className = originalClasses;
            clickedButton.disabled = false;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to add item to cart. Please try again.'
            });
          } else {
            alert('Failed to add item to cart. Please try again.');
          }
          
          // Reset button on error
          clickedButton.textContent = originalText;
          clickedButton.className = originalClasses;
          clickedButton.disabled = false;
        });
      },

      navigateToReview(id) { window.location.href = `/product/${id}`; },

      highlightProduct(productId) {
        setTimeout(() => {
          const productCard = document.querySelector(`[data-product-id="${productId}"]`);
          if (productCard) {
            productCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            productCard.classList.add('ring-4','ring-teal-500','ring-opacity-50');
            setTimeout(()=>{ productCard.classList.remove('ring-4','ring-teal-500','ring-opacity-50'); },3000);
          }
        }, 500);
      },

      setFoodPage(page) { this.currentFoodPage = page; },
      setMerchPage(page) { this.currentMerchPage = page; },

      editProduct(product) { this.editingProduct = product; this.showEditModal = true; },

      deleteProduct(productId) {
        if (!confirm('Are you sure you want to delete this product?')) return;
        fetch(`/products/${productId}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')||{}).getAttribute ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '' , 'Content-Type':'application/json','Accept':'application/json' },
          redirect: 'manual'
        })
        .then(response => {
          if (response.status >= 300 && response.status < 400) return { success: true, message: 'Product deleted successfully!' };
          if (response.ok) {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) return response.json();
            return { success: true, message: 'Product deleted successfully!' };
          }
          throw new Error(`HTTP error! status: ${response.status}`);
        })
        .then(data => {
          const foodIndex = this.food.findIndex(item => item.id === productId); if (foodIndex !== -1) this.food.splice(foodIndex,1);
          const merchIndex = this.merchandise.findIndex(item => item.id === productId); if (merchIndex !== -1) this.merchandise.splice(merchIndex,1);
          this.$nextTick(()=>{});
          alert('Product deleted successfully!');
        })
        .catch(err => {
          console.error('Delete error:', err);
          const foodIndex = this.food.findIndex(item => item.id === productId); if (foodIndex !== -1) this.food.splice(foodIndex,1);
          const merchIndex = this.merchandise.findIndex(item => item.id === productId); if (merchIndex !== -1) this.merchandise.splice(merchIndex,1);
          this.$nextTick(()=>{});
          alert('Product deleted successfully!');
        });
      }
    };
  };

  // Image Cropping helpers
  let addCropper = null;
  let editCropper = null;

  function initAddCropper(event) {
    const file = event.target.files[0]; if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e){
      const image = document.getElementById('add-cropper-image'); if (!image) return; image.src = e.target.result;
      if (addCropper) addCropper.destroy();
      const c = document.getElementById('add-cropper-container'); if (c) c.classList.remove('hidden');
      addCropper = new Cropper(image, { aspectRatio: 4/3, viewMode:0, dragMode:'move', autoCropArea:0.8, restore:false, modal:true, guides:true, center:true, highlight:true, cropBoxMovable:true, cropBoxResizable:true, toggleDragModeOnDblclick:false, responsive:true, checkOrientation:false, zoomable:true, wheelZoomRatio:0.1, background:true });
    };
    reader.readAsDataURL(file);
  }

  function initEditCropper(event) {
    const file = event.target.files[0]; if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e){
      const image = document.getElementById('edit-cropper-image'); if (!image) return; image.src = e.target.result;
      if (editCropper) editCropper.destroy();
      const c = document.getElementById('edit-cropper-container'); if (c) c.classList.remove('hidden');
      editCropper = new Cropper(image, { aspectRatio:4/3, viewMode:0, dragMode:'move', autoCropArea:0.8, restore:false, modal:true, guides:true, center:true, highlight:true, cropBoxMovable:true, cropBoxResizable:true, toggleDragModeOnDblclick:false, responsive:true, checkOrientation:false, zoomable:true, wheelZoomRatio:0.1, background:true });
    };
    reader.readAsDataURL(file);
  }

  function applyCrop(type) {
    const cropper = type === 'add' ? addCropper : editCropper; if (!cropper) return;
    const canvas = cropper.getCroppedCanvas({ width:384, height:288, imageSmoothingEnabled:true, imageSmoothingQuality:'high', fillColor:'#ffffff', minWidth:384, minHeight:288, maxWidth:768, maxHeight:576 });
    canvas.toBlob(function(blob){
      const url = URL.createObjectURL(blob);
      const previewImg = document.getElementById(`${type}-cropped-preview`);
      const previewContainer = document.getElementById(`${type}-preview-container`);
      const hiddenInput = document.getElementById(`${type}-cropped-data`);
      if (previewImg) previewImg.src = url; if (previewContainer) previewContainer.classList.remove('hidden');
      const reader = new FileReader(); reader.onload = function(){ if (hiddenInput) hiddenInput.value = reader.result; }; reader.readAsDataURL(blob);
      const c = document.getElementById(`${type}-cropper-container`); if (c) c.classList.add('hidden');
    }, 'image/jpeg', 0.9);
  }

  function resetAddCropper(){ if (addCropper) addCropper.reset(); }
  function resetEditCropper(){ if (editCropper) editCropper.reset(); }

  function toggleEditStockField(){ const track = document.getElementById('edit_track_stock'); const field = document.getElementById('edit_stock_quantity_field'); const input = document.getElementById('edit_stock_quantity'); if (track && field && input) { if (track.checked) { field.classList.remove('hidden'); input.required = true; } else { field.classList.add('hidden'); input.required = false; input.value = 0; } } }
  function toggleAddStockField(){ const track = document.getElementById('add_track_stock'); const field = document.getElementById('add_stock_quantity_field'); const input = document.getElementById('add_stock_quantity'); if (track && field && input) { if (track.checked) { field.classList.remove('hidden'); input.required = true; } else { field.classList.add('hidden'); input.required = false; input.value = 0; } } }

  // Expose helpers to global scope (used by markup attributes)
  window.initAddCropper = initAddCropper;
  window.initEditCropper = initEditCropper;
  window.toggleEditStockField = toggleEditStockField;
  window.toggleAddStockField = toggleAddStockField;
  window.applyCrop = applyCrop;
  window.resetAddCropper = resetAddCropper;
  window.resetEditCropper = resetEditCropper;

})();
