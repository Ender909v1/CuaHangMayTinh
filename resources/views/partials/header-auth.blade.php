@auth
    {{-- Logged in: account dropdown, replaces Login/Register --}}
    <div class="relative" x-data="{ open: false }" @click.away="open = false">
        <button type="button" @click="open = !open"
            class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-flex items-center gap-2">
            <i class="fas fa-user-circle"></i>
            <span class="max-w-[140px] truncate">{{ Auth::user()->full_name ?? Auth::user()->email }}</span>
            <i :class="open ? 'fas fa-chevron-up ml-1 text-xs' : 'fas fa-chevron-down ml-1 text-xs'"></i>
        </button>
        <div x-show="open" x-transition
            class="absolute right-0 mt-2 w-56 bg-white text-black shadow-lg rounded-lg py-2 z-50 text-left">
            <div class="px-4 py-2 border-b border-gray-100">
                <p class="font-semibold truncate">{{ Auth::user()->full_name ?? 'My Account' }}</p>
                <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
            </div>
            <a href="{{ route('account') }}"
                class="block px-4 py-2 hover:bg-primary hover:text-white font-semibold">
                <i class="fas fa-cog mr-2"></i>Account management
            </a>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                    class="block px-4 py-2 hover:bg-primary hover:text-white font-semibold">
                    <i class="fas fa-gauge-high mr-2"></i>Admin dashboard
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-2 hover:bg-primary hover:text-white font-semibold">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </form>
        </div>
    </div>
@else
    {{-- Guest: show Login/Register --}}
    <a href="{{ route('register') }}"
        class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">Register</a>
    <a href="{{ route('login') }}"
        class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">Login</a>
@endauth
