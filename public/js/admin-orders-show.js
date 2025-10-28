(() => {
  const bs = window.__adminOrder || {};
  const getCsrf = () => bs.csrf || (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content')) || '';

  function showNotification(message, type='info'){
    try{
      const notification = document.createElement('div'); notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${ type==='success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }`;
      notification.textContent = message; document.body.appendChild(notification);
      setTimeout(()=>{ if(notification.parentNode) notification.remove(); }, 3000);
    }catch(e){ console.error(e); }
  }

  window.updateOrderStatus = async function(newStatus){
    try{
      if(newStatus === 'cancelled' && !confirm('Are you sure you want to cancel this order?')) return;
      const updateUrl = bs.updateStatusUrl || ('/admin/orders/' + (bs.orderId||'') + '/update-status');
      const res = await fetch(updateUrl, { method:'POST', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN': getCsrf(),'Accept':'application/json','X-Requested-With':'XMLHttpRequest' }, body: JSON.stringify({ status: newStatus, _method: 'PATCH' }) });
      const data = await res.json().catch(()=>({}));
      if(res.ok && data.success){ showNotification(data.message || 'Order status updated successfully','success'); setTimeout(()=> location.reload(), 900); } else { showNotification(data.message || 'Failed to update status','error'); }
    }catch(e){ console.error('Status update error', e); showNotification('Network error occurred','error'); }
  };

  window.updatePaymentStatus = async function(){
    try{
      const sel = document.getElementById('payment-status-select'); if(!sel) return; const newPaymentStatus = sel.value;
      const updateUrl = bs.updatePaymentUrl || ('/admin/orders/' + (bs.orderId||'') + '/update-payment-status');
      const res = await fetch(updateUrl, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': getCsrf(),'Accept':'application/json','X-Requested-With':'XMLHttpRequest' }, body: JSON.stringify({ payment_status: newPaymentStatus, _method: 'PATCH' }) });
      const data = await res.json().catch(()=>({})); if(res.ok && data.success){ showNotification(data.message || 'Payment status updated successfully','success'); setTimeout(()=> location.reload(), 900); } else { showNotification(data.message || 'Failed to update payment status','error'); }
    }catch(e){ console.error('Payment status update error', e); showNotification('Network error occurred','error'); }
  };

  window.testOrderStatusFunctions = function(){ showNotification('Order status functions are loaded!', 'success'); return 'Order status functions are available'; };

})();
