// Extracted cart page JS: updateQuantity handler
(function(){
    async function updateQuantity(formId, change) {
        const form = document.getElementById(`cart-form-${formId}`);
        if (!form) return;
        const quantityInput = form.querySelector('input[name="quantity"]');
        if (!quantityInput) return;
        let newQuantity = parseInt(quantityInput.value, 10) + change;
        if (isNaN(newQuantity) || newQuantity < 1) newQuantity = 1;
        if (newQuantity > 100) newQuantity = 100;
        quantityInput.value = newQuantity;
        try {
            const formData = new FormData(form);
            formData.set('quantity', newQuantity);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content')) || ''
                }
            });
            if (response.ok) {
                const data = await response.json();
                console.log('Cart update response:', data, 'for formId:', formId);
                
                // Find the specific item's container and update ALL total elements within it
                const form = document.getElementById(`cart-form-${formId}`);
                const itemContainer = form ? form.closest('.group') : null;
                
                if (itemContainer && data.item_total) {
                    // Find ALL data-item-total elements within this specific item container
                    const itemTotalElements = itemContainer.querySelectorAll('[data-item-total]');
                    console.log('Found', itemTotalElements.length, 'item total elements to update');
                    
                    // Update each element (mobile and desktop versions)
                    itemTotalElements.forEach((element, index) => {
                        console.log(`Updating item total element ${index + 1} to: B$${parseFloat(data.item_total).toFixed(2)}`);
                        element.textContent = 'B$' + parseFloat(data.item_total).toFixed(2);
                    });
                } else {
                    console.log('Could not find item container. Form:', form, 'Container:', itemContainer);
                }
                if (data.cart_total) {
                    console.log('Updating cart totals to: B$' + parseFloat(data.cart_total).toFixed(2));
                    const subtotalElements = document.querySelectorAll('[data-subtotal]');
                    subtotalElements.forEach(el => {
                        console.log('Updating subtotal element:', el);
                        el.textContent = 'B$' + parseFloat(data.cart_total).toFixed(2);
                    });
                }
                if (data.total_items) {
                    const itemCountElements = document.querySelectorAll('[data-item-count]');
                    itemCountElements.forEach(el => el.textContent = data.total_items + ' items');
                }
                if (window.updateCartCount) window.updateCartCount(data.total_items || 0);
            } else {
                quantityInput.value = Math.max(1, newQuantity - change);
                alert('Failed to update cart. Please try again.');
            }
        } catch (error) {
            quantityInput.value = Math.max(1, newQuantity - change);
            alert('Error updating cart. Please try again.');
            console.error('Cart update error:', error);
        }
    }

    // expose globally for inline onclick callers
    window.updateQuantity = updateQuantity;
})();
