<header class="bg-gray-dark sticky top-0 z-50"
    data-authenticated="{{ auth()->check() ? '1' : '0' }}"
    data-login-url="{{ route('login') }}">
    <div class="container mx-auto flex justify-between items-center py-4">
        <a href="{{ route('cuahangmaytinh') }}" class="flex items-center">
            <img src="{{ asset('tailstore4-main/logo/logo.jpg') }}" alt="Shop Logo" class="navbar-shop-logo">
            <span class="text-white font-bold text-lg tracking-wide">Computer Store</span>
        </a>

        <div class="flex lg:hidden">
            <button id="hamburger" class="text-white focus:outline-none" aria-label="Open navigation menu">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>

        @include('partials.desktop-navigation')

        <div class="hidden lg:flex items-center space-x-4 relative">
            @include('partials.header-auth')
            @auth
                {{-- Purchase history link. No "!" alert badge: updates are shown on the My Orders page. --}}
                <a href="{{ route('my-orders') }}" class="relative text-white hover:text-secondary" title="My Orders">
                    <i class="fas fa-receipt text-xl"></i>
                </a>
            @endauth
            <div class="relative group cart-wrapper">
                <a href="{{ route('cart') }}" class="relative">
                    <img src="{{ asset('tailstore4-main/assets/images/cart-shopping.svg') }}" alt="Cart" class="h-6 w-6 group-hover:scale-120">
                </a>
                <div class="absolute right-0 mt-1 w-80 bg-white shadow-lg p-4 rounded hidden group-hover:block">
                    <div class="space-y-4" data-cart-preview>
                        <p class="text-sm text-gray-500">Your cart is empty.</p>
                    </div>
                    <a href="{{ route('cart') }}" class="block text-center mt-4 border border-primary bg-primary hover:bg-transparent text-white hover:text-primary py-2 rounded-full font-semibold">Go to Cart</a>
                </div>
            </div>
            <a id="search-icon" href="javascript:void(0);" class="text-white hover:text-secondary group">
                <img src="{{ asset('tailstore4-main/assets/images/search-icon.svg') }}" alt="Search" class="h-6 w-6 transition-transform transform group-hover:scale-120">
            </a>
            @php($searchQuery = is_string(request()->query('search')) ? trim(request()->query('search')) : '')
            <div id="search-field"
                class="{{ $searchQuery !== '' ? '' : 'hidden' }} absolute top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
                <form method="GET" action="{{ route('shop') }}" class="flex gap-2">
                    <input type="search" name="search" value="{{ $searchQuery }}"
                        class="w-full min-w-0 p-2 border border-gray-300 rounded focus:outline-none focus:border-primary"
                        placeholder="Search for products..." aria-label="Search for products">
                    <button type="submit"
                        class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 rounded-full">Search</button>
                </form>
            </div>
        </div>
    </div>
</header>

<nav id="mobile-menu-placeholder" class="mobile-menu hidden flex flex-col items-center space-y-8 lg:hidden">
    @include('partials.mobile-navigation-links')
    <div class="flex flex-col space-y-2 items-center">
        @auth
            <a href="{{ route('my-orders') }}"
                class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[180px]">
                <i class="fas fa-receipt mr-2"></i>My Orders
            </a>
        @endauth
        @include('partials.mobile-auth', ['showCartLink' => true])
    </div>
</nav>
