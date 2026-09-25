<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('tailstore4-main/logo/logo.jpg') }}" />
    <title>Cart page</title>

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

    <!-- Cart -->
    <section id="cart-page" class="bg-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-semibold mb-4">Shopping Cart</h1>

            <div class="flex flex-col md:flex-row gap-4">
                <div class="md:w-3/4">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                        {{-- Small box: fixed height, scrolls when many products are added --}}
                        <div class="max-h-[22rem] overflow-y-auto overflow-x-auto">
                            {{-- Empty state: centred in the middle of the box --}}
                            <div class="empty-cart-state hidden min-h-[18rem] flex-col items-center justify-center text-center">
                                <img src="{{ asset('tailstore4-main/assets/images/cart-shopping.svg') }}" alt="Empty cart" class="w-20 h-20 md:w-24 md:h-24 opacity-80">
                                <h2 class="mt-4 text-xl md:text-2xl font-semibold text-gray-800">Your cart is empty</h2>
                                <p class="mt-2 text-gray-500">Add some products to keep shopping.</p>
                                <a href="{{ route('cuahangmaytinh') }}" class="mt-5 inline-flex items-center justify-center rounded-full bg-primary border border-primary px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-transparent hover:text-primary">Continue Shopping</a>
                            </div>

                            <table id="cart-table" class="w-full">
                                <thead>
                                    <tr>
                                        <th class="text-center md:text-left font-semibold">Product</th>
                                        <th class="text-center font-semibold">Price</th>
                                        <th class="text-center font-semibold">Quantity</th>
                                        <th class="text-center md:text-right font-semibold">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-items"></tbody>
                            </table>
                        </div>

                        <div id="cart-actions" class="px-1 flex flex-col lg:flex-row justify-between items-center mt-6">
                            <div class="flex items-center">
                                <input type="text" placeholder="Coupon code" class="border border-gray-300 rounded-l-full py-2 px-4 focus:outline-none">
                                <button class="bg-primary text-white border border-primary hover:bg-transparent hover:text-primary rounded-r-full py-2 px-4">Apply Coupon</button>
                            </div>
                            <div class="mt-4 lg:mt-0 flex space-x-2">
                                <button type="button" id="empty-cart" class="bg-primary text-white border border-primary hover:bg-transparent hover:text-primary rounded-full py-2 px-4">Empty Cart</button>
                                <a href="{{ route('cuahangmaytinh') }}" class="bg-primary text-white border border-primary hover:bg-transparent hover:text-primary rounded-full py-2 px-4">Update Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/4">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold mb-4">Summary</h2>
                        <div class="flex justify-between mb-4">
                            <p>Subtotal</p>
                            <p id="cart-subtotal">$0.00</p>
                        </div>
                        <div class="flex justify-between mb-4">
                            <p>Taxes</p>
                            <p id="cart-taxes">$0.00</p>
                        </div>
                        <div class="flex justify-between mb-4 pb-4 border-b border-gray-line">
                            <p>Shipping</p>
                            <p>$0.00</p>
                        </div>
                        <div class="flex justify-between mb-2">
                            <p class="font-semibold">Total</p>
                            <p id="cart-total" class="font-semibold">$0.00</p>
                        </div>
                        <a href="/checkout" class="bg-primary text-white border hover:border-primary hover:bg-transparent hover:text-primary py-2 px-4 rounded-full mt-4 w-full text-center block">Proceed to checkout</a>
                    </div>
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
                <li><a href="{{ route('product') }}" class="hover:text-primary">PC part</a></li>
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
            <div id="contact" class="w-full sm:w-2/6 px-4 mb-8">
            <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
            <p><img src="{{ asset('tailstore4-main/assets/images/template-logo.png') }}" alt="Logo" class="h-[60px] mb-4"></p>
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
</body>

</html>



