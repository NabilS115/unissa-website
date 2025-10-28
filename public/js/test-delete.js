async function testDelete(productId) {
    const resultDiv = document.getElementById('result');
    if (resultDiv) resultDiv.innerHTML = 'Testing delete...';

    try {
        console.log('Attempting to delete product ID:', productId);

        const response = await fetch(`/admin/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);

        if (response.ok) {
            const data = await response.json().catch(()=>({}));
            console.log('Response data:', data);
            if (resultDiv) resultDiv.innerHTML = `<div style="color: green;">Success: ${data.message || 'Deleted'}</div>`;
        } else {
            const errorData = await response.json().catch(() => ({}));
            console.log('Error data:', errorData);
            if (resultDiv) resultDiv.innerHTML = `<div style="color: red;">Error ${response.status}: ${errorData.message || response.statusText}</div>`;
        }
    } catch (error) {
        console.error('Network error:', error);
        if (resultDiv) resultDiv.innerHTML = `<div style="color: red;">Network error: ${error.message}</div>`;
    }
}
