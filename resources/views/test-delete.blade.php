<!DOCTYPE html>
<html>
<head>
    <title>Test Delete Functionality</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Delete Product</h1>
    @if(App\Models\Product::count() > 0)
        @php $testProduct = App\Models\Product::first(); @endphp
        <p>Testing with product: {{ $testProduct->name }} (ID: {{ $testProduct->id }})</p>
        
        <button onclick="testDelete({{ $testProduct->id }})" style="background: red; color: white; padding: 10px; border: none;">
            Test Delete Product
        </button>
        
        <div id="result" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc;"></div>
    @else
        <p>No products found to test</p>
    @endif

    <script>
        async function testDelete(productId) {
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = 'Testing delete...';
            
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
                    const data = await response.json();
                    console.log('Response data:', data);
                    resultDiv.innerHTML = `<div style="color: green;">Success: ${data.message}</div>`;
                } else {
                    const errorData = await response.json().catch(() => ({}));
                    console.log('Error data:', errorData);
                    resultDiv.innerHTML = `<div style="color: red;">Error ${response.status}: ${errorData.message || response.statusText}</div>`;
                }
            } catch (error) {
                console.error('Network error:', error);
                resultDiv.innerHTML = `<div style="color: red;">Network error: ${error.message}</div>`;
            }
        }
    </script>
</body>
</html>