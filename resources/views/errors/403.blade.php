<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>403 Forbidden · INVORA</title>
    <link rel="stylesheet" href="{{ asset('css/invora.css') }}">
</head>
<body>
<div class="error-page">
    <div class="error-card">
        <div class="error-code">403</div>
        <h1>Forbidden</h1>
        <p>You are not authorized to access this page.</p>
        <a class="btn primary" href="{{ url('/dashboard') }}">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
