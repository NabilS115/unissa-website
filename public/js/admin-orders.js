(function(){
  function safe(fn){ try{ fn(); } catch(e){ console.error(e); } }

  // Expose helper to get CSS classes for statuses
  window.getStatusColorClass = function(status) {
    const colors = {
      'pending': 'text-yellow-700 bg-yellow-50',
      'confirmed': 'text-blue-700 bg-blue-50',
      'processing': 'text-purple-700 bg-purple-50',
      'ready_for_pickup': 'text-orange-700 bg-orange-50',
      'picked_up': 'text-green-700 bg-green-50',
      'cancelled': 'text-red-700 bg-red-50'
    };
    return colors[status] || 'text-gray-700 bg-gray-50';
  };

  // Expose helper to get CSS classes for payment statuses
  window.getPaymentStatusColorClass = function(paymentStatus) {
    const colors = {
      'paid': 'text-green-700 bg-green-100',
      'pending': 'text-yellow-700 bg-yellow-100',
      'refunded': 'text-purple-700 bg-purple-100',
      'failed': 'text-red-700 bg-red-100'
    };
    return colors[paymentStatus] || 'text-gray-700 bg-gray-100';
  };

  window.showNotification = function(message, type) {
    try {
      const notification = document.createElement('div');
      notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${ type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }`;
      notification.textContent = message;
      document.body.appendChild(notification);
      setTimeout(() => { notification.remove(); }, 3000);
    } catch (e) { console.error('showNotification error', e); }
  };

  window.testOrderManagementFunctions = function() {
    console.log('Testing order management functionality...');
    showNotification('Order management functions are loaded!', 'success');
    return 'Order management functions are available';
  };

  document.addEventListener('DOMContentLoaded', function() {
    safe(function(){
      const bs = window.__adminOrders || {};
      // Status update handling
      document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', async function() {
          const orderId = this.dataset.orderId;
          const newStatus = this.value;
          const originalValue = this.getAttribute('data-original') || this.value;
          try {
            const baseUrl = window.location.origin;
            const updateUrl = `${baseUrl}/admin/orders/${orderId}/status`;
            const csrf = (bs && bs.csrf) || (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            const response = await fetch(updateUrl, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: JSON.stringify({ status: newStatus, _method: 'PATCH' })
            });
            const data = await response.json().catch(()=>({}));
            if (response.ok && data.success) {
              this.value = newStatus;
              this.className = this.className.replace(/text-\w+-700 bg-\w+-50/g, '');
              const colorClass = getStatusColorClass(newStatus);
              this.className += ` ${colorClass}`;
              showNotification(data.message || 'Order status updated successfully', 'success');
              this.setAttribute('data-original', newStatus);
            } else {
              this.value = originalValue;
              showNotification((data && data.message) ? data.message : 'Failed to update status', 'error');
            }
          } catch (error) {
            console.error('Status update error:', error);
            this.value = originalValue;
            showNotification('Network error occurred', 'error');
          }
        });
        select.setAttribute('data-original', select.value);
      });

      // Payment status update handling
      document.querySelectorAll('.payment-status-select').forEach(select => {
        select.addEventListener('change', async function() {
          const orderId = this.dataset.orderId;
          const newPaymentStatus = this.value;
          const originalValue = this.getAttribute('data-original') || this.value;
          try {
            const baseUrl = window.location.origin;
            const updateUrl = `${baseUrl}/admin/orders/${orderId}/payment-status`;
            const csrf = (bs && bs.csrf) || (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            const response = await fetch(updateUrl, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: JSON.stringify({ payment_status: newPaymentStatus, _method: 'PATCH' })
            });
            const data = await response.json().catch(()=>({}));
            if (response.ok && data.success) {
              this.value = newPaymentStatus;
              this.className = this.className.replace(/text-\w+-700 bg-\w+-100/g, '');
              const colorClass = getPaymentStatusColorClass(newPaymentStatus);
              this.className += ` ${colorClass}`;
              showNotification(data.message || 'Payment status updated successfully', 'success');
              this.setAttribute('data-original', newPaymentStatus);
            } else {
              this.value = originalValue;
              showNotification((data && data.message) ? data.message : 'Failed to update payment status', 'error');
            }
          } catch (error) {
            console.error('Payment status update error:', error);
            this.value = originalValue;
            showNotification('Network error occurred', 'error');
          }
        });
        select.setAttribute('data-original', select.value);
      });

      // Bulk actions
      const selectAll = document.getElementById('select-all');
      const orderCheckboxes = document.querySelectorAll('.order-checkbox');
      const bulkActions = document.getElementById('bulk-actions');
      const selectedCount = document.getElementById('selected-count');
      const bulkStatus = document.getElementById('bulk-status');
      const applyBulk = document.getElementById('apply-bulk');
      const cancelBulk = document.getElementById('cancel-bulk');

      if (selectAll && orderCheckboxes.length > 0) {
        function updateBulkActions() {
          const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
          const count = checkedBoxes.length;
          if (count > 0) { bulkActions.classList.remove('hidden'); selectedCount.textContent = `${count} order${count > 1 ? 's' : ''} selected`; }
          else { bulkActions.classList.add('hidden'); }
        }

        selectAll.addEventListener('change', function() { orderCheckboxes.forEach(cb => cb.checked = this.checked); updateBulkActions(); });
        orderCheckboxes.forEach(cb => cb.addEventListener('change', updateBulkActions));

        if (applyBulk) {
          applyBulk.addEventListener('click', async function() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
            const status = bulkStatus.value;
            if (!status) { showNotification('Please select an action', 'error'); return; }
            if (orderIds.length === 0) { showNotification('Please select at least one order', 'error'); return; }
            try {
              const response = await fetch((bs && bs.bulkUpdateUrl) || '/admin/orders/bulk-update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': (bs && bs.csrf) || '' , 'Accept':'application/json' },
                body: JSON.stringify({ order_ids: orderIds, status: status })
              });
              const data = await response.json().catch(()=>({}));
              if (data.success || response.ok) { showNotification(data.message || 'Orders updated successfully', 'success'); setTimeout(()=> location.reload(), 1000); }
              else { showNotification(data.message || 'Bulk action failed', 'error'); }
            } catch (error) { console.error('Bulk update error:', error); showNotification('Network error occurred', 'error'); }
          });
        }

        if (cancelBulk) {
          cancelBulk.addEventListener('click', function(){ orderCheckboxes.forEach(cb=> cb.checked = false); if (selectAll) selectAll.checked = false; updateBulkActions(); });
        }
      }

      // Dropdown-close on outside click
      document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('dropdown-menu-orders');
        const button = event.target.closest('[onclick*="toggleDropdown"]');
        if (!button && dropdown && !dropdown.contains(event.target)) { dropdown.classList.add('hidden'); }
      });

    });
  });

  // Simple helpers exposed globally
  window.toggleDropdown = function(id) { const dropdown = document.getElementById(id); if (dropdown) dropdown.classList.toggle('hidden'); };

  window.openImportModal = function(type) { const inp = document.getElementById('importType'); const title = document.getElementById('modalTitle'); if (inp) inp.value = type; if (title) title.textContent = `Import ${type.charAt(0).toUpperCase() + type.slice(1)}`; const modal = document.getElementById('importModal'); if (modal) modal.classList.remove('hidden'); };
  window.closeImportModal = function() { const modal = document.getElementById('importModal'); if (modal) modal.classList.add('hidden'); const form = document.getElementById('importForm'); if (form) form.reset(); };

  window.handleImport = async function() {
    try {
      const form = document.getElementById('importForm');
      const fileInput = document.getElementById('importFile');
      const type = document.getElementById('importType').value;
      if (!fileInput.files[0]) { showNotification('Please select a CSV file', 'error'); return; }
      const fd = new FormData(); fd.append('csv_file', fileInput.files[0]); fd.append('_token', (window.__adminOrders && window.__adminOrders.csrf) || (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content')) );
      const response = await fetch(`/admin/${type}/import`, { method: 'POST', body: fd });
      const result = await response.json().catch(()=>({}));
      if (response.ok) { showNotification(result.message || 'Import completed successfully', 'success'); closeImportModal(); window.location.reload(); } else { showNotification(result.message || 'Import failed', 'error'); }
    } catch (error) { console.error('Import error:', error); showNotification('Import failed. Please try again.', 'error'); }
  };

})();
