{{--
    Task Index Page (Task List)

    এই page এ logged-in user এর সব tasks দেখানো হয়।
    Pagination সহ task list, প্রতিটি task এ edit ও delete button।

    @forelse ব্যবহার করা হয়েছে - @foreach এর মতো কিন্তু
    empty collection হলে @empty block execute হয়।

    @see https://laravel.com/docs/blade#loops
--}}
@extends('layouts.app')

@section('title', 'আমার টাস্কসমূহ')

@section('content')
    {{-- Page Header with Create Button --}}
    <div class="flex-between mb-2">
        <h1 style="font-size: 1.5rem; font-weight: 600;">আমার টাস্কসমূহ</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            + নতুন টাস্ক
        </a>
    </div>

    {{--
        Task List

        @forelse - foreach এর মতো কিন্তু empty হলে @empty block দেখায়
        compact variable থেকে $tasks পাওয়া যাচ্ছে (TaskController::index থেকে)
    --}}
    @forelse ($tasks as $task)
        <div class="card">
            <div class="flex-between">
                <div>
                    {{-- Task Title - Clickable --}}
                    <h3 style="font-size: 1.1rem; margin-bottom: 0.25rem;">
                        <a href="{{ route('tasks.show', $task) }}" style="color: inherit; text-decoration: none;">
                            {{ $task->title }}
                        </a>
                    </h3>

                    {{-- Task Meta Info --}}
                    <div style="font-size: 0.875rem; color: #666;">
                        {{-- Status Badge --}}
                        <span class="badge badge-{{ $task->status }}">
                            {{ \App\Models\Task::statuses()[$task->status] }}
                        </span>

                        {{-- Due Date (যদি থাকে) --}}
                        @if ($task->due_date)
                            <span style="margin-left: 0.5rem;">
                                শেষ তারিখ: {{ $task->due_date->format('d M, Y') }}
                            </span>
                        @endif

                        {{-- Attachment Indicator --}}
                        @if ($task->hasAttachment())
                            <span style="margin-left: 0.5rem;" title="ফাইল সংযুক্ত আছে">
                                [ফাইল আছে]
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex">
                    {{-- View Button --}}
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-secondary">
                        দেখুন
                    </a>

                    {{-- Edit Button --}}
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-secondary">
                        এডিট
                    </a>

                    {{--
                        Delete Form

                        DELETE method ব্যবহার করা হচ্ছে।
                        @method('DELETE') দিয়ে hidden field তৈরি হয়।
                        HTML form শুধুমাত্র GET ও POST সাপোর্ট করে,
                        তাই Laravel @method directive দিয়ে PUT, PATCH, DELETE simulate করে।

                        onsubmit এ confirm() দিয়ে user কে জিজ্ঞেস করা হচ্ছে।
                    --}}
                    <form
                        action="{{ route('tasks.destroy', $task) }}"
                        method="POST"
                        class="inline-form"
                        onsubmit="return confirm('আপনি কি নিশ্চিত যে এই টাস্কটি মুছে ফেলতে চান?');"
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
    @empty
        {{-- No Tasks Message --}}
        <div class="card empty-state">
            <p>কোনো টাস্ক নেই।</p>
            <p style="margin-top: 0.5rem;">
                <a href="{{ route('tasks.create') }}">প্রথম টাস্ক তৈরি করুন</a>
            </p>
        </div>
    @endforelse

    {{--
        Pagination Links

        Laravel এর built-in pagination।
        $tasks->links() দিয়ে pagination links render হয়।
    --}}
    @if ($tasks->hasPages())
        <div class="pagination">
            {{ $tasks->links() }}
        </div>
    @endif
@endsection
