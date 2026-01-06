{{--
    Recipe List Page (Index)

    সকল রেসিপির তালিকা দেখানো হয়।
    Search এবং Filter functionality আছে।
    @extends দিয়ে main layout inherit করা হয়েছে।
--}}
@extends('layouts.app')

@section('title', 'সকল রেসিপি')

@section('content')
    {{-- Page Header with Search --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">সকল রেসিপি</h1>

        {{-- Search and Filter Form --}}
        <form action="{{ route('recipes.index') }}" method="GET" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Title/Description Search --}}
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                        খুঁজুন
                    </label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="রেসিপির নাম বা বর্ণনা..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                {{-- Ingredient Filter --}}
                <div>
                    <label for="ingredient" class="block text-sm font-medium text-gray-700 mb-1">
                        উপকরণ দিয়ে খুঁজুন
                    </label>
                    <input type="text"
                           id="ingredient"
                           name="ingredient"
                           value="{{ request('ingredient') }}"
                           placeholder="যেমন: চিনি, আটা..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                {{-- Search Button --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                        খুঁজুন
                    </button>
                    @if(request('search') || request('ingredient'))
                        <a href="{{ route('recipes.index') }}"
                           class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                            রিসেট
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Recipe Grid --}}
    @if($recipes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recipes as $recipe)
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    {{-- Recipe Image --}}
                    @if($recipe->image)
                        <img src="{{ asset('storage/' . $recipe->image) }}"
                             alt="{{ $recipe->title }}"
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">ছবি নেই</span>
                        </div>
                    @endif

                    {{-- Recipe Info --}}
                    <div class="p-4">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">
                            <a href="{{ route('recipes.show', $recipe) }}" class="hover:text-emerald-600 transition">
                                {{ $recipe->title }}
                            </a>
                        </h2>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ Str::limit($recipe->description, 100) }}
                        </p>

                        {{-- View Button --}}
                        <a href="{{ route('recipes.show', $recipe) }}"
                           class="inline-block text-emerald-600 hover:text-emerald-700 font-medium">
                            বিস্তারিত দেখুন &rarr;
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Pagination Links --}}
        <div class="mt-8">
            {{ $recipes->withQueryString()->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
            <p class="text-gray-500 text-lg mb-4">কোনো রেসিপি পাওয়া যায়নি</p>
            <a href="{{ route('recipes.create') }}"
               class="inline-block bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 transition">
                প্রথম রেসিপি তৈরি করুন
            </a>
        </div>
    @endif
@endsection
