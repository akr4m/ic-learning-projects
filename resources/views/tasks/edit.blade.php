{{--
    Task Edit Page

    বিদ্যমান Task এডিট করার form।

    @method('PUT') - HTML form শুধু GET/POST সাপোর্ট করে।
    Laravel এই directive দিয়ে hidden _method field তৈরি করে PUT request simulate করে।

    @see https://laravel.com/docs/blade#method-field
--}}
@extends('layouts.app')

@section('title', 'টাস্ক এডিট')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title">টাস্ক এডিট করুন</h2>
        </div>

        {{--
            Task Edit Form

            @method('PUT') - form কে PUT request হিসেবে treat করে।
            enctype="multipart/form-data" - file upload এর জন্য।
        --}}
        <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Title Field --}}
            <div class="form-group">
                <label for="title">শিরোনাম <span style="color: #dc3545;">*</span></label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    value="{{ old('title', $task->title) }}"
                    required
                    autofocus
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
                >{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status Field --}}
            <div class="form-group">
                <label for="status">স্ট্যাটাস <span style="color: #dc3545;">*</span></label>
                <select id="status" name="status" class="form-control" required>
                    @foreach ($statuses as $value => $label)
                        {{--
                            old() function প্রথমে old input চেক করে।
                            না পেলে $task->status ব্যবহার করে (2nd parameter)।
                        --}}
                        <option value="{{ $value }}" {{ old('status', $task->status) == $value ? 'selected' : '' }}>
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
                    value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                >
                @error('due_date')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Current Attachment (যদি থাকে) --}}
            @if ($task->hasAttachment())
                <div class="form-group">
                    <label>বর্তমান সংযুক্তি</label>
                    <div style="padding: 0.5rem; background: #f5f5f5; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">
                        <span>{{ $task->attachment_name }}</span>
                        <div class="flex">
                            <a href="{{ route('tasks.download', $task) }}" class="btn btn-sm btn-secondary">
                                ডাউনলোড
                            </a>
                            {{-- Delete Attachment Button --}}
                            <form
                                action="{{ route('tasks.attachment.delete', $task) }}"
                                method="POST"
                                class="inline-form"
                                onsubmit="return confirm('ফাইলটি মুছে ফেলতে চান?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    মুছুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- New File Attachment Field --}}
            <div class="form-group">
                <label for="attachment">
                    {{ $task->hasAttachment() ? 'নতুন ফাইল দিয়ে বদলান' : 'ফাইল সংযুক্তি' }}
                </label>
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
                    পরিবর্তন সংরক্ষণ করুন
                </button>
                <a href="{{ route('tasks.show', $task) }}" class="btn btn-secondary">
                    বাতিল করুন
                </a>
            </div>
        </form>
    </div>
@endsection
