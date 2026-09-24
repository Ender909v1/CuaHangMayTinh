<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('tailstore4-main/logo/logo.jpg') }}" />
    <title>Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('tailstore4-main/assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('tailstore4-main/node_modules/swiper/swiper-bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('tailstore4-main/assets/css/custom.css') }}">
</head>

<body>
    <!-- Header -->
    <header class="bg-gray-dark sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center py-4">
            <!-- Left section: Logo -->
            <a href="{{ url('/') }}" class="flex items-center">
                <div>
                    <img src="{{ asset('tailstore4-main/assets/images/template-white-logo.png') }}" alt="Logo" class="h-14 w-auto mr-4">
                </div>
            </a>

            <!-- Hamburger menu (for mobile) -->
            <div class="flex lg:hidden">
                <button id="hamburger" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <!-- Center section: Menu -->
            <nav class="hidden lg:flex md:flex-grow justify-center">
                <ul class="flex justify-center space-x-4 text-white">
                    <li><a href="{{ url('/') }}" class="hover:text-secondary font-semibold">Home</a></li>
                    <li><a href="{{ route('shop') }}" class="hover:text-secondary font-semibold">Shop</a></li>
                    <li><a href="{{ route('checkout') }}" class="hover:text-secondary font-semibold">Checkout</a></li>
                </ul>
            </nav>

            <!-- Right section -->
            <div class="hidden lg:flex items-center space-x-4 relative">
                <a href="{{ route('cart') }}">
                    <img src="{{ asset('tailstore4-main/assets/images/cart-shopping.svg') }}" alt="Cart" class="h-6 w-6">
                </a>
            </div>
        </div>
    </header>

    <!-- Login -->
    <section id="login-page" class="bg-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex justify-center">
                <div class="w-full md:w-1/2 bg-white rounded-lg shadow-md p-4 md:p-10">
                    <h2 class="text-2xl font-semibold mb-4">Login</h2>

                    @if (session('status'))
                        <div class="mb-4 text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="block">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email') }}"
                                class="w-full px-3 py-1 border rounded-full focus:border-transparent focus:outline-none focus:ring-2 focus:ring-primary @error('email') border-red-500 @enderror"
                                required autofocus>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="block">Password</label>
                            <input type="password" name="password" id="password"
                                class="w-full px-3 py-1 border rounded-full focus:border-transparent focus:outline-none focus:ring-2 focus:ring-primary @error('password') border-red-500 @enderror"
                                required>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="remember" id="remember-me" class="mr-2"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember-me">Remember Me</label>
                        </div>

                        <div class="mb-3">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-primary hover:underline">Forgot Password?</a>
                            @endif
                        </div>

                        <button type="submit"
                            class="bg-primary text-white border border-primary hover:bg-transparent hover:text-primary py-2 px-3 rounded-full w-full">
                            Login
                        </button>
                    </form>

                    <p class="mt-4 text-sm">
                        No account? <a href="{{ route('register') }}" class="text-primary hover:underline">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-line">
        <div class="py-6 border-t border-gray-line">
            <div class="container mx-auto px-4 text-center">
                <p class="font-bold">&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('tailstore4-main/assets/js/script.js') }}"></script>
</body>

</html>







