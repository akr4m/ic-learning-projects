{{--
    Single Recipe View (Show)

    একটি রেসিপির বিস্তারিত তথ্য দেখানো হয়।
    Edit এবং Delete button-ও আছে।
--}}
@extends('layouts.app')

@section('title', $recipe->title)

@section('content')
    <article class="max-w-3xl mx-auto">
        {{-- Back Link --}}
        <a href="{{ route('recipes.index') }}"
           class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
            &larr; সকল রেসিপিতে ফিরে যান
        </a>

        {{-- Recipe Card --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            {{-- Recipe Image --}}
            @if($recipe->image)
                <img src="{{ asset('storage/' . $recipe->image) }}"
                     alt="{{ $recipe->title }}"
                     class="w-full h-64 md:h-96 object-cover">
            @else
                <div class="w-full h-64 md:h-96 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400 text-lg">ছবি নেই</span>
                </div>
            @endif

            {{-- Recipe Content --}}
            <div class="p-6">
                {{-- Title --}}
                <h1 class="text-3xl font-bold text-gray-800 mb-4">
                    {{ $recipe->title }}
                </h1>

                {{-- Action Buttons --}}
                <div class="flex gap-3 mb-6">
                    <a href="{{ route('recipes.edit', $recipe) }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        সম্পাদনা করুন
                    </a>

                    {{-- Delete Form - confirm dialog সহ --}}
                    <form action="{{ route('recipes.destroy', $recipe) }}"
                          method="POST"
                          onsubmit="return confirm('আপনি কি নিশ্চিত যে এই রেসিপিটি মুছে ফেলতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                            মুছে ফেলুন
                        </button>
                    </form>
                </div>

                {{-- Description --}}
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">বর্ণনা</h2>
                    <div class="prose prose-gray max-w-none">
                        {{-- nl2br দিয়ে newline কে <br> এ convert করা হয় --}}
                        {!! nl2br(e($recipe->description)) !!}
                    </div>
                </div>

                {{-- Ingredients List --}}
                @if($recipe->ingredients->count() > 0)
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-3">উপকরণসমূহ</h2>
                        <ul class="space-y-2">
                            @foreach($recipe->ingredients as $ingredient)
                                <li class="flex items-center gap-2 text-gray-700">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                    <span class="font-medium">{{ $ingredient->name }}</span>
                                    @if($ingredient->quantity)
                                        <span class="text-gray-500">- {{ $ingredient->quantity }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Metadata --}}
                <div class="mt-8 pt-6 border-t border-gray-200 text-sm text-gray-500">
                    <p>তৈরি: {{ $recipe->created_at->format('d M, Y h:i A') }}</p>
                    @if($recipe->updated_at->ne($recipe->created_at))
                        <p>সর্বশেষ আপডেট: {{ $recipe->updated_at->format('d M, Y h:i A') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </article>
@endsection
