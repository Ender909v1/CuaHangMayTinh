<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="{{ asset('tailstore4-main/logo/logo.jpg') }}" />
    <title>Home page</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('tailstore4-main/assets/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ asset('tailstore4-main/assets/css/custom.css') }}">
</head>

<body>
    @include('partials.store-header')

    <section id="product-slider">
        <div class="main-slider swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('tailstore4-main/carousel/banner_1.jpg') }}" alt="Laptop banner" style="filter: brightness(0.4);">
                    <div class="absolute inset-0 bg-black/60"></div>
                    <div class="swiper-slide-content">
                        <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Laptop</h2>
                        <p class="mb-4 text-white md:text-2xl" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Power through work and play with <br>high-performance gaming and business laptops.</p>
                        <a href="{{ url('/') }}"
                            class="bg-primary hover:bg-transparent text-white hover:text-white border border-transparent hover:border-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                            now</a>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('tailstore4-main/carousel/banner_2.jpg') }}" alt="PC parts banner" style="filter: brightness(0.4);">
                    <div class="absolute inset-0 bg-black/60"></div>
                    <div class="swiper-slide-content">
                        <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">PC Part</h2>
                        <p class="mb-4 text-white md:text-2xl" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Build your dream setup with premium CPUs, GPUs,<br>and motherboard essentials.</p>
                        <a href="{{ url('/') }}"
                            class="bg-white hover:bg-transparent text-black hover:text-white font-semibold px-4 py-2 rounded-full inline-block border border-transparent hover:border-white">Shop
                            now</a>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('tailstore4-main/carousel/banner_3.jpg') }}" alt="Accessories banner" style="filter: brightness(0.4);">
                    <div class="absolute inset-0 bg-black/60"></div>
                    <div class="swiper-slide-content">
                        <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Accessories</h2>
                        <p class="mb-4 text-white md:text-2xl" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Upgrade your setup with essential gear<br>for cooling, storage, and productivity.</p>
                        <a href="{{ url('/') }}"
                            class="bg-primary hover:bg-transparent text-white hover:text-white border border-transparent hover:border-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                            now</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </section>

    <section id="product-banners">
        <div class="container mx-auto py-10">
            <div class="flex flex-wrap">
                <div class="w-full sm:w-1/3 px-4 mb-8">
                    <div class="category-banner relative overflow-hidden rounded-lg shadow-lg group">
                        <img src="{{ asset('tailstore4-main/logo/laptop.jpg') }}" alt="Category 1"
                            class="w-full h-auto">
                        <div class="absolute inset-0 bg-gray-light/50"></div>
                        <div
                            class="absolute inset-0 flex flex-col items-center justify-center text-center text-white p-4">
                            <h2 class="text-2xl md:text-3xl font-bold mb-4">Laptop</h2>
                            <a href="{{ url('/') }}"
                                class="bg-primary hover:bg-transparent border border-transparent hover:border-white text-white hover:text-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                                now</a>
                        </div>
                    </div>
                </div>
                <div class="w-full sm:w-1/3 px-4 mb-8">
                    <div class="category-banner relative overflow-hidden rounded-lg shadow-lg group">
                        <img src="{{ asset('tailstore4-main/logo/pc_part.jpg') }}" alt="Category 2"
                            class="w-full h-auto">
                        <div class="absolute inset-0 bg-gray-light/50"></div>
                        <div
                            class="category-text absolute inset-0 flex flex-col items-center justify-center text-center text-white p-4 transition duration-300">
                            <h2 class="text-2xl md:text-3xl font-bold mb-4">PC Part</h2>
                            <a href="{{ url('/') }}"
                                class="bg-primary hover:bg-transparent border border-transparent hover:border-white text-white hover:text-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                                now</a>
                        </div>
                    </div>
                </div>
                <div class="w-full sm:w-1/3 px-4 mb-8">
                    <div class="category-banner relative overflow-hidden rounded-lg shadow-lg group">
                        <img src="{{ asset('tailstore4-main/logo/accessories.jpg') }}" alt="Category 3"
                            class="w-full h-auto">
                        <div class="absolute inset-0 bg-gray-light/50"></div>
                        <div
                            class="category-text absolute inset-0 flex flex-col items-center justify-center text-center text-white p-4 transition duration-300">
                            <h2 class="text-2xl md:text-3xl font-bold mb-4">Accessories</h2>
                            <a href="{{ url('/') }}"
                                class="bg-primary hover:bg-transparent border border-transparent hover:border-white text-white hover:text-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                                now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="recommended-products" class="bg-white py-10" data-product-url="{{ route('product') }}">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-2">Recommended for you</h2>
            <p id="recommendation-message" class="text-gray-500 mb-6">Similar picks based on our popular products.</p>
            <div id="recommendation-list" class="flex flex-wrap -mx-4"></div>
        </div>
    </section>

    <!-- index.blade.php (#popular-products) -->
    <section id="popular-products" x-data="{ showModal: false, modalTitle: '', modalCategory: '', modalPrice: '', modalOldPrice: '', modalImg: '' }">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8">Popular products</h2>
            <div class="flex flex-wrap -mx-4">
                @foreach ($products->take(4) as $product)
                    @php
                        $imageUrl = $product->images->first()?->image_url ?? asset('tailstore4-main/logo/logo.jpg');
                        $displayPrice = $product->discount_price ?? $product->price;
                        $oldPrice = $product->discount_price ? $product->price : null;
                    @endphp
                    <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                        <div class="bg-white p-3 rounded-lg shadow-lg">
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                class="w-full object-cover mb-4 rounded-lg cursor-pointer"
                                @click="showModal = true; modalTitle = '{{ addslashes($product->name) }}'; modalCategory = '{{ addslashes($product->category->name ?? 'Product') }}'; modalPrice = '${{ number_format((float) $displayPrice, 2) }}'; modalOldPrice = '{{ $oldPrice ? '$'.number_format((float) $oldPrice, 2) : '' }}'; modalImg = '{{ $imageUrl }}'">
                            <a href="{{ route('product', $product) }}" class="text-lg font-semibold mb-2 block">{{ $product->name }}</a>
                            <p class="my-2 text-gray-500">{{ $product->category->name ?? 'Product' }}</p>
                            <div class="flex items-center mb-4">
                                <span class="text-lg font-bold text-primary">${{ number_format((float) $displayPrice, 2) }}</span>
                                @if ($oldPrice)
                                    <span class="text-sm line-through ml-2 text-gray-400">${{ number_format((float) $oldPrice, 2) }}</span>
                                @endif
                            </div>
                            <button class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add to Cart</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="latest-products" class="py-10">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8">Latest products</h2>
            <div class="flex flex-wrap -mx-4">
                @foreach ($products->slice(4, 4) as $product)
                    @php
                        $imageUrl = $product->images->first()?->image_url ?? asset('tailstore4-main/logo/logo.jpg');
                        $displayPrice = $product->discount_price ?? $product->price;
                        $oldPrice = $product->discount_price ? $product->price : null;
                    @endphp
                    <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                        <div class="bg-white p-3 rounded-lg shadow-lg">
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                class="w-full object-cover mb-4 rounded-lg cursor-pointer"
                                @click="showModal = true; modalTitle = '{{ addslashes($product->name) }}'; modalCategory = '{{ addslashes($product->category->name ?? 'Product') }}'; modalPrice = '${{ number_format((float) $displayPrice, 2) }}'; modalOldPrice = '{{ $oldPrice ? '$'.number_format((float) $oldPrice, 2) : '' }}'; modalImg = '{{ $imageUrl }}'">
                            <a href="{{ route('product', $product) }}" class="text-lg font-semibold mb-2 block">{{ $product->name }}</a>
                            <p class="my-2 text-gray-500">{{ $product->category->name ?? 'Product' }}</p>
                            <div class="flex items-center mb-4">
                                <span class="text-lg font-bold text-primary">${{ number_format((float) $displayPrice, 2) }}</span>
                                @if ($oldPrice)
                                    <span class="text-sm line-through ml-2 text-gray-400">${{ number_format((float) $oldPrice, 2) }}</span>
                                @endif
                            </div>
                            <button class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add to Cart</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
                </div>

                <!-- Laptop 2 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <div class="bg-white p-3 rounded-lg shadow-lg">
                        <img src="{{ asset('tailstore4-main/laptop/Macbook_M13_pro_14inch.jpg') }}"
                            alt="MacBook Pro M3 14-inch" class="w-full object-cover mb-4 rounded-lg cursor-pointer"
                            @click="showModal = true; modalTitle = 'MacBook Pro M3 14-inch'; modalCategory = 'Laptop'; modalPrice = '$1599.00'; modalOldPrice = '$1799.00'; modalImg = '{{ asset('tailstore4-main/laptop/Macbook_M13_pro_14inch.jpg') }}'">
                        <a href="{{ route('product') }}" class="text-lg font-semibold mb-2 block">MacBook Pro M3 14-inch</a>
                        <p class="my-2 text-gray-500">Laptop</p>
                        <div class="flex items-center mb-4">
                            <span class="text-lg font-bold text-primary">$1599.00</span>
                            <span class="text-sm line-through ml-2 text-gray-400">$1799.00</span>
                        </div>
                        <button
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                            to Cart</button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="brands" class="bg-white py-16 px-4">
        <div class="container mx-auto max-w-screen-xl px-4 testimonials">
            <div class="text-center mb-12 lg:mb-20">
                <h2 class="text-5xl font-bold mb-4">Discover <span class="text-primary">Our Brands</span></h2>
                <p class="my-7">Explore the top brands we feature in our store</p>
            </div>
            <div class="swiper brands-swiper-slider">
                <div class="swiper-wrapper">
                    <div class="swiper-slide flex-none bg-gray-200 flex items-center justify-center rounded-md">
                        <img src="{{ asset('tailstore4-main/assets/images/brands/html.svg') }}" alt="Client Logo"
                            class="max-h-full max-w-full">
                    </div>
                    <div class="swiper-slide flex-none bg-gray-200 flex items-center justify-center rounded-md">
                        <img src="{{ asset('tailstore4-main/assets/images/brands/js.svg') }}" alt="Client Logo"
                            class="max-h-full max-w-full">
                    </div>
                    <div class="swiper-slide flex-none bg-gray-200 flex items-center justify-center rounded-md">
                        <img src="{{ asset('tailstore4-main/assets/images/brands/laravel.svg') }}" alt="Client Logo"
                            class="max-h-full max-w-full">
                    </div>
                    <div class="swiper-slide flex-none bg-gray-200 flex items-center justify-center rounded-md">
                        <img src="{{ asset('tailstore4-main/assets/images/brands/php.svg') }}" alt="Client Logo"
                            class="max-h-full max-w-full">
                    </div>
                    <div class="swiper-slide flex-none bg-gray-200 flex items-center justify-center rounded-md">
                        <img src="{{ asset('tailstore4-main/assets/images/brands/react.svg') }}" alt="Client Logo"
                            class="max-h-full max-w-full">
                    </div>
                    <div class="swiper-slide flex-none bg-gray-200 flex items-center justify-center rounded-md">
                        <img src="{{ asset('tailstore4-main/assets/images/brands/tailwind.svg') }}" alt="Client Logo"
                            class="max-h-full max-w-full">
                    </div>
                    <div class="swiper-slide flex-none bg-gray-200 flex items-center justify-center rounded-md">
                        <img src="{{ asset('tailstore4-main/assets/images/brands/typescript.svg') }}"
                            alt="Client Logo" class="max-h-full max-w-full">
                    </div>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
    </section>

    <section class="py-16">
        <div class="text-center mb-12 lg:mb-20">
            <h2 class="text-5xl font-bold mb-4">Discover <span class="text-primary">Our</span> Tech Blog</h2>
            <p class="my-7">Stay updated with the latest technology trends, hardware reviews, and PC building tips</p>
        </div>
        <div class="relative items-center w-full px-5 py-12 mx-auto md:px-12 lg:px-24 max-w-7xl">
            <div class="grid w-full grid-cols-1 gap-6 mx-auto lg:grid-cols-3">
                <div class="flex flex-col p-6 bg-white rounded-xl shadow-lg">
                    <img class="object-cover object-center w-full mb-8 rounded-xl"
                        src="{{ asset('tailstore4-main/assets/images/fashion-trends.jpg') }}" alt="blog">
                    <h2 class="mb-2 text-xs font-semibold tracking-widest text-primary uppercase">Hardware</h2>
                    <h1 class="mb-4 text-2xl font-semibold leading-none tracking-tighter text-gray-dark lg:text-3xl">
                        Latest GPU Trends for 2024</h1>
                    <p class="flex-grow text-base font-medium leading-relaxed text-gray-txt">Explore the newest graphics cards
                        and performance benchmarks. From RTX 40 series to AMD RDNA3, stay ahead of the gaming curve with our
                        expert insights.</p>
                    <div class="mt-8">
                        <a href="#"
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Read
                            more</a>
                    </div>
                </div>
                <div class="flex flex-col p-6 bg-white rounded-xl shadow-lg">
                    <img class="object-cover object-center w-full mb-8 rounded-xl"
                        src="{{ asset('tailstore4-main/assets/images/stylisng-tips.jpg') }}" alt="blog">
                    <h2 class="mb-2 text-xs font-semibold tracking-widest text-primary uppercase">PC Building</h2>
                    <h1 class="mb-4 text-2xl font-semibold leading-none tracking-tighter text-gray-dark lg:text-3xl">
                        How to Build Your First Gaming PC</h1>
                    <p class="flex-grow text-base font-medium leading-relaxed text-gray-txt">Learn how to assemble your
                        own gaming PC from scratch. From choosing the right components to cable management, get tips from
                        hardware experts.</p>
                    <div class="mt-8">
                        <a href="#"
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Read
                            more</a>
                    </div>
                </div>
                <div class="flex flex-col p-6 bg-white rounded-xl shadow-lg">
                    <img class="object-cover object-center w-full mb-8 rounded-xl"
                        src="{{ asset('tailstore4-main/assets/images/customer-stories.jpg') }}" alt="blog">
                    <h2 class="mb-2 text-xs font-semibold tracking-widest text-primary uppercase">Reviews</h2>
                    <h1 class="mb-4 text-2xl font-semibold leading-none tracking-tighter text-gray-dark lg:text-3xl">
                        Real Reviews from Our Happy Customers</h1>
                    <p class="flex-grow text-base font-medium leading-relaxed text-gray-txt">Read about the experiences
                        of our customers. Discover how our products have improved their gaming and productivity setups.</p>
                    <div class="mt-8">
                        <a href="#"
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Read
                            more</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="subscribe" class="py-6 lg:py-24 bg-white border-t border-gray-line">
        <div class="container mx-auto">
            <div class="flex flex-col items-center rounded-lg p-4 sm:p-0 ">
                <div class="mb-8">
                    <h2 class="text-center text-xl font-bold sm:text-2xl lg:text-left lg:text-3xl">Join our newsletter
                        and <span class="text-primary">get $50 discount</span> for your first order
                    </h2>
                </div>
                <div class="flex flex-col items-center w-96 ">
                    <form class="flex w-full gap-2">
                        <input placeholder="Enter your email address"
                            class="w-full flex-1 rounded-full px-3 py-2 border border-gray-300 text-gray-700 placeholder-gray-500 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary" />
                        <button
                            class="bg-primary border border-primary hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-gray-line">
        <div class="container mx-auto px-4 py-10">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full sm:w-1/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Shop</h3>
                    <ul>
                        <li><a href="{{ route('shop') }}" class="hover:text-primary">Shop</a></li>
                        <li><a href="{{ route('product') }}" class="hover:text-primary">PC Part</a></li>
                        <li><a href="{{ route('shop') }}" class="hover:text-primary">Laptop</a></li>
                        <li><a href="{{ route('product') }}" class="hover:text-primary">Accessories</a></li>
                    </ul>
                </div>
                <div class="w-full sm:w-1/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Pages</h3>
                    <ul>
                        <li><a href="{{ route('shop') }}" class="hover:text-primary">Shop</a></li>
                        <li><a href="{{ route('product') }}" class="hover:text-primary">Product</a></li>
                        <li><a href="{{ route('checkout') }}" class="hover:text-primary">Checkout</a></li>
                        <li><a href="{{ route('not-found') }}" class="hover:text-primary">404</a></li>
                    </ul>
                </div>
                <div class="w-full sm:w-1/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Account</h3>
                    <ul>
                        <li><a href="{{ route('cart') }}" class="hover:text-primary">Cart</a></li>
                        @include('partials.footer-account')
                    </ul>
                </div>
                <div class="w-full sm:w-1/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                    <ul>
                        <li class="flex items-center mb-2">
                            <img src="{{ asset('tailstore4-main/assets/images/social_icons/facebook.svg') }}"
                                alt="Facebook" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-primary">Facebook</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="{{ asset('tailstore4-main/assets/images/social_icons/twitter.svg') }}"
                                alt="Twitter" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-primary">Twitter</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="{{ asset('tailstore4-main/assets/images/social_icons/instagram.svg') }}"
                                alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-primary">Instagram</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="{{ asset('tailstore4-main/assets/images/social_icons/pinterest.svg') }}"
                                alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-primary">Pinterest</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="{{ asset('tailstore4-main/assets/images/social_icons/youtube.svg') }}"
                                alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-primary">YouTube</a>
                        </li>
                    </ul>
                </div>
                <div id="contact" class="w-full sm:w-2/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <p><img src="{{ asset('tailstore4-main/logo/logo.jpg') }}" alt="Logo"
                            class="h-[60px] mb-4"></p>
                    <p>123 Street Name, Paris, France</p>
                    <p class="text-xl font-bold my-4">Phone: (123) 456-7890</p>
                    <a href="mailto:info@company.com" class="underline">Email: info@company.com</a>
                </div>
            </div>
        </div>
        <div class="py-6 border-t border-gray-line">
            <div class="container mx-auto px-4 flex flex-wrap justify-between items-center">
                <div class="w-full lg:w-3/4 text-center lg:text-left mb-4 lg:mb-0">
                    <p class="mb-2 font-bold">&copy; 2024 Your Company. All rights reserved.</p>
                    <ul class="flex justify-center lg:justify-start space-x-4 mb-4 lg:mb-0">
                        <li><a href="#" class="hover:text-primary">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-primary">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-primary">FAQ</a></li>
                    </ul>
                    <p class="text-sm mt-4">Your shop's description goes here. This is a brief introduction to your
                        shop and what you offer.</p>
                </div>
                <div class="w-full lg:w-1/4 text-center lg:text-right">
                    <img src="{{ asset('tailstore4-main/assets/images/social_icons/paypal.svg') }}" alt="PayPal"
                        class="inline-block h-8 mr-2">
                    <img src="{{ asset('tailstore4-main/assets/images/social_icons/stripe.svg') }}" alt="Stripe"
                        class="inline-block h-8 mr-2">
                    <img src="{{ asset('tailstore4-main/assets/images/social_icons/visa.svg') }}" alt="Visa"
                        class="inline-block h-8">
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('tailstore4-main/assets/js/script.js') }}"></script>

</body>

</html>





