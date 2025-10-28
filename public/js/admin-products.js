(function(){
  function safe(fn){ try{ fn(); } catch(e){ console.error(e); } }
  const bs = window.__adminProducts || {};
  // Global delete modal state
  window.deleteProductId = null;

  window.openDeleteModal = function(productId, productName) {
    window.deleteProductId = productId;
    const el = document.getElementById('delete-product-name'); if (el) el.textContent = productName;
    const modal = document.getElementById('delete-modal'); if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
  };

  window.closeDeleteModal = function() {
    const modal = document.getElementById('delete-modal'); if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    window.deleteProductId = null;
    const confirmBtn = document.getElementById('confirm-delete-btn'); if (confirmBtn) { confirmBtn.disabled = false; confirmBtn.innerHTML = 'Delete Product'; confirmBtn.className = 'px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium'; }
  };

  window.confirmDelete = async function() {
    if (!window.deleteProductId) return;
    const confirmBtn = document.getElementById('confirm-delete-btn');
    const row = document.querySelector(`tr:has(.delete-btn-${window.deleteProductId})`);
    if (confirmBtn) { confirmBtn.disabled = true; confirmBtn.innerHTML = '<svg class="animate-spin h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Deleting...'; confirmBtn.className = 'px-6 py-2.5 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium'; }
    try {
      const csrf = (bs && bs.csrf) || (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      const response = await fetch(`/admin/products/${window.deleteProductId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json','Content-Type':'application/json' } });
      const data = await response.json().catch(()=>({}));
      if (data.success) {
        showNotification(data.message || 'Product deleted successfully!', 'success');
        closeDeleteModal();
        if (row) { row.style.opacity='0'; row.style.transform='translateX(-100%)'; setTimeout(()=>{ row.remove(); const remaining = document.querySelectorAll('tbody tr').length; if (remaining===0) location.reload(); },500); } else { location.reload(); }
      } else { throw new Error(data.message || 'Failed to delete product'); }
    } catch (error) { console.error('Delete error:', error); showNotification(error.message || 'Failed to delete product','error'); closeDeleteModal(); }
  };

  window.showNotification = function(message, type='info'){
    try{
      const notification = document.createElement('div'); notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
      switch(type){ case 'success': notification.className+= ' bg-green-500 text-white'; break; case 'error': notification.className += ' bg-red-500 text-white'; break; case 'warning': notification.className += ' bg-yellow-500 text-white'; break; default: notification.className += ' bg-blue-500 text-white'; }
      const icon = type==='success' ? '✓' : type==='error' ? '✕' : type==='warning' ? '⚠' : 'ℹ';
      notification.innerHTML = `<div class="flex items-center gap-2"><span class="text-lg">${icon}</span><span>${message}</span></div>`;
      document.body.appendChild(notification);
      setTimeout(()=>{ notification.classList.remove('translate-x-full'); },100);
      setTimeout(()=>{ notification.classList.add('translate-x-full'); setTimeout(()=>{ if(notification.parentNode) notification.remove(); },300); },4000);
    } catch(e){ console.error('notification error', e); }
  };

  window.testJS = function(){ showNotification('JavaScript is working! Delete function is ready.', 'success'); };

  window.updateProductStatus = async function(productId, newStatus){
    try{
      const csrf = (bs && bs.csrf) || (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      const response = await fetch(`/admin/products/${productId}/update-status`, { method:'PATCH', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': csrf,'Accept':'application/json' }, body: JSON.stringify({ status: newStatus }) });
      const data = await response.json().catch(()=>({}));
      if (data.success) { showNotification(data.message,'success'); location.reload(); } else { showNotification(data.message || 'Failed to update status','error'); }
    } catch(e){ console.error('Update status error', e); showNotification('Error updating status','error'); }
  };

  window.toggleDropdown = function(id){ const dropdown = document.getElementById(id); if(dropdown) dropdown.classList.toggle('hidden'); };

  window.openImportModal = function(type){ const modal = document.getElementById('importModal'); if(modal){ modal.classList.remove('hidden'); } const inp = document.getElementById('importType'); if(inp) inp.value = type; const title = document.getElementById('modalTitle'); if(title) title.textContent = `Import ${type.charAt(0).toUpperCase() + type.slice(1)}`; };
  window.closeImportModal = function(){ const modal = document.getElementById('importModal'); if(modal) modal.classList.add('hidden'); const file = document.getElementById('importFile'); if(file) file.value = ''; const form = document.getElementById('importForm'); if(form) form.reset(); };

  window.handleImport = async function(){
    try{
      const fileInput = document.getElementById('importFile'); const type = (document.getElementById('importType')||{}).value;
      if(!fileInput || !fileInput.files[0]){ showNotification('Please select a CSV file','error'); return; }
      const formData = new FormData(); formData.append('csv_file', fileInput.files[0]); formData.append('_token', (bs && bs.csrf) || '');
      const response = await fetch(`/admin/${type}/import`, { method:'POST', body: formData });
      const result = await response.json().catch(()=>({}));
      if(response.ok){ showNotification(result.message || 'Import completed successfully','success'); closeImportModal(); window.location.reload(); } else { showNotification(result.message || 'Import failed','error'); }
    } catch(e){ console.error('Import error', e); showNotification('Import failed. Please try again.','error'); }
  };

  // Stock management
  window.currentProductId = null;
  window.updateStock = async function(productId, action, quantity){
    try{
      const csrf = (bs && bs.csrf) || (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      const response = await fetch(`/admin/products/${productId}/stock`, { method:'PATCH', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': csrf,'Accept':'application/json' }, body: JSON.stringify({ action, quantity }) });
      if(!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      const data = await response.json().catch(()=>({})); if(data.success){ showNotification(data.message, 'success'); location.reload(); } else { showNotification(data.message || 'Failed to update stock','error'); }
    } catch(e){ console.error('Stock update error', e); showNotification(`Error: ${e.message}`,'error'); }
  };

  window.showStockModal = function(productId, currentStock){ window.currentProductId = productId; const cur = document.getElementById('current-stock'); if(cur) cur.textContent = currentStock || '0'; const qty = document.getElementById('stock-quantity'); if(qty) qty.value=''; const action = document.getElementById('stock-action'); if(action) action.value='set'; const modal = document.getElementById('stock-modal'); if(modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); } };
  window.closeStockModal = function(){ const modal = document.getElementById('stock-modal'); if(modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); } };
  window.applyStockUpdate = function(){ const qty = parseInt((document.getElementById('stock-quantity')||{}).value) || 0; const act = (document.getElementById('stock-action')||{}).value || 'set'; if(!window.currentProductId){ showNotification('No product selected','error'); return; } updateStock(window.currentProductId, act, qty); };

  document.addEventListener('click', function(event){ const dropdown = document.getElementById('dropdown-menu'); const button = event.target.closest('[onclick*="toggleDropdown"]'); if(!button && dropdown && !dropdown.contains(event.target)) dropdown.classList.add('hidden'); });

})();
