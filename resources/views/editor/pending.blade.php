{{--
    Editor Pending Posts View

    Editor এর জন্য pending posts এর তালিকা।
    এখান থেকে posts approve বা reject করা যাবে।

    Teaching Points:
    - Role-based view (শুধু Editor দেখতে পারবে)
    - Authorization check is in PostPolicy
    - Action buttons with different forms

    Variables:
    - $posts: Paginated collection of pending posts
--}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            অনুমোদনের অপেক্ষায় আছে
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($posts->isEmpty())
                <!-- No Pending Posts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        এই মুহূর্তে কোনো পোস্ট অনুমোদনের অপেক্ষায় নেই।
                    </div>
                </div>
            @else
                <!-- Pending Posts List -->
                <div class="space-y-6">
                    @foreach($posts as $post)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <!-- Post Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            <a href="{{ route('posts.show', $post) }}" class="hover:text-blue-600">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            লেখক: {{ $post->author->name }} &bull;
                                            জমা দেওয়া হয়েছে: {{ $post->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Post Excerpt -->
                                <div class="text-gray-600 mb-4">
                                    {{ Str::limit(strip_tags($post->body), 300) }}
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-4 pt-4 border-t">
                                    <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:text-blue-800">
                                        সম্পূর্ণ পড়ুন &rarr;
                                    </a>

                                    <div class="flex-grow"></div>

                                    <!-- Approve Button -->
                                    <form action="{{ route('posts.approve', $post) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                            অনুমোদন
                                        </button>
                                    </form>

                                    <!-- Reject Button (opens modal) -->
                                    <button type="button" onclick="openRejectModal({{ $post->id }})" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                        প্রত্যাখ্যান
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal for this post -->
                        <div id="reject-modal-{{ $post->id }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <div class="mt-3">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">প্রত্যাখ্যানের কারণ</h3>
                                    <p class="text-sm text-gray-600 mb-4">
                                        "{{ Str::limit($post->title, 30) }}" পোস্টটি কেন প্রত্যাখ্যান করা হচ্ছে?
                                    </p>
                                    <form action="{{ route('posts.reject', $post) }}" method="POST">
                                        @csrf
                                        <textarea
                                            name="rejection_reason"
                                            rows="4"
                                            required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="লেখককে বুঝতে সাহায্য করার জন্য কারণ লিখুন..."
                                        ></textarea>

                                        <div class="mt-4 flex justify-end gap-3">
                                            <button type="button" onclick="closeRejectModal({{ $post->id }})" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                                বাতিল
                                            </button>
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-500">
                                                প্রত্যাখ্যান করুন
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            @endif

        </div>
    </div>

    <!-- Modal JavaScript -->
    <script>
        function openRejectModal(postId) {
            document.getElementById('reject-modal-' + postId).classList.remove('hidden');
        }

        function closeRejectModal(postId) {
            document.getElementById('reject-modal-' + postId).classList.add('hidden');
        }
    </script>
</x-app-layout>
