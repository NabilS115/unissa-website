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

    <script src="/js/test-delete.js"></script>
</body>
</html>