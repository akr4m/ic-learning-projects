{{--
    Registration Page

    নতুন User registration এর জন্য form।

    Form Handling:
    - @csrf directive দিয়ে CSRF token যোগ করা হয় (Security)
    - @error directive দিয়ে validation error দেখানো হয়
    - old() function দিয়ে form submit fail হলে পুরাতন value রাখা হয়

    @see https://laravel.com/docs/blade#forms
--}}
@extends('layouts.app')

@section('title', 'রেজিস্টার')

@section('content')
    <div class="card" style="max-width: 400px; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title text-center">নতুন অ্যাকাউন্ট তৈরি করুন</h2>
        </div>

        {{--
            Registration Form

            action: form submit হলে কোথায় যাবে
            method: HTTP method (POST)
        --}}
        <form action="{{ route('register') }}" method="POST">
            {{--
                @csrf - Cross-Site Request Forgery protection
                প্রতিটি POST form এ এটি mandatory।
                Hidden input field হিসেবে token যোগ করে।
            --}}
            @csrf

            {{-- Name Field --}}
            <div class="form-group">
                <label for="name">নাম</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    required
                    autofocus
                >
                {{--
                    @error directive - Validation error থাকলে দেখায়
                    $message variable এ error message থাকে
                --}}
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

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
                <small style="color: #666;">সর্বনিম্ন ৮ অক্ষর</small>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password Confirmation Field --}}
            <div class="form-group">
                <label for="password_confirmation">পাসওয়ার্ড নিশ্চিত করুন</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    required
                >
            </div>

            {{-- Submit Button --}}
            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    রেজিস্টার করুন
                </button>
            </div>
        </form>

        {{-- Login Link --}}
        <p class="text-center mt-2" style="font-size: 0.875rem;">
            ইতিমধ্যে অ্যাকাউন্ট আছে?
            <a href="{{ route('login') }}">লগইন করুন</a>
        </p>
    </div>
@endsection
