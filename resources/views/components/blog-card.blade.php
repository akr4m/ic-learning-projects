{{-- Blog Card - Reusable component for blog posts --}}
@props([
    'title' => 'ব্লগ পোস্ট শিরোনাম',
    'excerpt' => 'এটি একটি সংক্ষিপ্ত বিবরণ যা ব্লগ পোস্টের মূল বিষয়বস্তু সম্পর্কে পাঠককে ধারণা দেয়।',
    'category' => 'Laravel',
    'categoryColor' => 'blue',
    'author' => 'রাফি আহমেদ',
    'date' => '১৫ জানুয়ারি, ২০২৫',
    'readTime' => '৫ মিনিট',
    'image' => null,
    'url' => '#'
])

<article class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 overflow-hidden">

    {{-- Thumbnail --}}
    <a href="{{ $url }}" class="block overflow-hidden">
        <div class="aspect-video relative">
            @if($image)
                <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                @php
                    $gradients = [
                        'blue' => 'from-blue-400 to-blue-600',
                        'green' => 'from-green-400 to-green-600',
                        'purple' => 'from-purple-400 to-purple-600',
                        'orange' => 'from-orange-400 to-orange-600',
                        'pink' => 'from-pink-400 to-pink-600',
                        'cyan' => 'from-cyan-400 to-cyan-600',
                    ];
                    $gradient = $gradients[$categoryColor] ?? 'from-gray-400 to-gray-600';
                @endphp
                <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                    <div class="text-white/20 text-6xl font-bold">{{ strtoupper(substr($category, 0, 2)) }}</div>
                </div>
            @endif

            {{-- Category Badge --}}
            @php
                $badgeColors = [
                    'blue' => 'bg-blue-500', 'green' => 'bg-green-500', 'purple' => 'bg-purple-500',
                    'orange' => 'bg-orange-500', 'pink' => 'bg-pink-500', 'cyan' => 'bg-cyan-500',
                ];
                $badgeColor = $badgeColors[$categoryColor] ?? 'bg-gray-500';
            @endphp
            <span class="absolute top-4 left-4 px-3 py-1 {{ $badgeColor }} text-white text-xs font-medium rounded-full shadow-sm">{{ $category }}</span>
        </div>
    </a>

    {{-- Content --}}
    <div class="p-5 lg:p-6">
        <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
            <a href="{{ $url }}">{{ $title }}</a>
        </h3>

        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-4 line-clamp-3">{{ $excerpt }}</p>

        {{-- Meta --}}
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">{{ mb_substr($author, 0, 1) }}</div>
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $author }}</span>
            </div>
            <span class="hidden sm:inline text-gray-300 dark:text-gray-600">|</span>
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $date }}
            </div>
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $readTime }} পড়া
            </div>
        </div>

        <a href="{{ $url }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-medium hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
            আরও পড়ুন
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</article>
