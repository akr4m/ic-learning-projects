{{-- Sidebar - Search, Categories, Popular Posts, Tags --}}
<aside class="space-y-8">

    {{-- Search --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            সার্চ করুন
        </h3>
        <form action="#" method="GET">
            <div class="relative">
                <input type="search" name="q" placeholder="ব্লগে খুঁজুন..."
                       class="w-full px-4 py-3 pr-12 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Categories --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            ক্যাটাগরি
        </h3>
        <ul class="space-y-2">
            <li><a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group"><span class="flex items-center gap-2"><span class="w-2 h-2 bg-blue-500 rounded-full"></span>Laravel</span><span class="text-sm text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full group-hover:bg-blue-100 dark:group-hover:bg-blue-900 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">12</span></a></li>
            <li><a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group"><span class="flex items-center gap-2"><span class="w-2 h-2 bg-yellow-500 rounded-full"></span>JavaScript</span><span class="text-sm text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full group-hover:bg-yellow-100 dark:group-hover:bg-yellow-900 group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors">8</span></a></li>
            <li><a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group"><span class="flex items-center gap-2"><span class="w-2 h-2 bg-purple-500 rounded-full"></span>PHP</span><span class="text-sm text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full group-hover:bg-purple-100 dark:group-hover:bg-purple-900 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">15</span></a></li>
            <li><a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group"><span class="flex items-center gap-2"><span class="w-2 h-2 bg-green-500 rounded-full"></span>Database</span><span class="text-sm text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full group-hover:bg-green-100 dark:group-hover:bg-green-900 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">6</span></a></li>
            <li><a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group"><span class="flex items-center gap-2"><span class="w-2 h-2 bg-pink-500 rounded-full"></span>DevOps</span><span class="text-sm text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full group-hover:bg-pink-100 dark:group-hover:bg-pink-900 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">4</span></a></li>
        </ul>
    </div>

    {{-- Popular Posts --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
            </svg>
            জনপ্রিয় পোস্ট
        </h3>
        <div class="space-y-4">
            <a href="/blog/laravel-tutorial" class="flex gap-3 group">
                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center text-white text-xl font-bold">L</div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">Laravel দিয়ে RESTful API তৈরি করুন</h4>
                    <p class="text-xs text-gray-500 mt-1">১২,৫০০ views</p>
                </div>
            </a>
            <a href="/blog/javascript-async" class="flex gap-3 group">
                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center text-white text-xl font-bold">JS</div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">Async/Await বুঝুন সহজ ভাষায়</h4>
                    <p class="text-xs text-gray-500 mt-1">৯,৮০০ views</p>
                </div>
            </a>
            <a href="/blog/tailwind-tips" class="flex gap-3 group">
                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-lg flex items-center justify-center text-white text-xl font-bold">TW</div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">Tailwind CSS Tips & Tricks</h4>
                    <p class="text-xs text-gray-500 mt-1">৭,২০০ views</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Tags --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            ট্যাগস
        </h3>
        <div class="flex flex-wrap gap-2">
            <a href="#" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">#laravel</a>
            <a href="#" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">#php</a>
            <a href="#" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">#javascript</a>
            <a href="#" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">#mysql</a>
            <a href="#" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">#api</a>
            <a href="#" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">#tailwind</a>
            <a href="#" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">#vue</a>
            <a href="#" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">#react</a>
        </div>
    </div>

    {{-- Author Card --}}
    <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-6 shadow-sm text-white">
        <div class="text-center">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-white/20 flex items-center justify-center ring-4 ring-white/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-1">রাফি আহমেদ</h3>
            <p class="text-blue-100 text-sm mb-4">Full Stack Developer</p>
            <p class="text-blue-100 text-sm leading-relaxed mb-4">৫+ বছরের অভিজ্ঞতা নিয়ে Laravel, Vue.js এবং modern web technologies শেখাচ্ছি।</p>
            <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                ফলো করুন
            </a>
        </div>
    </div>
</aside>
