{{--
    Create Recipe Page

    নতুন রেসিপি তৈরির ফর্ম।
    Form validation errors দেখানো হয়।
    JavaScript দিয়ে dynamic ingredient fields যোগ করা যায়।
--}}
@extends('layouts.app')

@section('title', 'নতুন রেসিপি তৈরি করুন')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">নতুন রেসিপি তৈরি করুন</h1>

        {{-- Recipe Form --}}
        <form action="{{ route('recipes.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">

            {{-- CSRF Token - Laravel security feature --}}
            @csrf

            {{-- Title Field --}}
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    রেসিপির নাম <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title') }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description Field --}}
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    বিস্তারিত বর্ণনা <span class="text-red-500">*</span>
                </label>
                <textarea id="description"
                          name="description"
                          rows="5"
                          required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image Upload Field --}}
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                    রেসিপির ছবি
                </label>
                <input type="file"
                       id="image"
                       name="image"
                       accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('image') border-red-500 @enderror">
                <p class="text-gray-500 text-sm mt-1">সর্বোচ্চ ২ MB, JPEG/PNG/GIF/JPG</p>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ingredients Section --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    উপকরণসমূহ
                </label>

                <div id="ingredients-container">
                    {{-- Initial ingredient row --}}
                    <div class="ingredient-row flex gap-2 mb-2">
                        <input type="text"
                               name="ingredients[0][name]"
                               placeholder="উপকরণের নাম"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <input type="text"
                               name="ingredients[0][quantity]"
                               placeholder="পরিমাণ"
                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <button type="button"
                                onclick="removeIngredient(this)"
                                class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                            &times;
                        </button>
                    </div>
                </div>

                <button type="button"
                        onclick="addIngredient()"
                        class="mt-2 text-emerald-600 hover:text-emerald-700 font-medium">
                    + আরো উপকরণ যোগ করুন
                </button>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex gap-4">
                <button type="submit"
                        class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                    রেসিপি সংরক্ষণ করুন
                </button>
                <a href="{{ route('recipes.index') }}"
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                    বাতিল
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Ingredient counter for unique names
    let ingredientCount = 1;

    /**
     * নতুন ingredient row যোগ করার function
     * DOM manipulation এর উদাহরণ
     */
    function addIngredient() {
        const container = document.getElementById('ingredients-container');
        const newRow = document.createElement('div');
        newRow.className = 'ingredient-row flex gap-2 mb-2';
        newRow.innerHTML = `
            <input type="text"
                   name="ingredients[${ingredientCount}][name]"
                   placeholder="উপকরণের নাম"
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            <input type="text"
                   name="ingredients[${ingredientCount}][quantity]"
                   placeholder="পরিমাণ"
                   class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            <button type="button"
                    onclick="removeIngredient(this)"
                    class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                &times;
            </button>
        `;
        container.appendChild(newRow);
        ingredientCount++;
    }

    /**
     * Ingredient row মুছে ফেলার function
     */
    function removeIngredient(button) {
        const row = button.parentElement;
        const container = document.getElementById('ingredients-container');
        // কমপক্ষে একটি row থাকতে হবে
        if (container.children.length > 1) {
            row.remove();
        }
    }
</script>
@endpush
