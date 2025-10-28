(function(){
  const bs = window.__adminProductCreate || {};
  const getCsrf = () => bs.csrf || (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content')) || '';
  const storeUrl = bs.storeUrl || '/admin/products';
  const redirectUrl = bs.redirectUrl || '/admin/products';

  // Expose a global productForm() factory for Alpine to consume
  window.productForm = function() {
    return {
      isSubmitting: false,
      errors: {},
      successMessage: '',
      showErrors: false,
      showCropper: false,
      croppedUrl: '',
      cropper: null,
      croppedBlob: null,

      init() {
        // defensive init
        this.isSubmitting = false;
        this.errors = {};
        this.initStockTracking();
        // trigger an update tick in case Alpine needs it
        try{ this.$nextTick(()=>{ this.isSubmitting = false; this.$el.dispatchEvent(new Event('x-data-updated')); }); } catch(e){}
      },

      initStockTracking() {
        const trackStockCheckbox = document.getElementById('track_stock');
        const stockField = document.getElementById('stock_quantity_field');
        const stockInput = document.getElementById('stock_quantity');

        if (!trackStockCheckbox) return;
        trackStockCheckbox.addEventListener('change', function(){
          if (this.checked) { stockField && stockField.classList.remove('hidden'); if (stockInput) stockInput.required = true; }
          else { stockField && stockField.classList.add('hidden'); if (stockInput){ stockInput.required = false; stockInput.value = 0; } }
        });

        // initialize state
        if (trackStockCheckbox.checked) { stockField && stockField.classList.remove('hidden'); if (stockInput) stockInput.required = true; }
      },

      startCrop(event) {
        const file = event.target && event.target.files && event.target.files[0];
        if (!file) return;
        this.showCropper = true;

        if (this.cropper) { try { this.cropper.destroy(); } catch(e){} this.cropper = null; }

        const img = document.getElementById('cropper-img');
        if (!img) return;

        const reader = new FileReader();
        reader.onload = (e) => {
          img.src = e.target.result;
          img.onload = () => {
            try {
              this.cropper = new Cropper(img, {
                aspectRatio: 4/3,
                viewMode: 1,
                autoCropArea: 1,
                responsive: true,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
              });
            } catch(err){ console.error('Cropper init error', err); }
          };
        };
        reader.readAsDataURL(file);
      },

      finishCrop() {
        if (!this.cropper) return;
        this.cropper.getCroppedCanvas({ width:800, height:600, imageSmoothingEnabled:false, imageSmoothingQuality:'high' })
          .toBlob((blob) => {
            this.croppedBlob = blob;
            this.croppedUrl = URL.createObjectURL(blob);
            const reader = new FileReader();
            reader.onload = () => { const el = document.getElementById('cropped-data'); if (el) el.value = reader.result; };
            reader.readAsDataURL(blob);
          }, 'image/jpeg', 0.9);
      },

      resetCropper() {
        try{ if (this.cropper) { this.cropper.destroy(); this.cropper = null; } } catch(e){}
        this.showCropper = false;
        this.croppedUrl = '';
        this.croppedBlob = null;
        const inp = document.getElementById('img'); if (inp) inp.value = '';
        const hidden = document.getElementById('cropped-data'); if (hidden) hidden.value = '';
      },

      handleSubmit(event) {
        // If we have a cropped image, submit via JS to include the blob
        if (this.croppedBlob) {
          event.preventDefault();
          this.submitProduct();
        } else {
          if (event.type === 'submit') this.isSubmitting = true;
        }
      },

      async submitProduct() {
        const formElement = this.$refs && this.$refs.productForm ? this.$refs.productForm : document.querySelector('form[x-data]');
        if (!formElement) { console.error('Form element not found'); return; }
        const formData = new FormData(formElement);
        this.isSubmitting = true; this.errors = {};

        if (this.croppedBlob) {
          const reader = new FileReader();
          reader.onload = () => { formData.set('cropped_image', reader.result); this.sendFormData(formData); };
          reader.readAsDataURL(this.croppedBlob);
        } else {
          this.sendFormData(formData);
        }
      },

      async sendFormData(formData) {
        try {
          const controller = new AbortController();
          const timeoutId = setTimeout(()=> controller.abort(), 30000);
          const failsafe = setTimeout(()=>{ if(this.isSubmitting){ this.isSubmitting = false; alert('Form submission is taking too long. Please try again.'); } }, 10000);

          const response = await fetch(storeUrl, { method:'POST', headers:{ 'X-CSRF-TOKEN': getCsrf(), 'Accept':'application/json' }, body: formData, signal: controller.signal });
          clearTimeout(timeoutId); clearTimeout(failsafe);

          if (!response.ok) {
            const errText = await response.text().catch(()=>null);
            throw new Error(`HTTP ${response.status} ${errText||''}`);
          }

          const data = await response.json().catch(()=>({}));
          if (data.success) {
            this.successMessage = data.message || 'Product created successfully!';
            this.errors = {};
            this.isSubmitting = false;
            setTimeout(()=>{ if (data.redirect_url) window.location.href = data.redirect_url; else window.location.href = redirectUrl; }, 1200);
          } else {
            if (data.errors) { this.errors = data.errors; const first = Object.values(data.errors)[0]; alert('Validation Error: '+(Array.isArray(first)?first[0]:first)); }
            else if (data.error) { alert('Server Error: '+data.error); }
            else { alert('Failed to create product. Please try again.'); }
          }
        } catch (error) {
          console.error('Product submit error', error);
          if (error.name === 'AbortError') alert('Request timed out. Please try again.');
          else alert('Network error occurred. Please try again.');
        } finally { this.isSubmitting = false; }
      }
    };
  };

})();
