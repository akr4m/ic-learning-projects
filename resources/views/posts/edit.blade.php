{{--
    Edit Post View

    পোস্ট সম্পাদনা করার ফর্ম।
    শুধুমাত্র draft বা rejected পোস্ট সম্পাদনা করা যায়।

    Teaching Points:
    - PUT method spoofing with @method('PUT')
    - Pre-filling form with existing data
    - Route model binding ($post)

    Variables:
    - $post: The Post model instance being edited
--}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            পোস্ট সম্পাদনা করুন
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Current Status Info -->
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                <p class="text-sm text-blue-700">
                    বর্তমান অবস্থা:
                    @switch($post->status)
                        @case('draft')
                            <span class="font-semibold">খসড়া</span> - আপনি সম্পাদনা করতে পারবেন।
                            @break
                        @case('rejected')
                            <span class="font-semibold">প্রত্যাখ্যাত</span> - সংশোধন করে আবার জমা দিতে পারবেন।
                            @break
                    @endswitch
                </p>
            </div>

            <!-- Rejection Reason (if rejected) -->
            @if($post->isRejected() && $post->rejection_reason)
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4">
                    <h4 class="text-sm font-medium text-red-800">প্রত্যাখ্যানের কারণ:</h4>
                    <p class="text-sm text-red-700 mt-1">{{ $post->rejection_reason }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{--
                        Form - PUT method এ posts.update route এ submit হবে
                        HTML forms শুধু GET/POST support করে, তাই @method('PUT') দিয়ে
                        Laravel কে বলা হচ্ছে এটা আসলে PUT request
                    --}}
                    <form action="{{ route('posts.update', $post) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Title Field -->
                        <div class="mb-6">
                            <x-input-label for="title" value="শিরোনাম" />
                            <x-text-input
                                id="title"
                                name="title"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('title', $post->title)"
                                required
                                autofocus
                            />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Body Field -->
                        <div class="mb-6">
                            <x-input-label for="body" value="বিস্তারিত" />
                            <textarea
                                id="body"
                                name="body"
                                rows="12"
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('body', $post->body) }}</textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                পরিবর্তন সংরক্ষণ করুন
                            </x-primary-button>

                            <a href="{{ route('posts.show', $post) }}" class="text-gray-600 hover:text-gray-900">
                                বাতিল
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
