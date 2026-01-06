{{--
    Navigation Component

    এই component main navigation bar রেন্ডার করে।
    User এর role অনুযায়ী বিভিন্ন menu items দেখায়।

    Features:
    - Responsive design (mobile + desktop)
    - Role-based menu items
    - Active state highlighting
--}}
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">
                        ব্লগ
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Public: Home (Blog) -->
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home') || request()->routeIs('posts.index')">
                        হোম
                    </x-nav-link>

                    @auth
                        <!-- Auth: Dashboard -->
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            ড্যাশবোর্ড
                        </x-nav-link>

                        <!-- Auth: My Posts -->
                        <x-nav-link :href="route('posts.my')" :active="request()->routeIs('posts.my')">
                            আমার পোস্ট
                        </x-nav-link>

                        <!-- Auth: New Post -->
                        <x-nav-link :href="route('posts.create')" :active="request()->routeIs('posts.create')">
                            নতুন পোস্ট
                        </x-nav-link>

                        <!-- Editor/Admin Only: Pending Posts -->
                        @if(auth()->user()->canManagePosts())
                            <x-nav-link :href="route('editor.pending')" :active="request()->routeIs('editor.pending')">
                                অনুমোদন
                                @php
                                    $pendingCount = \App\Models\Post::pending()->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="ml-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </x-nav-link>
                        @endif

                        <!-- Admin Only: User Management -->
                        @if(auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                ইউজার
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- Settings Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <span class="ml-2 text-xs px-2 py-0.5 rounded-full
                                    {{ Auth::user()->isAdmin() ? 'bg-red-100 text-red-700' : (Auth::user()->isEditor() ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ Auth::user()->role_name }}
                                </span>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                প্রোফাইল
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    লগ আউট
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Guest: Login/Register Links -->
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                        লগইন
                    </a>
                    <a href="{{ route('register') }}" class="bg-gray-800 text-white hover:bg-gray-700 px-4 py-2 rounded-md text-sm font-medium ml-2">
                        রেজিস্টার
                    </a>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Public: Home -->
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                হোম
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    ড্যাশবোর্ড
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('posts.my')" :active="request()->routeIs('posts.my')">
                    আমার পোস্ট
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('posts.create')" :active="request()->routeIs('posts.create')">
                    নতুন পোস্ট
                </x-responsive-nav-link>

                @if(auth()->user()->canManagePosts())
                    <x-responsive-nav-link :href="route('editor.pending')" :active="request()->routeIs('editor.pending')">
                        অনুমোদন
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        ইউজার
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ Auth::user()->isAdmin() ? 'bg-red-100 text-red-700' : (Auth::user()->isEditor() ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700') }}">
                        {{ Auth::user()->role_name }}
                    </span>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        প্রোফাইল
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            লগ আউট
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        লগইন
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        রেজিস্টার
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
