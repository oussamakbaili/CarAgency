<!DOCTYPE html>
<html>
<head>
    <title>Debug Car Show</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .error { color: red; background: #ffe6e6; padding: 10px; border: 1px solid #ff0000; }
        .info { color: blue; background: #e6f3ff; padding: 10px; border: 1px solid #0066cc; }
    </style>
</head>
<body>
    <h1>Debug Car Show Page</h1>
    
    <div class="info">
        <h3>Request Information:</h3>
        <p><strong>Car ID:</strong> {{ $car_id }}</p>
        <p><strong>Car Exists:</strong> {{ $car_exists ? 'Yes' : 'No' }}</p>
        <p><strong>Current URL:</strong> {{ url()->current() }}</p>
        <p><strong>User Authenticated:</strong> {{ auth()->check() ? 'Yes' : 'No' }}</p>
        @if(auth()->check())
            <p><strong>User Role:</strong> {{ auth()->user()->role }}</p>
            <p><strong>User ID:</strong> {{ auth()->user()->id }}</p>
        @endif
    </div>
    
    @if(isset($error))
    <div class="error">
        <h3>Error:</h3>
        <p>{{ $error }}</p>
    </div>
    @endif
    
    <p><a href="{{ route('client.cars.index') }}">← Back to Cars List</a></p>
</body>
</html>
