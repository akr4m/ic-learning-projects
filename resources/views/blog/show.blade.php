{{-- Single Blog Post Page --}}
@extends('layouts.app')

@section('title', 'Laravel 11: নতুন ফিচার ও আপডেট গাইড')
@section('meta_description', 'Laravel 11-এর নতুন features, improvements এবং migration guide।')

@section('content')

<article>
    {{-- Breadcrumb --}}
    <div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="/" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">হোম</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="/blog" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">ব্লগ</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-900 dark:text-white font-medium truncate">Laravel 11 গাইড</span>
            </nav>
        </div>
    </div>

    {{-- Post Header --}}
    <header class="bg-white dark:bg-gray-800 transition-colors">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <a href="#" class="inline-block px-4 py-1.5 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 text-sm font-medium rounded-full mb-4 hover:bg-blue-200 dark:hover:bg-blue-900 transition-colors">Laravel</a>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white leading-tight mb-6">Laravel 11: নতুন ফিচার ও আপডেট গাইড</h1>
            <div class="flex flex-wrap items-center gap-4 lg:gap-6 text-gray-600 dark:text-gray-400">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg">র</div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">রাফি আহমেদ</p>
                        <p class="text-sm">Full Stack Developer</p>
                    </div>
                </div>
                <span class="hidden sm:inline text-gray-300 dark:text-gray-600">|</span>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>২০ জানুয়ারি, ২০২৫</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>১০ মিনিট পড়া</span>
                </div>
            </div>
        </div>
    </header>

    {{-- Featured Image --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4 mb-8 lg:mb-12">
        <div class="aspect-video bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-2xl overflow-hidden shadow-xl flex items-center justify-center">
            <div class="text-center text-white">
                <div class="w-24 h-24 mx-auto mb-4 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-5xl font-bold">L</div>
                <p class="text-white/60">Featured Image</p>
            </div>
        </div>
    </div>

    {{-- Post Content --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-8">

            {{-- Social Share (Desktop) --}}
            <aside class="hidden lg:block lg:col-span-1">
                <div class="sticky top-24 flex flex-col gap-3">
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">শেয়ার</span>
                    <a href="#" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-sky-500 hover:bg-sky-600 text-white rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href)" class="w-10 h-10 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    </button>
                </div>
            </aside>

            {{-- Main Content --}}
            <div class="lg:col-span-11">
                {{-- prose class থেকে typography plugin-এর styles আসে --}}
                <div class="prose prose-lg max-w-none dark:prose-invert prose-headings:text-gray-900 dark:prose-headings:text-white prose-headings:font-bold prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-a:no-underline hover:prose-a:underline prose-code:text-pink-600 dark:prose-code:text-pink-400 prose-code:bg-gray-100 dark:prose-code:bg-gray-800 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-pre:bg-gray-900 prose-img:rounded-xl prose-blockquote:border-blue-500 prose-blockquote:bg-blue-50 dark:prose-blockquote:bg-blue-900/20 prose-blockquote:py-1 prose-blockquote:px-4 prose-blockquote:rounded-r-lg">

                    <p class="lead text-xl text-gray-600 dark:text-gray-400">Laravel 11 একটি major release যা অনেক নতুন features এবং improvements নিয়ে এসেছে। এই guide-এ আমরা সব important changes cover করবো।</p>

                    <h2>Laravel 11 এ কী কী নতুন?</h2>
                    <p>Laravel team সবসময় developer experience improve করার চেষ্টা করে। এবারের release-এ তারা অনেক exciting features add করেছে।</p>

                    <h3>১. Simplified Application Structure</h3>
                    <p>Laravel 11-এ application structure অনেক simplified করা হয়েছে। অনেক unnecessary files এবং folders remove করা হয়েছে।</p>
                    <blockquote><p>"Less is more. Laravel 11 focuses on what matters most - your code."</p></blockquote>

                    <h3>২. New Artisan Commands</h3>
                    <p>নতুন কিছু artisan commands add করা হয়েছে:</p>
                    <pre><code class="language-bash">php artisan make:class MyClass
php artisan make:enum Status
php artisan make:interface MyInterface</code></pre>

                    <h3>৩. Improved Performance</h3>
                    <p>Laravel 11-এ অনেক performance improvements করা হয়েছে:</p>
                    <ul>
                        <li>Route caching ৪০% faster</li>
                        <li>Config loading optimized</li>
                        <li>Reduced memory footprint</li>
                        <li>Faster boot time</li>
                    </ul>

                    <h3>৪. Per-Second Rate Limiting</h3>
                    <p>এখন rate limiting per-second basis-এ করা যাবে:</p>
                    <pre><code class="language-php">RateLimiter::for('api', function (Request $request) {
    return Limit::perSecond(10)->by($request->user()?->id ?: $request->ip());
});</code></pre>

                    <h2>কীভাবে Upgrade করবেন?</h2>
                    <ol>
                        <li>PHP 8.2+ required</li>
                        <li>Update composer.json dependencies</li>
                        <li>Run <code>composer update</code></li>
                        <li>Update config files</li>
                        <li>Test thoroughly</li>
                    </ol>

                    <h2>উপসংহার</h2>
                    <p>Laravel 11 একটি excellent release। আশা করি এই guide আপনার কাজে আসবে। কোন প্রশ্ন থাকলে নিচে comment করুন!</p>
                </div>

                {{-- Tags --}}
                <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">ট্যাগস:</span>
                        <a href="#" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">#laravel</a>
                        <a href="#" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">#php</a>
                        <a href="#" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">#framework</a>
                    </div>
                </div>

                {{-- Mobile Share --}}
                <div class="lg:hidden mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-4">পোস্টটি শেয়ার করুন</h4>
                    <div class="flex flex-wrap gap-3">
                        <a href="#" class="flex-1 min-w-[100px] py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center gap-2 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                            Facebook
                        </a>
                        <a href="#" class="flex-1 min-w-[100px] py-2.5 bg-sky-500 hover:bg-sky-600 text-white rounded-lg flex items-center justify-center gap-2 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            Twitter
                        </a>
                    </div>
                </div>

                {{-- Author Bio --}}
                <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-6 lg:p-8">
                        <div class="flex flex-col sm:flex-row gap-6">
                            <div class="flex-shrink-0">
                                <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-purple-500 rounded-2xl flex items-center justify-center text-white text-3xl font-bold">র</div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-1">রাফি আহমেদ</h4>
                                <p class="text-blue-600 dark:text-blue-400 font-medium mb-3">Full Stack Developer</p>
                                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4">৫+ বছরের experience নিয়ে Laravel, Vue.js এবং modern web technologies নিয়ে কাজ করছি।</p>
                                <div class="flex gap-3">
                                    <a href="#" class="text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                                    <a href="#" class="text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Comments Section --}}
                <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        মন্তব্য (৩)
                    </h3>

                    {{-- Comment Form --}}
                    <form action="#" method="POST" class="mb-10">
                        <div class="grid gap-4 sm:grid-cols-2 mb-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">নাম <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" placeholder="আপনার নাম" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ইমেইল <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" placeholder="আপনার ইমেইল" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">মন্তব্য <span class="text-red-500">*</span></label>
                            <textarea id="comment" name="comment" rows="4" placeholder="আপনার মন্তব্য লিখুন..." class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors resize-none"></textarea>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            মন্তব্য করুন
                        </button>
                    </form>

                    {{-- Comments List --}}
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">ত</div>
                            <div class="flex-1">
                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 lg:p-5">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-semibold text-gray-900 dark:text-white">তানভীর হোসেন</span>
                                        <span class="text-sm text-gray-500">• ২ দিন আগে</span>
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300">অসাধারণ পোস্ট! Laravel 11-এর simplified structure সত্যিই impressive।</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">ন</div>
                            <div class="flex-1">
                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 lg:p-5">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-semibold text-gray-900 dark:text-white">নাফিসা আক্তার</span>
                                        <span class="text-sm text-gray-500">• ৩ দিন আগে</span>
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300">Per-second rate limiting feature টা API development-এ খুব কাজে আসবে।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Posts --}}
    <section class="bg-gray-50 dark:bg-gray-800/50 py-12 lg:py-16 mt-12 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">সম্পর্কিত পোস্ট</h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <x-blog-card title="PHP 8.4 এর নতুন ফিচারগুলো দেখুন" excerpt="PHP 8.4-এ কী কী নতুন ফিচার এসেছে যেমন Property Hooks এবং আরও অনেক কিছু।" category="PHP" categoryColor="purple" author="রাফি আহমেদ" date="১৮ জানুয়ারি, ২০২৫" readTime="৭ মিনিট" url="/blog/php-84-features" />
                <x-blog-card title="Laravel Eloquent ORM Mastery" excerpt="Eloquent ORM-এর advanced features শিখুন - relationships, scopes, events।" category="Laravel" categoryColor="blue" author="সাকিব হাসান" date="১০ জানুয়ারি, ২০২৫" readTime="১২ মিনিট" url="/blog/eloquent-mastery" />
                <x-blog-card title="API Development Best Practices" excerpt="Production-ready RESTful API তৈরি করার best practices।" category="Laravel" categoryColor="blue" author="ফারহান আহমেদ" date="৫ জানুয়ারি, ২০২৫" readTime="১০ মিনিট" url="/blog/api-best-practices" />
            </div>
        </div>
    </section>
</article>

@endsection
