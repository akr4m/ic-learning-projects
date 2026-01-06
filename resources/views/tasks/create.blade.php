{{--
    Task Create Page

    নতুন Task তৈরির form।

    Form enctype="multipart/form-data" - file upload এর জন্য আবশ্যক।
    এটি ছাড়া file upload কাজ করবে না।

    @see https://laravel.com/docs/blade#forms
--}}
@extends('layouts.app')

@section('title', 'নতুন টাস্ক')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title">নতুন টাস্ক তৈরি করুন</h2>
        </div>

        {{--
            Task Creation Form

            enctype="multipart/form-data" - file upload এর জন্য অবশ্যই লাগবে।
            এটি form data কে multipart format এ encode করে যাতে file পাঠানো যায়।
        --}}
        <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Title Field --}}
            <div class="form-group">
                <label for="title">শিরোনাম <span style="color: #dc3545;">*</span></label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}"
                    required
                    autofocus
                    placeholder="টাস্কের শিরোনাম লিখুন"
                >
                @error('title')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Description Field --}}
            <div class="form-group">
                <label for="description">বিবরণ</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    placeholder="টাস্কের বিস্তারিত বিবরণ (ঐচ্ছিক)"
                >{{ old('description') }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status Field --}}
            <div class="form-group">
                <label for="status">স্ট্যাটাস <span style="color: #dc3545;">*</span></label>
                {{--
                    select field - dropdown menu
                    $statuses variable TaskController::create() থেকে আসছে
                --}}
                <select id="status" name="status" class="form-control" required>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" {{ old('status', 'pending') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Due Date Field --}}
            <div class="form-group">
                <label for="due_date">শেষ তারিখ</label>
                <input
                    type="date"
                    id="due_date"
                    name="due_date"
                    class="form-control"
                    value="{{ old('due_date') }}"
                >
                @error('due_date')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- File Attachment Field --}}
            <div class="form-group">
                <label for="attachment">ফাইল সংযুক্তি</label>
                <input
                    type="file"
                    id="attachment"
                    name="attachment"
                    class="form-control"
                >
                <small style="color: #666;">
                    সর্বোচ্চ 10MB | অনুমোদিত: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, PNG, GIF, ZIP
                </small>
                @error('attachment')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit Button --}}
            <div class="form-group" style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">
                    টাস্ক তৈরি করুন
                </button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                    বাতিল করুন
                </a>
            </div>
        </form>
    </div>
@endsection
