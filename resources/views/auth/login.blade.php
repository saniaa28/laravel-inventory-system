<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login · INVORA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/invora.css') }}">
</head>
<body>
<div class="login-page">
    <div class="login-card">
        <div class="login-brand">
            <div class="brand-mark large">I</div>
            <h1>INVORA</h1>
            <p>Smart Inventory Management</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="form-grid">
            @csrf
            <x-field label="Username" name="username" required autofocus />
            <x-field label="Password" name="password" type="password" required />
            <button type="submit" class="btn primary full">Sign In</button>
        </form>

        <div class="demo">
            <b>Demo accounts</b><br>
            Admin: admin / admin123<br>
            Staff: staff / staff123
        </div>
    </div>
</div>
</body>
</html>
