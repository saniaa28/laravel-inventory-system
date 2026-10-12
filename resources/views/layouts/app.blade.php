<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'INVORA') · INVORA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/invora.css') }}">
</head>
<body>
<div class="app-shell">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark">I</div>
            <div>
                <strong>INVORA</strong>
                <small>Inventory Management</small>
            </div>
        </div>

        <nav>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">▦ <span>Dashboard</span></a>

            <div class="nav-label">MAIN</div>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">▣ <span>Products</span></a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"># <span>Categories</span></a>
            @endif

            <div class="nav-label">INVENTORY</div>
            <a href="{{ route('stock.in') }}" class="nav-link {{ request()->routeIs('stock.in*') ? 'active' : '' }}">↓ <span>Stock In</span></a>
            <a href="{{ route('stock.out') }}" class="nav-link {{ request()->routeIs('stock.out*') ? 'active' : '' }}">↑ <span>Stock Out</span></a>
            <a href="{{ route('stock.history') }}" class="nav-link {{ request()->routeIs('stock.history') ? 'active' : '' }}">↺ <span>Stock History</span></a>

            @if(auth()->user()->isAdmin())
                <div class="nav-label">MANAGEMENT</div>
                <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">▱ <span>Suppliers</span></a>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">♙ <span>Users</span></a>
            @endif

            <div class="nav-label">REPORTS</div>
            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">▥ <span>Inventory Reports</span></a>
        </nav>
    </aside>

    {{-- KONTEN --}}
    <div class="main">
        <header class="topbar">
            <div class="top-title">@yield('title')</div>
            <div class="user-chip">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->username ?? auth()->user()->name, 0, 1)) }}</div>
                <span>{{ auth()->user()->username ?? auth()->user()->name }}</span>
                <small>{{ ucfirst(auth()->user()->role) }}</small>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">↪ Logout</button>
                </form>
            </div>
        </header>

        <main class="content">
            @if(session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif
            @if(session('error'))
                <x-alert type="danger">{{ session('error') }}</x-alert>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
