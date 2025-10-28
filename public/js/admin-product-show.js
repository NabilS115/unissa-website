(() => {
  function safe(fn){ try{ fn(); } catch(e){ console.error(e); } }
  const bs = window.__adminProduct || {};

  const getCsrf = () => bs.csrf || (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content')) || '';
  const redirectIndex = bs.redirectIndex || '/admin/products';

  window.showStockModal = function(productId, currentStock){
    try{
      window.currentProductId = productId;
      const cur = document.getElementById('current-stock'); if(cur) cur.textContent = (currentStock==null? '0': currentStock);
      const qty = document.getElementById('stock-quantity'); if(qty) qty.value = '';
      const modal = document.getElementById('stock-modal'); if(modal){ modal.classList.remove('hidden'); modal.classList.add('flex'); }
    }catch(e){ console.error(e); }
  };

  window.closeStockModal = function(){
    safe(()=>{ const modal = document.getElementById('stock-modal'); if(modal){ modal.classList.add('hidden'); modal.classList.remove('flex'); } });
  };

  window.updateStock = async function(productId, action, quantity){
    try{
      const csrf = getCsrf();
      const res = await fetch(`/admin/products/${productId}/stock`, { method:'PATCH', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': csrf,'Accept':'application/json' }, body: JSON.stringify({ action, quantity }) });
      if(!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json().catch(()=>({}));
      if(data.success){ showNotification(data.message || 'Stock updated', 'success'); location.reload(); } else { showNotification(data.message || 'Failed to update stock','error'); }
    }catch(e){ console.error('Stock update error', e); showNotification(e.message || 'Error updating stock', 'error'); }
  };

  window.applyStockUpdate = function(){
    try{
      const qty = parseInt((document.getElementById('stock-quantity')||{}).value) || 0;
      const act = (document.getElementById('stock-action')||{}).value || 'set';
      if(!window.currentProductId){ showNotification('No product selected','error'); return; }
      if(qty < 0){ showNotification('Quantity must be >= 0','error'); return; }
      updateStock(window.currentProductId, act, qty);
      closeStockModal();
    }catch(e){ console.error(e); }
  };

  window.toggleStatus = async function(productId){
    try{
      const csrf = getCsrf();
      const res = await fetch(`/admin/products/${productId}/toggle-status`, { method:'PATCH', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': csrf,'Accept':'application/json' } });
      const data = await res.json().catch(()=>({}));
      if(data.success){ showNotification(data.message || 'Status updated','success'); location.reload(); } else { showNotification(data.message || 'Failed to toggle status','error'); }
    }catch(e){ console.error('toggleStatus error', e); showNotification('Error toggling status','error'); }
  };

  window.deleteProduct = async function(productId){
    try{
      if(!confirm('Are you sure you want to delete this product? This action cannot be undone.')) return;
      const csrf = getCsrf();
      const res = await fetch(`/admin/products/${productId}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' } });
      if(res.ok){ showNotification('Product deleted successfully!', 'success'); setTimeout(()=>{ window.location.href = redirectIndex; }, 1200); }
      else { const data = await res.json().catch(()=>({})); showNotification(data.message || 'Failed to delete product','error'); }
    }catch(e){ console.error('deleteProduct error', e); showNotification('Network error occurred','error'); }
  };

  window.showNotification = function(message, type='info'){
    try{
      const notification = document.createElement('div'); notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full ${ type==='success' ? 'bg-green-500 text-white' : type==='error' ? 'bg-red-500 text-white' : 'bg-blue-500 text-white' }`;
      notification.textContent = message;
      document.body.appendChild(notification);
      setTimeout(()=>{ notification.classList.remove('translate-x-full'); },100);
      setTimeout(()=>{ notification.classList.add('translate-x-full'); setTimeout(()=>{ if(notification.parentNode) notification.remove(); },300); },3500);
    }catch(e){ console.error('notification error', e); }
  };

})();
