<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('tailstore4-main/logo/logo.jpg') }}" />
    <title>Register</title>

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
    @include('partials.store-header')

    <!-- Register and login -->
    <section id="register-login-page" class="bg-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row justify-center items-stretch gap-6 max-w-5xl mx-auto">
                <div class="w-full lg:w-1/2 lg:max-w-md bg-white rounded-lg shadow-md p-4 md:p-10">
                    <h2 class="text-2xl font-semibold mb-4">Login</h2>
                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="login-email" class="block ">Email</label>
                            <input type="email" name="email" id="login-email" class="w-full px-3 py-1 border  rounded-full focus:border-transparent focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="block ">Password</label>
                            <input type="password" name="password" id="login-password" class="w-full px-3 py-1 border  rounded-full focus:border-transparent focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div class="flex items-center mb-3">
                            <input type="checkbox" id="remember-me" class="mr-2">
                            <label for="remember-me" class="">Remember Me</label>
                        </div>
                        <div class="mb-3">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-primary hover:underline">Forgot Password?</a>
                            @endif
                        </div>
                        <button type="submit" class="bg-primary text-white border border-primary hover:bg-transparent hover:text-primary py-2 px-3 rounded-full w-full">Login</button>
                    </form>
                </div>
                <div class="w-full lg:w-1/2 lg:max-w-md bg-white rounded-lg shadow-md p-4 md:p-10">
                    <h2 class="text-2xl font-semibold mb-4">Register</h2>
                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                            <p class="font-semibold mb-1">Please fix the following:</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('register.submit') }}" id="register-form" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="register-name" class="block ">Full name</label>
                            <input type="text" name="full_name" id="register-name" value="{{ old('full_name') }}" class="w-full px-3 py-1 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary @error('full_name') border-red-500 @enderror" required autocomplete="name">
                            @error('full_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="register-email" class="block ">Email</label>
                            <input type="email" name="email" id="register-email" value="{{ old('email') }}" class="w-full px-3 py-1 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary @error('email') border-red-500 @enderror" required autocomplete="email">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="register-password" class="block ">Password</label>
                            <input type="password" name="password" id="register-password" aria-describedby="password-requirements password-announcer" class="w-full px-3 py-1 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary @error('password') border-red-500 @enderror" required autocomplete="new-password">
                            <p id="password-announcer" class="sr-only" role="status" aria-live="polite"></p>
                            <ul id="password-requirements" class="mt-2 space-y-1 text-sm">
                                <li id="req-length" class="flex items-center gap-2 text-gray-500"><span class="req-icon" aria-hidden="true">○</span> At least 6 characters</li>
                                <li id="req-match" class="flex items-center gap-2 text-gray-500"><span class="req-icon" aria-hidden="true">○</span> Passwords match</li>
                            </ul>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="register-confirm-password" class="block ">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="register-confirm-password" class="w-full px-3 py-1 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary" required autocomplete="new-password">
                        </div>
                        <button type="submit" id="register-submit" class="bg-primary text-white border border-primary hover:bg-transparent hover:text-primary py-2 px-3 rounded-full w-full disabled:opacity-50 disabled:cursor-not-allowed">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-line">
        <!-- Top part -->
        <div class="container mx-auto px-4 py-10">
        <div class="flex flex-wrap -mx-4">
            <!-- Menu 1 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
            <h3 class="text-lg font-semibold mb-4">Shop</h3>
            <ul>
                <li><a href="{{ route('shop') }}" class="hover:text-primary">Shop</a></li>
                <li><a href="{{ route('product') }}" class="hover:text-primary">PC Part</a></li>
                <li><a href="{{ route('shop') }}" class="hover:text-primary">Laptop</a></li>
                <li><a href="{{ route('product') }}" class="hover:text-primary">Accessories</a></li>
            </ul>
            </div>
            <!-- Menu 2 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
            <h3 class="text-lg font-semibold mb-4">Pages</h3>
            <ul>
                <li><a href="{{ route('shop') }}" class="hover:text-primary">Shop</a></li>
                <li><a href="{{ route('product') }}" class="hover:text-primary">Product</a></li>
                <li><a href="{{ route('checkout') }}" class="hover:text-primary">Checkout</a></li>
                <li><a href="{{ route('not-found') }}" class="hover:text-primary">404</a></li>
            </ul>
            </div>
            <!-- Menu 3 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
            <h3 class="text-lg font-semibold mb-4">Account</h3>
            <ul>
                <li><a href="{{ route('cart') }}" class="hover:text-primary">Cart</a></li>
                @include('partials.footer-account')
            </ul>
            </div>
            <!-- Social Media -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
            <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
            <ul>
                <li class="flex items-center mb-2">
                <img src="{{ asset('tailstore4-main/assets/images/social_icons/facebook.svg') }}" alt="Facebook" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                <a href="#" class="hover:text-primary">Facebook</a>
                </li>
                <li class="flex items-center mb-2">
                <img src="{{ asset('tailstore4-main/assets/images/social_icons/twitter.svg') }}" alt="Twitter" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                <a href="#" class="hover:text-primary">Twitter</a>
                </li>
                <li class="flex items-center mb-2">
                <img src="{{ asset('tailstore4-main/assets/images/social_icons/instagram.svg') }}" alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                <a href="#" class="hover:text-primary">Instagram</a>
                </li>
                <li class="flex items-center mb-2">
                <img src="{{ asset('tailstore4-main/assets/images/social_icons/pinterest.svg') }}" alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                <a href="#" class="hover:text-primary">Pinterest</a>
                </li>
                <li class="flex items-center mb-2">
                <img src="{{ asset('tailstore4-main/assets/images/social_icons/youtube.svg') }}" alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                <a href="#" class="hover:text-primary">YouTube</a>
                </li>
            </ul>
            </div>
            <!-- Contact Information -->
            <div class="w-full sm:w-2/6 px-4 mb-8">
            <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
            <p><img src="{{ asset('tailstore4-main/logo/logo.jpg') }}" alt="Logo" class="h-[60px] mb-4"></p>
            <p>123 Street Name, Paris, France</p>
            <p class="text-xl font-bold my-4">Phone: (123) 456-7890</p>
            <a href="mailto:info@company.com" class="underline">Email: info@company.com</a>
            </div>
        </div>
        </div>

        <!-- Bottom part -->
        <div class="py-6 border-t border-gray-line">
        <div class="container mx-auto px-4 flex flex-wrap justify-between items-center">
            <!-- Copyright and Links -->
            <div class="w-full lg:w-3/4 text-center lg:text-left mb-4 lg:mb-0">
            <p class="mb-2 font-bold">&copy; 2024 Your Company. All rights reserved.</p>
            <ul class="flex justify-center lg:justify-start space-x-4 mb-4 lg:mb-0">
                <li><a href="#" class="hover:text-primary">Privacy Policy</a></li>
                <li><a href="#" class="hover:text-primary">Terms of Service</a></li>
                <li><a href="#" class="hover:text-primary">FAQ</a></li>
            </ul>
            <p class="text-sm mt-4">Your shop's description goes here. This is a brief introduction to your shop and what you offer.</p>
            </div>
            <!-- Payment Icons -->
            <div class="w-full lg:w-1/4 text-center lg:text-right">
            <img src="{{ asset('tailstore4-main/assets/images/social_icons/paypal.svg') }}" alt="PayPal" class="inline-block h-8 mr-2">
            <img src="{{ asset('tailstore4-main/assets/images/social_icons/stripe.svg') }}" alt="Stripe" class="inline-block h-8 mr-2">
            <img src="{{ asset('tailstore4-main/assets/images/social_icons/visa.svg') }}" alt="Visa" class="inline-block h-8">
            </div>
        </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('tailstore4-main/assets/js/script.js') }}"></script>
    <script>
        (function () {
            const passwordInput = document.getElementById('register-password');
            const confirmInput = document.getElementById('register-confirm-password');
            const announcer = document.getElementById('password-announcer');
            const submitBtn = document.getElementById('register-submit');
            const form = document.getElementById('register-form');
            if (!passwordInput || !confirmInput || !announcer) return;

            const rules = {
                'req-length': (pw) => pw.length >= 6,
                'req-match': (pw, confirm) => pw.length > 0 && pw === confirm,
            };

            const messages = {
                'req-length': 'at least 6 characters',
                'req-match': 'matching passwords',
            };

            function setRowState(id, passed) {
                const li = document.getElementById(id);
                if (!li) return;
                const icon = li.querySelector('.req-icon');
                li.classList.remove('text-gray-500', 'text-green-600', 'text-red-600');
                if (passwordInput.value.length === 0 && id !== 'req-match') {
                    li.classList.add('text-gray-500');
                    if (icon) icon.textContent = '○';
                    return;
                }
                if (passed) {
                    li.classList.add('text-green-600');
                    if (icon) icon.textContent = '●';
                } else {
                    li.classList.add('text-red-600');
                    if (icon) icon.textContent = '○';
                }
            }

            function validate(live) {
                const pw = passwordInput.value;
                const confirm = confirmInput.value;
                const missing = [];

                Object.keys(rules).forEach((id) => {
                    const passed = id === 'req-match'
                        ? rules[id](pw, confirm)
                        : rules[id](pw);
                    setRowState(id, passed);
                    if (!passed) missing.push(messages[id]);
                });

                const allPassed = missing.length === 0;

                // Screen-reader + visual announcer (turn-back condition when typing)
                if (pw.length === 0 && confirm.length === 0) {
                    announcer.textContent = '';
                    announcer.className = 'sr-only';
                } else if (allPassed) {
                    announcer.textContent = 'Password looks good. All requirements met.';
                    announcer.className = 'mt-2 text-sm text-green-600';
                } else {
                    announcer.textContent = 'Password still needs: ' + missing.join(', ') + '.';
                    announcer.className = 'mt-2 text-sm text-red-600';
                }

                // Turn back invalid typing state on inputs
                passwordInput.classList.toggle('border-red-500', pw.length > 0 && !allPassed);
                confirmInput.classList.toggle('border-red-500', confirm.length > 0 && pw !== confirm);

                if (submitBtn) submitBtn.disabled = live ? false : submitBtn.disabled;

                return allPassed;
            }

            passwordInput.addEventListener('input', () => validate(true));
            confirmInput.addEventListener('input', () => validate(true));

            form.addEventListener('submit', (e) => {
                if (!validate(false)) {
                    e.preventDefault();
                    announcer.textContent = 'Please fix your password: ' + announcer.textContent;
                    passwordInput.focus();
                }
            });

            validate(true);
        })();
    </script>
</body>

</html>






