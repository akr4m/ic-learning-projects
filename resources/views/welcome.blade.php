{{--
    Welcome Page (Home Page)

    এটি application এর landing page।
    Logged-in user টাস্ক লিস্টে redirect হবে।
    Logged-out user এখানে welcome message দেখবে।

    @extends দিয়ে parent layout উল্লেখ করা হয়।
    @section('content') দিয়ে content define করা হয়।
--}}
@extends('layouts.app')

@section('title', 'স্বাগতম')

@section('content')
    <div class="card" style="max-width: 600px; margin: 2rem auto; text-align: center;">
        <h1 style="font-size: 2rem; margin-bottom: 1rem;">Task Manager</h1>

        <p style="color: #666; margin-bottom: 1.5rem;">
            আপনার দৈনন্দিন কাজগুলো সহজে ম্যানেজ করুন।
            <br>
            Laravel দিয়ে তৈরি একটি সহজ Task Management Application।
        </p>

        @guest
            {{-- Guest users দেখবে --}}
            <div class="flex" style="justify-content: center;">
                <a href="{{ route('login') }}" class="btn btn-secondary">লগইন করুন</a>
                <a href="{{ route('register') }}" class="btn btn-primary">রেজিস্টার করুন</a>
            </div>
        @else
            {{-- Logged-in users দেখবে --}}
            <a href="{{ route('tasks.index') }}" class="btn btn-primary">আমার টাস্ক দেখুন</a>
        @endguest
    </div>

    {{-- Features Section --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-top: 2rem;">
        <div class="card">
            <h3 style="margin-bottom: 0.5rem;">টাস্ক ম্যানেজমেন্ট</h3>
            <p style="color: #666; font-size: 0.875rem;">
                সহজেই টাস্ক তৈরি করুন, এডিট করুন এবং মুছে ফেলুন। স্ট্যাটাস ট্র্যাক করুন।
            </p>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 0.5rem;">ফাইল সংযুক্তি</h3>
            <p style="color: #666; font-size: 0.875rem;">
                প্রতিটি টাস্কের সাথে ফাইল আপলোড করুন। PDF, DOC, Image সব সাপোর্টেড।
            </p>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 0.5rem;">সিকিউর অ্যাক্সেস</h3>
            <p style="color: #666; font-size: 0.875rem;">
                শুধুমাত্র আপনি আপনার টাস্ক দেখতে ও ম্যানেজ করতে পারবেন।
            </p>
        </div>
    </div>
@endsection
