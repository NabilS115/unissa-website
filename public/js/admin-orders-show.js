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
      const res = await fetch(updateUrl, { 
        method:'POST', 
        headers: { 
          'Content-Type':'application/json',
          'X-CSRF-TOKEN': getCsrf(),
          'Accept':'application/json',
          'X-Requested-With':'XMLHttpRequest' 
        }, 
        body: JSON.stringify({ status: newStatus, _method: 'PATCH' }) 
      });
      const data = await res.json().catch(()=>({}));
      if(res.ok && data.success){ 
        showNotification(data.message || 'Order status updated successfully','success'); 
        // Update UI without full page reload
        updateStatusUI(newStatus);
      } else { 
        showNotification(data.message || 'Failed to update status','error'); 
      }
    }catch(e){ 
      console.error('Status update error', e); 
      showNotification('Network error occurred','error'); 
    }
  };

  function updateStatusUI(newStatus) {
    // Update the status badge
    const statusBadge = document.querySelector('.inline-flex.items-center.px-4.py-2.rounded-full');
    if (statusBadge) {
      // Remove old color classes
      statusBadge.className = statusBadge.className.replace(/bg-\w+-50|text-\w+-800/g, '');
      
      // Add new color classes based on status
      const colorClasses = {
        'pending': 'bg-yellow-50 text-yellow-800',
        'confirmed': 'bg-blue-50 text-blue-800', 
        'processing': 'bg-purple-50 text-purple-800',
        'ready_for_pickup': 'bg-orange-50 text-orange-800',
        'picked_up': 'bg-green-50 text-green-800',
        'cancelled': 'bg-red-50 text-red-800'
      };
      
      statusBadge.className += ' ' + (colorClasses[newStatus] || 'bg-gray-50 text-gray-800');
      statusBadge.textContent = newStatus.split('_').map(word => 
        word.charAt(0).toUpperCase() + word.slice(1)
      ).join(' ');
    }

    // Refresh the quick actions buttons
    setTimeout(() => location.reload(), 1000);
  }

  window.updatePaymentStatus = async function(newPaymentStatus = null){
    try{
      // If no status provided, get from dropdown
      if(!newPaymentStatus) {
        const sel = document.getElementById('payment-status-select'); 
        if(!sel) return; 
        newPaymentStatus = sel.value;
      }
      
      const updateUrl = bs.updatePaymentUrl || ('/admin/orders/' + (bs.orderId||'') + '/update-payment-status');
      const res = await fetch(updateUrl, { 
        method:'POST', 
        headers:{ 
          'Content-Type':'application/json',
          'X-CSRF-TOKEN': getCsrf(),
          'Accept':'application/json',
          'X-Requested-With':'XMLHttpRequest' 
        }, 
        body: JSON.stringify({ payment_status: newPaymentStatus, _method: 'PATCH' }) 
      });
      
      const data = await res.json().catch(()=>({})); 
      if(res.ok && data.success){ 
        showNotification(data.message || 'Payment status updated successfully','success'); 
        updatePaymentStatusUI(newPaymentStatus);
      } else { 
        showNotification(data.message || 'Failed to update payment status','error'); 
      }
    }catch(e){ 
      console.error('Payment status update error', e); 
      showNotification('Network error occurred','error'); 
    }
  };

  function updatePaymentStatusUI(newPaymentStatus) {
    const dropdown = document.getElementById('payment-status-select');
    if (dropdown) {
      // Remove old color classes
      dropdown.className = dropdown.className.replace(/text-\w+-700|bg-\w+-100/g, '');
      
      // Add new color classes based on status
      const colorClasses = {
        'paid': 'text-green-700 bg-green-100',
        'pending': 'text-yellow-700 bg-yellow-100',
        'refunded': 'text-purple-700 bg-purple-100',
        'failed': 'text-red-700 bg-red-100'
      };
      
      dropdown.className += ' ' + (colorClasses[newPaymentStatus] || 'text-red-700 bg-red-100');
      dropdown.setAttribute('data-current-status', newPaymentStatus);
    }
  }

  window.testOrderStatusFunctions = function(){ showNotification('Order status functions are loaded!', 'success'); return 'Order status functions are available'; };

})();
