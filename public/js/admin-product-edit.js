(() => {
  function safe(fn){ try{ fn(); } catch(e){ console.error(e); } }

  function onDomReady(fn){ if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn); else fn(); }

  onDomReady(() => {
    safe(() => {
      const trackStockCheckbox = document.getElementById('track_stock');
      const stockField = document.getElementById('stock_quantity_field');
      const stockInput = document.getElementById('stock_quantity');
      const imageInput = document.getElementById('img');

      if (trackStockCheckbox) {
        trackStockCheckbox.addEventListener('change', function() {
          if (this.checked) {
            stockField && stockField.classList.remove('hidden');
            if (stockInput) stockInput.required = true;
          } else {
            stockField && stockField.classList.add('hidden');
            if (stockInput) { stockInput.required = false; stockInput.value = 0; }
          }
        });
      }

      if (imageInput) {
        imageInput.addEventListener('change', function(e) {
          const file = e.target.files && e.target.files[0];
          if (!file) return;
          const reader = new FileReader();
          reader.onload = function(ev) {
            let preview = document.getElementById('image-preview');
            if (!preview) {
              preview = document.createElement('div');
              preview.id = 'image-preview';
              preview.className = 'mt-4 p-4 bg-teal-50 border border-teal-200 rounded-xl';
              // Insert after the file input's container
              const container = imageInput.parentNode && imageInput.parentNode.parentNode;
              if (container) container.appendChild(preview);
            }

            preview.innerHTML = `
              <p class="text-sm font-medium text-teal-800 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                New Image Preview:
              </p>
              <img src="${ev.target.result}" alt="Preview" class="w-40 h-40 object-cover rounded-xl border-2 border-white shadow-lg">
            `;
          };
          reader.readAsDataURL(file);
        });
      }
    });
  });

})();
