{{--
    Task Show Page (Task Details)

    একটি নির্দিষ্ট Task এর বিস্তারিত দেখায়।
    Route Model Binding দ্বারা $task variable inject হয়।

    @see https://laravel.com/docs/routing#route-model-binding
--}}
@extends('layouts.app')

@section('title', $task->title)

@section('content')
    <div class="card" style="max-width: 700px; margin: 0 auto;">
        {{-- Card Header with Actions --}}
        <div class="card-header flex-between">
            <h2 class="card-title" style="margin-bottom: 0;">{{ $task->title }}</h2>

            <div class="flex">
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-secondary">
                    এডিট করুন
                </a>
                <form
                    action="{{ route('tasks.destroy', $task) }}"
                    method="POST"
                    class="inline-form"
                    onsubmit="return confirm('আপনি কি নিশ্চিত?');"
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        মুছে ফেলুন
                    </button>
                </form>
            </div>
        </div>

        {{-- Task Info Table --}}
        <table class="table" style="margin-bottom: 1rem;">
            <tr>
                <th style="width: 30%;">স্ট্যাটাস</th>
                <td>
                    <span class="badge badge-{{ $task->status }}">
                        {{ \App\Models\Task::statuses()[$task->status] }}
                    </span>
                </td>
            </tr>

            @if ($task->due_date)
                <tr>
                    <th>শেষ তারিখ</th>
                    <td>
                        {{--
                            Carbon date formatting
                            $task->due_date Carbon instance (casts এ define করা)
                            format() method দিয়ে date format করা যায়
                        --}}
                        {{ $task->due_date->format('d F, Y') }}

                        {{--
                            Due date পার হয়ে গেলে warning দেখানো
                            isPast() - Carbon method যা check করে date past কিনা
                        --}}
                        @if ($task->due_date->isPast() && $task->status !== 'completed')
                            <span style="color: #dc3545; margin-left: 0.5rem;">
                                (সময় পার হয়ে গেছে)
                            </span>
                        @endif
                    </td>
                </tr>
            @endif

            <tr>
                <th>তৈরির তারিখ</th>
                <td>{{ $task->created_at->format('d F, Y - h:i A') }}</td>
            </tr>

            @if ($task->created_at != $task->updated_at)
                <tr>
                    <th>সর্বশেষ আপডেট</th>
                    <td>{{ $task->updated_at->format('d F, Y - h:i A') }}</td>
                </tr>
            @endif
        </table>

        {{-- Task Description --}}
        @if ($task->description)
            <div style="margin-bottom: 1rem;">
                <h4 style="margin-bottom: 0.5rem;">বিবরণ</h4>
                <div style="background: #f9f9f9; padding: 1rem; border-radius: 4px; white-space: pre-wrap;">{{ $task->description }}</div>
            </div>
        @endif

        {{-- Attachment Section --}}
        @if ($task->hasAttachment())
            <div style="margin-bottom: 1rem;">
                <h4 style="margin-bottom: 0.5rem;">সংযুক্তি</h4>
                <div style="padding: 0.75rem; background: #f5f5f5; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">
                    <span>{{ $task->attachment_name }}</span>
                    <a href="{{ route('tasks.download', $task) }}" class="btn btn-sm btn-primary">
                        ডাউনলোড করুন
                    </a>
                </div>
            </div>
        @endif

        {{-- Back Button --}}
        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #eee;">
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                &larr; টাস্ক তালিকায় ফিরে যান
            </a>
        </div>
    </div>
@endsection
