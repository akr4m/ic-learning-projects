{{--
    Dashboard View

    User এর ড্যাশবোর্ড যেখানে তার পোস্টের সারসংক্ষেপ দেখতে পাবে।
    Editor হলে pending posts এর count ও দেখাবে।

    Variables:
    - $stats: Post statistics (total, draft, pending, published, rejected)
    - $pendingApprovalCount: Pending posts count (Editor only)
    - $recentPosts: User's recent 5 posts
--}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ড্যাশবোর্ড
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">
                        স্বাগতম, {{ auth()->user()->name }}!
                    </h3>
                    <p class="text-gray-600 mt-1">
                        আপনি
                        <span class="px-2 py-1 rounded-full text-sm {{ auth()->user()->isEditor() ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ auth()->user()->isEditor() ? 'Editor' : 'Author' }}
                        </span>
                        হিসেবে লগইন করেছেন।
                    </p>
                </div>
            </div>

            <!-- Editor Alert: Pending Posts -->
            @if(auth()->user()->isEditor() && $pendingApprovalCount > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>{{ $pendingApprovalCount }}</strong> টি পোস্ট অনুমোদনের অপেক্ষায় আছে।
                                <a href="{{ route('editor.pending') }}" class="font-medium underline hover:text-yellow-600">
                                    এখনই দেখুন &rarr;
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <!-- Total Posts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                    <div class="text-gray-600 text-sm">মোট পোস্ট</div>
                </div>

                <!-- Draft -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-500">{{ $stats['draft'] }}</div>
                    <div class="text-gray-600 text-sm">খসড়া</div>
                </div>

                <!-- Pending -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                    <div class="text-gray-600 text-sm">অপেক্ষমাণ</div>
                </div>

                <!-- Published -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['published'] }}</div>
                    <div class="text-gray-600 text-sm">প্রকাশিত</div>
                </div>

                <!-- Rejected -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                    <div class="text-gray-600 text-sm">প্রত্যাখ্যাত</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="font-medium text-gray-900 mb-4">দ্রুত কাজ</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            নতুন পোস্ট লিখুন
                        </a>
                        <a href="{{ route('posts.my') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                            আমার সব পোস্ট
                        </a>
                        @if(auth()->user()->isEditor())
                            <a href="{{ route('editor.pending') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500">
                                অনুমোদন করুন
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Posts -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-medium text-gray-900 mb-4">সাম্প্রতিক পোস্ট</h3>

                    @if($recentPosts->isEmpty())
                        <p class="text-gray-500">আপনি এখনো কোনো পোস্ট লেখেননি।</p>
                    @else
                        <div class="space-y-4">
                            @foreach($recentPosts as $post)
                                <div class="flex items-center justify-between border-b pb-3 last:border-0">
                                    <div>
                                        <a href="{{ route('posts.show', $post) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                            {{ $post->title }}
                                        </a>
                                        <div class="text-sm text-gray-500">
                                            {{ $post->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div>
                                        @switch($post->status)
                                            @case('draft')
                                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">খসড়া</span>
                                                @break
                                            @case('pending')
                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">অপেক্ষমাণ</span>
                                                @break
                                            @case('published')
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">প্রকাশিত</span>
                                                @break
                                            @case('rejected')
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">প্রত্যাখ্যাত</span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
