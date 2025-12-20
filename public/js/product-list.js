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
      others: data.others || [],
      foodSearch: '',
      merchSearch: '',
      othersSearch: '',
      foodSearchInput: '',
      merchSearchInput: '',
      othersSearchInput: '',
      showFoodPredictions: false,
      showMerchPredictions: false,
      showOthersPredictions: false,
      foodFilter: 'All',
      merchFilter: 'All',
      othersFilter: 'All',
      foodSort: '',
      merchSort: '',
      othersSort: '',
      currentFoodPage: 1,
      currentMerchPage: 1,
      currentOthersPage: 1,
      itemsPerPage: 12,
      isLoading: false,
      isSubmitting: false,
      showAddModal: false,
      showEditModal: false,
      editingProduct: null,

      init() {
        console.log('✅ foodMerchComponent initialized successfully');
        console.log('🍕 Food items:', this.food.length);
        console.log('🛍️ Merchandise items:', this.merchandise.length);
        console.log('📦 Others items:', this.others.length);
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

      get pagedOthers() {
        let filtered = this.filteredOthers;
        let start = (this.currentOthersPage - 1) * this.itemsPerPage;
        return filtered.slice(start, start + this.itemsPerPage);
      },

      get filteredOthers() {
        let result = this.others;
        if (this.othersSearch && this.othersSearch.trim() !== '') {
          result = result.filter(item =>
            (item.name||'').toLowerCase().includes(this.othersSearch.toLowerCase()) ||
            (item.desc||'').toLowerCase().includes(this.othersSearch.toLowerCase()) ||
            (item.category||'').toLowerCase().includes(this.othersSearch.toLowerCase())
          );
        }
        if (this.othersFilter !== 'All') {
          result = result.filter(item => item.category === this.othersFilter);
        }
        if (this.othersSort) {
          result = [...result].sort((a,b) => {
            switch(this.othersSort) {
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
      get totalOthersPages() { return Math.ceil(this.filteredOthers.length / this.itemsPerPage); },

      // Real-time update methods
      addProductToList(product) {
        if (product.type === 'food') {
          this.food.push(product);
        } else if (product.type === 'merch') {
          this.merchandise.push(product);
        } else if (product.type === 'others') {
          this.others.push(product);
        }
        // Switch to the appropriate tab and highlight the new product
        this.switchTab(product.type);
        this.$nextTick(() => {
          this.highlightProduct(product.id);
        });
      },

      updateProductInList(updatedProduct) {
        const lists = {
          food: this.food,
          merch: this.merchandise,
          others: this.others
        };
        
        let originalIndex = -1;
        let originalList = null;
        
        // Find the original position and list
        Object.entries(lists).forEach(([type, list]) => {
          const index = list.findIndex(p => p.id === updatedProduct.id);
          if (index !== -1) {
            originalIndex = index;
            originalList = type;
          }
        });
        
        // Remove from all lists
        Object.values(lists).forEach(list => {
          const index = list.findIndex(p => p.id === updatedProduct.id);
          if (index !== -1) list.splice(index, 1);
        });
        
        // Add to correct list
        if (lists[updatedProduct.type]) {
          // If product stayed in the same category, preserve position
          if (originalList === updatedProduct.type && originalIndex !== -1) {
            lists[updatedProduct.type].splice(originalIndex, 0, updatedProduct);
          } else {
            // If category changed, add to end of new category
            lists[updatedProduct.type].push(updatedProduct);
          }
        }
        
        // Highlight the updated product
        this.switchTab(updatedProduct.type);
        this.$nextTick(() => {
          this.highlightProduct(updatedProduct.id);
        });
      },

      removeProductFromList(productId) {
        const lists = [this.food, this.merchandise, this.others];
        lists.forEach(list => {
          const index = list.findIndex(p => p.id === productId);
          if (index !== -1) list.splice(index, 1);
        });
      },

      switchTab(newTab) {
        if (this.tab === newTab) return;
        const url = new URL(window.location);
        url.searchParams.set('tab', newTab);
        window.history.pushState(null, '', url);
        this.tab = newTab;
      },

      // AJAX form submission methods
      async submitAddForm(event) {
        event.preventDefault();
        const form = event.target;
        
        // Custom validation for image upload
        const fileInput = document.getElementById('add-image-input');
        const croppedInput = document.getElementById('add-cropped-data');
        const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;
        const hasCroppedData = croppedInput && croppedInput.value && croppedInput.value.trim() !== '';
        
        if (!hasFile && !hasCroppedData) {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Image Required',
              text: 'Please upload and crop an image for the product.',
              confirmButtonColor: '#0d9488'
            });
          } else {
            alert('Please upload and crop an image for the product.');
          }
          return;
        }
        
        const formData = new FormData(form);
        
        // Add AJAX headers
        const headers = {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };
        
        try {
          this.isSubmitting = true;
          const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: headers
          });
          
          const data = await response.json();
          
          if (data.success) {
            // Add product to list in real-time
            this.addProductToList(data.product);
            
            // Close modal and reset form
            this.showAddModal = false;
            form.reset();
            
            // Reset image cropper and validation state
            resetAddCropper();
            if (window.addCropper) {
              window.addCropper.destroy();
              window.addCropper = null;
            }
            
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Product added successfully!',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
              });
            } else {
              alert('Product added successfully!');
            }
          } else {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'error',
                title: 'Upload Failed',
                text: data.error || 'Failed to add product. Please check all fields and try again.',
                confirmButtonColor: '#0d9488'
              });
            } else {
              alert('Error: ' + (data.error || 'Failed to add product. Please check all fields and try again.'));
            }
          }
        } catch (error) {
          console.error('Add product error:', error);
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Network Error',
              text: 'Failed to add product. Please check your connection and try again.',
              confirmButtonColor: '#0d9488'
            });
          } else {
            alert('Failed to add product. Please check your connection and try again.');
          }
        } finally {
          this.isSubmitting = false;
        }
      },

      async submitEditForm(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        // Add AJAX headers
        const headers = {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };
        
        try {
          this.isSubmitting = true;
          const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: headers
          });
          
          const data = await response.json();
          
          if (data.success) {
            // Update product in list in real-time
            this.updateProductInList(data.product);
            
            // Close modal and reset
            this.showEditModal = false;
            this.editingProduct = null;
            
            // Reset image cropper
            if (window.editCropper) {
              window.editCropper.destroy();
              window.editCropper = null;
            }
            
            // Hide preview containers
            document.getElementById('edit-preview-container')?.classList.add('hidden');
            document.getElementById('edit-cropper-container')?.classList.add('hidden');
            
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Product updated successfully!',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
              });
            } else {
              alert('Product updated successfully!');
            }
          } else {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: data.error || 'Failed to update product. Please check all fields and try again.',
                confirmButtonColor: '#0d9488'
              });
            } else {
              alert('Error: ' + (data.error || 'Failed to update product. Please check all fields and try again.'));
            }
          }
        } catch (error) {
          console.error('Edit product error:', error);
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Network Error',
              text: 'Failed to update product. Please check your connection and try again.',
              confirmButtonColor: '#0d9488'
            });
          } else {
            alert('Failed to update product. Please check your connection and try again.');
          }
        } finally {
          this.isSubmitting = false;
        }
      },

      performSearch() {
        if (this.tab === 'food') { 
          this.foodSearch = this.foodSearchInput; 
          this.currentFoodPage = 1; 
        } else if (this.tab === 'merch') { 
          this.merchSearch = this.merchSearchInput; 
          this.currentMerchPage = 1; 
        } else if (this.tab === 'others') {
          this.othersSearch = this.othersSearchInput;
          this.currentOthersPage = 1;
        }
      },

      clearSearch() { 
        if (this.tab === 'food') { 
          this.foodSearch = ''; 
          this.foodSearchInput = ''; 
          this.currentFoodPage = 1; 
        } else if (this.tab === 'merch') { 
          this.merchSearch = ''; 
          this.merchSearchInput = ''; 
          this.currentMerchPage = 1; 
        } else if (this.tab === 'others') {
          this.othersSearch = '';
          this.othersSearchInput = '';
          this.currentOthersPage = 1;
        }
      },

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
      setOthersPage(page) { this.currentOthersPage = page; },

      editProduct(product) { this.editingProduct = product; this.showEditModal = true; },

      deleteProduct(productId) {
        if (!confirm('Are you sure you want to delete this product?')) return;
        
        this.isLoading = true;
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
          this.removeProductFromList(productId);
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Product deleted successfully!',
              timer: 2000,
              showConfirmButton: false,
              toast: true,
              position: 'top-end'
            });
          } else {
            alert('Product deleted successfully!');
          }
        })
        .catch(err => {
          console.error('Delete error:', err);
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Delete Failed',
              text: 'Failed to delete product. Please check your connection and try again.',
              confirmButtonColor: '#0d9488'
            });
          } else {
            alert('Failed to delete product. Please check your connection and try again.');
          }
          this.removeProductFromList(productId);
        })
        .finally(() => {
          this.isLoading = false;
        });
      }
    };
  };

  // Image Cropping helpers
  let addCropper = null;
  let editCropper = null;

  function initAddCropper(event) {
    const file = event.target.files[0]; 
    if (!file) return;
    
    // Show loading indicator
    const uploadArea = event.target.closest('.border-dashed');
    const originalContent = uploadArea.innerHTML;
    uploadArea.innerHTML = `
      <div class="flex flex-col items-center justify-center py-12">
        <svg class="animate-spin h-8 w-8 text-teal-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-sm text-gray-600">Processing image...</p>
      </div>
    `;
    
    const reader = new FileReader();
    reader.onload = function(e) {
      // Restore original content
      uploadArea.innerHTML = originalContent;
      
      const image = document.getElementById('add-cropper-image'); 
      if (!image) return; 
      image.src = e.target.result;
      if (addCropper) addCropper.destroy();
      const c = document.getElementById('add-cropper-container'); 
      if (c) c.classList.remove('hidden');
      addCropper = new Cropper(image, { 
        aspectRatio: 4/3, viewMode:0, dragMode:'move', autoCropArea:0.8, restore:false, modal:true, 
        guides:true, center:true, highlight:true, cropBoxMovable:true, cropBoxResizable:true, 
        toggleDragModeOnDblclick:false, responsive:true, checkOrientation:false, zoomable:true, 
        wheelZoomRatio:0.1, background:true 
      });
    };
    reader.onerror = function() {
      // Restore original content on error
      uploadArea.innerHTML = originalContent;
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'error',
          title: 'Upload Error',
          text: 'Failed to process the image. Please try again.',
          confirmButtonColor: '#0d9488'
        });
      } else {
        alert('Failed to process the image. Please try again.');
      }
    };
    reader.readAsDataURL(file);
  }

  function initEditCropper(event) {
    const file = event.target.files[0]; 
    if (!file) return;
    
    // Show loading indicator
    const uploadArea = event.target.closest('.border-dashed');
    const originalContent = uploadArea.innerHTML;
    uploadArea.innerHTML = `
      <div class="flex flex-col items-center justify-center py-12">
        <svg class="animate-spin h-8 w-8 text-teal-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-sm text-gray-600">Processing image...</p>
      </div>
    `;
    
    const reader = new FileReader();
    reader.onload = function(e) {
      // Restore original content
      uploadArea.innerHTML = originalContent;
      
      const image = document.getElementById('edit-cropper-image'); 
      if (!image) return; 
      image.src = e.target.result;
      if (editCropper) editCropper.destroy();
      const c = document.getElementById('edit-cropper-container'); 
      if (c) c.classList.remove('hidden');
      editCropper = new Cropper(image, { 
        aspectRatio:4/3, viewMode:0, dragMode:'move', autoCropArea:0.8, restore:false, modal:true, 
        guides:true, center:true, highlight:true, cropBoxMovable:true, cropBoxResizable:true, 
        toggleDragModeOnDblclick:false, responsive:true, checkOrientation:false, zoomable:true, 
        wheelZoomRatio:0.1, background:true 
      });
    };
    reader.onerror = function() {
      // Restore original content on error
      uploadArea.innerHTML = originalContent;
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'error',
          title: 'Upload Error',
          text: 'Failed to process the image. Please try again.',
          confirmButtonColor: '#0d9488'
        });
      } else {
        alert('Failed to process the image. Please try again.');
      }
    };
    reader.readAsDataURL(file);
  }

  function applyCrop(type) {
    const cropper = type === 'add' ? addCropper : editCropper; 
    if (!cropper) return;
    
    const canvas = cropper.getCroppedCanvas({ 
      width:384, height:288, imageSmoothingEnabled:true, imageSmoothingQuality:'high', 
      fillColor:'#ffffff', minWidth:384, minHeight:288, maxWidth:768, maxHeight:576 
    });
    
    canvas.toBlob(function(blob){
      const url = URL.createObjectURL(blob);
      const previewImg = document.getElementById(`${type}-cropped-preview`);
      const previewContainer = document.getElementById(`${type}-preview-container`);
      const hiddenInput = document.getElementById(`${type}-cropped-data`);
      const fileInput = document.getElementById(`${type}-image-input`);
      
      if (previewImg) previewImg.src = url; 
      if (previewContainer) previewContainer.classList.remove('hidden');
      
      const reader = new FileReader(); 
      reader.onload = function(){ 
        if (hiddenInput) {
          hiddenInput.value = reader.result;
          // Mark the hidden input as having valid data for form validation
          hiddenInput.setAttribute('data-has-image', 'true');
        }
        // Remove required from file input since we now have cropped data
        if (fileInput) fileInput.removeAttribute('required');
        
        // Update upload area to show success state
        const uploadArea = fileInput?.closest('.border-dashed');
        if (uploadArea) {
          uploadArea.innerHTML = `
            <div class="flex flex-col items-center justify-center py-6 text-center">
              <svg class="h-12 w-12 text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              <p class="text-sm font-medium text-green-600">Image uploaded and cropped successfully!</p>
              <p class="text-xs text-gray-500 mt-1">You can see the preview below</p>
              <button type="button" class="mt-2 text-xs text-teal-600 hover:text-teal-500" onclick="resetAddCropper()">
                Upload different image
              </button>
            </div>
          `;
        }
      }; 
      reader.readAsDataURL(blob);
      
      const c = document.getElementById(`${type}-cropper-container`); 
      if (c) c.classList.add('hidden');
    }, 'image/jpeg', 0.9);
  }

  function resetAddCropper(){ 
    if (addCropper) {
      addCropper.destroy();
      addCropper = null;
    }
    
    // Reset the validation state
    const fileInput = document.getElementById('add-image-input');
    const hiddenInput = document.getElementById('add-cropped-data');
    const previewContainer = document.getElementById('add-preview-container');
    const cropperContainer = document.getElementById('add-cropper-container');
    
    if (fileInput) {
      fileInput.value = '';
      // Restore the original upload interface
      const uploadArea = fileInput.closest('.border-dashed');
      if (uploadArea) {
        uploadArea.innerHTML = `
          <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
              <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div class="flex text-sm text-gray-600">
              <label for="add-image-input" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
                <span>Upload an image</span>
                <input id="add-image-input" type="file" accept="image/*" class="sr-only" x-on:change="initAddCropper($event)">
              </label>
              <p class="pl-1">or drag and drop</p>
            </div>
            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
          </div>
        `;
      }
    }
    
    if (hiddenInput) {
      hiddenInput.value = '';
      hiddenInput.removeAttribute('data-has-image');
    }
    if (previewContainer) previewContainer.classList.add('hidden');
    if (cropperContainer) cropperContainer.classList.add('hidden');
  }
  
  function resetEditCropper(){ 
    if (editCropper) editCropper.reset(); 
    // Reset the validation state
    const hiddenInput = document.getElementById('edit-cropped-data');
    const previewContainer = document.getElementById('edit-preview-container');
    const cropperContainer = document.getElementById('edit-cropper-container');
    
    if (hiddenInput) {
      hiddenInput.value = '';
      hiddenInput.removeAttribute('data-has-image');
    }
    if (previewContainer) previewContainer.classList.add('hidden');
    if (cropperContainer) cropperContainer.classList.add('hidden');
  }

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
