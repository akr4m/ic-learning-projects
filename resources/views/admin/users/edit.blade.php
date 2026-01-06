<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            রোল পরিবর্তন - {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- User Info --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">ইউজার</p>
                        <p class="font-medium">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            বর্তমান রোল:
                            <span class="font-medium">{{ $user->role_name }}</span>
                        </p>
                    </div>

                    {{-- Role Change Form --}}
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                নতুন রোল সিলেক্ট করুন
                            </label>

                            <div class="space-y-3">
                                @foreach ($roles as $role)
                                    <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50
                                                  {{ $user->role === $role ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                        <input type="radio"
                                               name="role"
                                               value="{{ $role }}"
                                               {{ $user->role === $role ? 'checked' : '' }}
                                               class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-gray-900">
                                                @if ($role === 'admin')
                                                    অ্যাডমিন
                                                @elseif ($role === 'editor')
                                                    এডিটর
                                                @else
                                                    লেখক
                                                @endif
                                            </span>
                                            <span class="block text-sm text-gray-500">
                                                @if ($role === 'admin')
                                                    সব কিছু করতে পারে, user roles manage করতে পারে
                                                @elseif ($role === 'editor')
                                                    Posts approve/reject করতে পারে
                                                @else
                                                    Posts লিখতে এবং submit করতে পারে
                                                @endif
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                রোল আপডেট করুন
                            </button>

                            <a href="{{ route('admin.users.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                বাতিল
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
