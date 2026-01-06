{{--
    Main Layout Template

    এটি হলো মূল layout ফাইল যা সব পেজে ব্যবহৃত হবে।
    @yield() দিয়ে dynamic content inject করা হয়।
    Blade Template Engine-এর inheritance feature ব্যবহার করা হয়েছে।
--}}
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic page title --}}
    <title>@yield('title', 'রেসিপি শেয়ারিং')</title>

    {{-- Vite দিয়ে CSS এবং JS load করা --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    {{-- Navigation Bar --}}
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                {{-- Logo/Brand --}}
                <a href="{{ route('recipes.index') }}" class="text-xl font-bold text-gray-800">
                    রেসিপি শেয়ারিং
                </a>

                {{-- Navigation Links --}}
                <div class="flex gap-4">
                    <a href="{{ route('recipes.index') }}"
                       class="text-gray-600 hover:text-gray-900 transition">
                        সকল রেসিপি
                    </a>
                    <a href="{{ route('recipes.create') }}"
                       class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
                        নতুন রেসিপি
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages - Success/Error notifications --}}
    <div class="max-w-6xl mx-auto px-4 mt-4">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Main Content Area --}}
    <main class="max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-6xl mx-auto px-4 py-6 text-center text-gray-500">
            <p>Laravel শেখার জন্য একটি ডেমো প্রজেক্ট</p>
        </div>
    </footer>

    {{-- Additional Scripts --}}
    @stack('scripts')
</body>
</html>
