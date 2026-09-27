<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('tailstore4-main/logo/logo.jpg') }}" />
    <title>Checkout page</title>

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

    <!-- Checkout -->
    <section id="checkout-page" class="bg-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-semibold mb-8">Checkout</h1>

            {{-- Hidden product catalogue so checkout can resolve product ids for older cart lines. --}}
            <div class="hidden" aria-hidden="true">
                @foreach (\App\Models\Product::select('id', 'name')->orderBy('id')->get() as $catalogProduct)
                    <a href="#" data-product-link data-product-id="{{ $catalogProduct->id }}" data-product-name="{{ $catalogProduct->name }}"></a>
                @endforeach
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-4">
                <!-- Billing and Shipping Details -->
                <div class="md:w-2/3 bg-white rounded-lg shadow-md p-4">
                    <h2 class="text-xl font-semibold mb-4">Billing Details</h2>
                    <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}">
                        @csrf
                        <input type="hidden" name="items_json" id="checkout-items-json" value="">
                        <div class="mb-4">
                            <label for="billing-name" class="mb-4">Full Name</label>
                            <input type="text" id="billing-name" name="full_name" value="{{ old('full_name', $billingName) }}" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div class="mb-4">
                            <label for="billing-email" class="mb-4">Email</label>
                            <input type="email" id="billing-email" name="email" value="{{ old('email', $billingEmail) }}" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div class="mb-4">
                            <label for="billing-address" class="mb-4">Address</label>
                            <input type="text" id="billing-address" name="address" value="{{ old('address', $billingAddress) }}" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div class="mb-4">
                            <label for="billing-city" class="mb-4">City</label>
                            <input type="text" id="billing-city" name="city" value="{{ old('city') }}" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="mb-4 flex gap-4">
                            <div class="w-1/2">
                                <label for="billing-state" class="mb-4">State</label>
                                <input type="text" id="billing-state" name="state" value="{{ old('state') }}" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div class="w-1/2">
                                <label for="billing-zip" class="mb-4">ZIP Code</label>
                                <input type="text" id="billing-zip" name="zip" value="{{ old('zip') }}" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="billing-phone" class="mb-4">Phone Number</label>
                            <input type="tel" id="billing-phone" name="phone" value="{{ old('phone', $billingPhone) }}" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div class="mb-4">
                            <label for="payment-method" class="mb-4">Payment Method</label>
                            <select id="payment-method" name="payment_method" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="cod">Cash on Delivery</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="e_wallet">E-Wallet</option>
                            </select>
                        </div>
                    </form>
                </div>
                <!-- Order Summary -->
                <div class="md:w-1/3 bg-white rounded-lg shadow-md p-4">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    <div id="checkout-items" class="mb-4 space-y-3 text-sm"></div>
                    <div class="flex justify-between mb-4">
                        <p>Subtotal</p>
                        <p id="checkout-subtotal">0 ₫</p>
                    </div>
                    <div class="flex justify-between mb-4">
                        <p>Shipping</p>
                        <p>0 ₫</p>
                    </div>
                    <div class="flex justify-between mb-4">
                        <p class="font-semibold">Total</p>
                        <p class="font-semibold" id="checkout-total">0 ₫</p>
                    </div>
                    <p id="checkout-error" class="mb-3 hidden text-sm text-red-600">Your cart is empty. Please add some products first.</p>
                    <button type="submit" form="checkout-form" id="checkout-submit" class="bg-primary text-white border border-primary hover:bg-transparent hover:text-primary py-2 px-4 rounded-full w-full">Proceed to Payment</button>
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
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('checkout-form');
            const itemsInput = document.getElementById('checkout-items-json');
            const errorBox = document.getElementById('checkout-error');
            if (!form || !itemsInput) return;

            const readCart = () => {
                try {
                    const cart = JSON.parse(localStorage.getItem('computer-store-cart') || '[]');
                    return Array.isArray(cart) ? cart : [];
                } catch { return []; }
            };

            const renderSummary = () => {
                const cart = readCart();
                const list = document.getElementById('checkout-items');
                const money = (n) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', maximumFractionDigits: 0 }).format(n || 0);
                const subtotal = cart.reduce((s, i) => s + (Number(i.price) || 0) * (Number(i.quantity) || 0), 0);
                if (list) {
                    list.innerHTML = '';
                    if (!cart.length) {
                        list.innerHTML = '<p class="text-gray-500">Your cart is empty.</p>';
                    } else {
                        cart.forEach((item) => {
                            const row = document.createElement('div');
                            row.className = 'flex justify-between gap-2';
                            const label = document.createElement('span');
                            label.textContent = `${item.name} x ${item.quantity}`;
                            const amount = document.createElement('span');
                            amount.textContent = money((Number(item.price) || 0) * (Number(item.quantity) || 0));
                            row.appendChild(label);
                            row.appendChild(amount);
                            list.appendChild(row);
                        });
                    }
                }
                const sub = document.getElementById('checkout-subtotal');
                const tot = document.getElementById('checkout-total');
                if (sub) sub.textContent = money(subtotal);
                if (tot) tot.textContent = money(subtotal);
            };

            renderSummary();
            window.addEventListener('storage', (e) => { if (e.key === 'computer-store-cart') renderSummary(); });

            // Backfill product_id for cart lines saved before product ids were tracked.
            const backfillProductIds = () => {
                const links = Array.from(document.querySelectorAll('[data-product-link]'));
                if (!links.length) return;
                const byKey = new Map();
                links.forEach((link) => {
                    const id = Number.parseInt(link.dataset.productId || '', 10);
                    const name = (link.dataset.productName || '').toLowerCase();
                    if (id && name) byKey.set(name, id);
                });
                if (!byKey.size) return;
                let changed = false;
                const cart = readCart();
                cart.forEach((item) => {
                    if (!Number(item.product_id) && item.name && byKey.has(String(item.name).toLowerCase())) {
                        item.product_id = byKey.get(String(item.name).toLowerCase());
                        changed = true;
                    }
                });
                if (changed) {
                    try { localStorage.setItem('computer-store-cart', JSON.stringify(cart)); } catch (e) {}
                    renderSummary();
                }
            };
            backfillProductIds();

            form.addEventListener('submit', function (event) {
                const cart = readCart().filter((i) => Number(i.product_id) > 0 && Number(i.quantity) > 0);
                if (!cart.length) {
                    event.preventDefault();
                    if (errorBox) errorBox.classList.remove('hidden');
                    return;
                }
                if (errorBox) errorBox.classList.add('hidden');
                // Remove stale inputs then attach one hidden input per item line.
                form.querySelectorAll('input[data-cart-item]').forEach((el) => el.remove());
                cart.forEach((item, index) => {
                    const pid = document.createElement('input');
                    pid.type = 'hidden';
                    pid.name = `items[${index}][product_id]`;
                    pid.setAttribute('data-cart-item', '');
                    pid.value = item.product_id;
                    form.appendChild(pid);
                    const qty = document.createElement('input');
                    qty.type = 'hidden';
                    qty.name = `items[${index}][quantity]`;
                    qty.setAttribute('data-cart-item', '');
                    qty.value = item.quantity;
                    form.appendChild(qty);
                });
                itemsInput.value = JSON.stringify(cart);
            });
        });
    </script>
</body>

</html>



