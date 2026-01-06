{{--
    Login Page

    User login এর জন্য form।

    "Remember Me" checkbox ব্যবহার করা হয়েছে।
    চেক করলে user দীর্ঘদিন logged-in থাকবে।

    @see https://laravel.com/docs/authentication#remembering-users
--}}
@extends('layouts.app')

@section('title', 'লগইন')

@section('content')
    <div class="card" style="max-width: 400px; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title text-center">লগইন করুন</h2>
        </div>

        {{-- Login Form --}}
        <form action="{{ route('login') }}" method="POST">
            @csrf

            {{-- Email Field --}}
            <div class="form-group">
                <label for="email">ইমেইল</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password Field --}}
            <div class="form-group">
                <label for="password">পাসওয়ার্ড</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    required
                >
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{--
                Remember Me Checkbox

                name="remember" - AuthController এ $request->boolean('remember') দিয়ে চেক করা হয়
                চেক করলে Auth::attempt() এ remember parameter true হয়
            --}}
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: normal;">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    আমাকে মনে রাখুন
                </label>
            </div>

            {{-- Submit Button --}}
            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    লগইন করুন
                </button>
            </div>
        </form>

        {{-- Register Link --}}
        <p class="text-center mt-2" style="font-size: 0.875rem;">
            অ্যাকাউন্ট নেই?
            <a href="{{ route('register') }}">রেজিস্টার করুন</a>
        </p>
    </div>
@endsection
