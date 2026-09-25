@auth
    <div class="flex flex-col mt-6 space-y-2 items-center w-full">
        <p class="text-white/80 text-sm">Hi, <span class="font-semibold text-white">{{ Auth::user()->full_name ?? Auth::user()->email }}</span></p>
        <a href="{{ route('account') }}"
            class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[180px]">
            <i class="fas fa-cog mr-2"></i>Account management</a>
        @if (Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
                class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[180px]">
                <i class="fas fa-gauge-high mr-2"></i>Admin dashboard</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" class="w-full flex justify-center">
            @csrf
            <button type="submit"
                class="bg-transparent hover:bg-primary text-white border border-white/40 hover:border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[180px]">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout</button>
        </form>
    </div>
@else
    <div class="flex flex-col mt-6 space-y-2 items-center">
        <a href="{{ route('register') }}"
            class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Register</a>
        <a href="{{ route('login') }}"
            class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Login</a>
        @if (!empty($showCartLink))
            <a href="{{ route('cart') }}"
                class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Cart -&nbsp;<span>5</span>&nbsp;items</a>
        @endif
    </div>
@endauth
