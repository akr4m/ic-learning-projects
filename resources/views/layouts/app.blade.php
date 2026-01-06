{{--
    Main Layout Template

    এটি application এর প্রধান layout file।
    অন্য সব view এই layout কে extend করে।

    @yield এবং @section দিয়ে content inject করা হয়।

    মিনিমাল CSS ব্যবহার করা হয়েছে - কোনো external library নেই।

    @see https://laravel.com/docs/blade#layouts
--}}
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Task Manager') - টাস্ক ম্যানেজার</title>

    {{-- Minimal Internal CSS - কোনো external library নেই --}}
    <style>
        /* CSS Reset ও Base Styles */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Container */
        .container {
            width: 100%;
            max-width: 960px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header */
        header {
            background: #fff;
            border-bottom: 1px solid #ddd;
            padding: 1rem 0;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            text-decoration: none;
        }

        nav {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        nav a {
            color: #555;
            text-decoration: none;
        }

        nav a:hover {
            color: #000;
        }

        .user-name {
            color: #666;
            font-size: 0.875rem;
        }

        /* Main Content */
        main {
            flex: 1;
            padding: 2rem 0;
        }

        /* Footer */
        footer {
            background: #fff;
            border-top: 1px solid #ddd;
            padding: 1rem 0;
            text-align: center;
            color: #666;
            font-size: 0.875rem;
        }

        /* Alert Messages */
        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border: 1px solid transparent;
            border-radius: 4px;
            font-size: 0.875rem;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary {
            background: #333;
            color: #fff;
        }

        .btn-primary:hover {
            background: #555;
        }

        .btn-secondary {
            background: #fff;
            color: #333;
            border-color: #ddd;
        }

        .btn-secondary:hover {
            background: #f5f5f5;
        }

        .btn-danger {
            background: #dc3545;
            color: #fff;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #333;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .form-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Cards */
        .card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .card-header {
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #eee;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background: #f9f9f9;
            font-weight: 600;
        }

        .table tr:hover {
            background: #f5f5f5;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 4px;
        }

        .badge-pending {
            background: #ffc107;
            color: #333;
        }

        .badge-in_progress {
            background: #17a2b8;
            color: #fff;
        }

        .badge-completed {
            background: #28a745;
            color: #fff;
        }

        /* Utilities */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mt-2 { margin-top: 1rem; }

        .flex {
            display: flex;
            gap: 0.5rem;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 0.25rem;
            list-style: none;
            margin-top: 1rem;
        }

        .pagination a,
        .pagination span {
            display: block;
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            color: #333;
            text-decoration: none;
        }

        .pagination a:hover {
            background: #f5f5f5;
        }

        .pagination .active span {
            background: #333;
            color: #fff;
            border-color: #333;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        /* Inline form for delete buttons */
        .inline-form {
            display: inline;
        }
    </style>
</head>
<body>
    {{--
        Header Section
        Navigation এবং User info এখানে থাকে।
    --}}
    <header>
        <div class="container">
            <a href="{{ route('home') }}" class="logo">Task Manager</a>

            <nav>
                {{--
                    @auth - শুধুমাত্র logged-in user দেখবে
                    @guest - শুধুমাত্র logged-out user দেখবে
                --}}
                @auth
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <a href="{{ route('tasks.index') }}">টাস্ক</a>
                    {{--
                        Logout Form - POST method ব্যবহার করা হচ্ছে
                        Security এর জন্য logout সবসময় POST হওয়া উচিত
                    --}}
                    <form action="{{ route('logout') }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary">লগআউট</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">লগইন</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">রেজিস্টার</a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Main Content --}}
    <main>
        <div class="container">
            {{--
                Flash Messages
                Session এ success বা error message থাকলে দেখাবে।
                with('success', 'message') দিয়ে পাঠানো হয়।
            --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            {{--
                @yield('content') - Child view এর content এখানে inject হবে
            --}}
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Task Manager - Laravel শেখার প্রজেক্ট</p>
        </div>
    </footer>
</body>
</html>
