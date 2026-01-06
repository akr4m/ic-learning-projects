{{-- Blog List Page (Home) --}}
@extends('layouts.app')

@section('title', 'হোম')
@section('meta_description', 'প্রোগ্রামিং ও ওয়েব ডেভেলপমেন্ট বিষয়ক বাংলায় সেরা ব্লগ।')

@section('content')

    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <div class="text-white">
                    <span class="inline-block px-4 py-1.5 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium mb-4">Featured Post</span>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4 lg:mb-6">Laravel 11: নতুন ফিচার ও আপডেট গাইড</h1>
                    <p class="text-lg text-blue-100 dark:text-gray-300 leading-relaxed mb-6">Laravel-এর নতুন version 11-এ কী কী নতুন ফিচার এসেছে, কীভাবে upgrade করবেন - সব বিস্তারিত জানুন।</p>
                    <div class="flex flex-wrap items-center gap-4 text-blue-100 dark:text-gray-400 mb-8">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span>রাফি আহমেদ</span>
                        </div>
                        <span>|</span>
                        <span>২০ জানুয়ারি, ২০২৫</span>
                        <span>|</span>
                        <span>১০ মিনিট পড়া</span>
                    </div>
                    <a href="/blog/laravel-11-guide" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors shadow-lg hover:shadow-xl">
                        পড়ুন
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                <div class="hidden lg:block">
                    <div class="aspect-[4/3] bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20">
                        <div class="text-center">
                            <div class="w-24 h-24 mx-auto mb-4 bg-white/20 rounded-2xl flex items-center justify-center text-white text-4xl font-bold">L</div>
                            <p class="text-white/60 text-sm">Featured Image</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Blog Posts Section --}}
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">সাম্প্রতিক পোস্ট</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">আমাদের latest articles এবং tutorials</p>
                </div>
                {{-- View Switcher (decorative) --}}
                <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
                    <button class="p-2 bg-white dark:bg-gray-700 rounded-md shadow-sm text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </button>
                    <button class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 rounded-md transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>

            {{-- 2 col layout: main (2/3) + sidebar (1/3) --}}
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    {{-- Blog Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                        <x-blog-card title="PHP 8.4 এর নতুন ফিচারগুলো দেখুন" excerpt="PHP 8.4-এ কী কী নতুন ফিচার এসেছে যেমন Property Hooks, new array functions এবং আরও অনেক কিছু।" category="PHP" categoryColor="purple" author="রাফি আহমেদ" date="১৮ জানুয়ারি, ২০২৫" readTime="৭ মিনিট" url="/blog/php-84-features" />
                        <x-blog-card title="Tailwind CSS v4.0 দিয়ে Modern UI Design" excerpt="Tailwind CSS-এর নতুন version 4.0-এ আসা নতুন utilities, improved performance শিখুন।" category="CSS" categoryColor="cyan" author="সাকিব হাসান" date="১৫ জানুয়ারি, ২০২৫" readTime="৮ মিনিট" url="/blog/tailwind-v4" />
                        <x-blog-card title="JavaScript Async/Await সহজ ভাষায়" excerpt="Asynchronous JavaScript বোঝা কঠিন মনে হয়? এই tutorial-এ Promise, async/await সহজ বাংলায় শিখুন।" category="JavaScript" categoryColor="orange" author="তানভীর রহমান" date="১২ জানুয়ারি, ২০২৫" readTime="১০ মিনিট" url="/blog/javascript-async-await" />
                        <x-blog-card title="MySQL Performance Optimization Tips" excerpt="আপনার database queries slow? এই guide-এ MySQL optimization techniques শিখুন।" category="Database" categoryColor="green" author="ফারহান আহমেদ" date="১০ জানুয়ারি, ২০২৫" readTime="১২ মিনিট" url="/blog/mysql-optimization" />
                        <x-blog-card title="Docker দিয়ে Laravel Development Environment" excerpt="Docker Compose ব্যবহার করে complete Laravel development environment সেটআপ করুন।" category="DevOps" categoryColor="pink" author="রাফি আহমেদ" date="৮ জানুয়ারি, ২০২৫" readTime="১৫ মিনিট" url="/blog/docker-laravel" />
                        <x-blog-card title="Vue 3 Composition API Complete Guide" excerpt="Vue 3-এর নতুন Composition API কীভাবে কাজ করে এবং কেন better সেটা examples সহ বুঝুন।" category="JavaScript" categoryColor="orange" author="সাকিব হাসান" date="৫ জানুয়ারি, ২০২৫" readTime="১১ মিনিট" url="/blog/vue3-composition-api" />
                    </div>

                    {{-- Pagination --}}
                    <nav class="mt-12 flex items-center justify-center">
                        <div class="flex items-center gap-2">
                            <a href="#" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                            <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium">1</a>
                            <a href="#" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">2</a>
                            <a href="#" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">3</a>
                            <span class="px-2 text-gray-500">...</span>
                            <a href="#" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">10</a>
                            <a href="#" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </nav>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    @include('components.sidebar')
                </div>
            </div>
        </div>
    </section>

    {{-- Newsletter Section --}}
    <section class="bg-gray-100 dark:bg-gray-800 py-12 lg:py-16 transition-colors">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-16 h-16 mx-auto mb-6 bg-blue-100 dark:bg-blue-900/50 rounded-2xl flex items-center justify-center">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-3">আপডেট পেতে সাবস্ক্রাইব করুন</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-xl mx-auto">নতুন পোস্ট publish হলে সরাসরি আপনার inbox-এ পৌঁছে যাবে। কোন spam নেই।</p>
            <form action="#" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <input type="email" name="email" placeholder="আপনার ইমেইল দিন" class="flex-1 px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors whitespace-nowrap">সাবস্ক্রাইব</button>
            </form>
        </div>
    </section>

@endsection
