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
    @vite('resources/assets/css/custom.css')
</head>

<body>
    <header class="bg-gray-dark sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center py-4">
            <a href="{{ url('/') }}" class="flex items-center">
                <div>
                    <img src="{{ asset('tailstore4-main/assets/images/template-white-logo.png') }}" alt="Logo"
                        class="h-14 w-auto mr-4">
                </div>
            </a>

            <div class="flex lg:hidden">
                <button id="hamburger" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <nav class="hidden lg:flex md:flex-grow justify-center">
                <ul class="flex justify-center space-x-4 text-white">
                    <li><a href="{{ url('/') }}" class="hover:text-secondary font-semibold">Home</a></li>
                    <li class="relative group" x-data="{ open: false }">
                    <li class="relative group" x-data="{ open: false }">
                        <a href="{{ route('shop') }}" @mouseover="open = true" @mouseleave="open = false"
                            class="hover:text-secondary font-semibold flex items-center">
                            PC Part
                            <i
                                :class="open ? 'fas fa-chevron-up ml-1 text-xs' : 'fas fa-chevron-down ml-1 text-xs'"></i>
                        </a>
                        <ul x-show="open" @mouseover="open = true" @mouseleave="open = false"
                            class="absolute left-0 bg-white text-black space-y-2 mt-1 p-2 rounded shadow-lg"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90">
                            <li><a href="{{ route('shop') }}"
                                    class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">CPU</a>
                            </li>
                            <li><a href="{{ route('shop') }}"
                                    class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">GPU</a>
                            </li>
                            <li><a href="{{ route('shop') }}"
                                    class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Motherboard</a>
                            </li>
                        </ul>
                    </li>

                    <!-- Laptop Dropdown -->
                    <li class="relative group" x-data="{ open: false }">
                        <a href="{{ route('shop') }}" @mouseover="open = true" @mouseleave="open = false"
                            class="hover:text-secondary font-semibold flex items-center">
                            Laptop
                            <i
                                :class="open ? 'fas fa-chevron-up ml-1 text-xs' : 'fas fa-chevron-down ml-1 text-xs'"></i>
                        </a>
                        <ul x-show="open" @mouseover="open = true" @mouseleave="open = false"
                            class="absolute left-0 bg-white text-black space-y-2 mt-1 p-2 rounded shadow-lg"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90">
                            <li><a href="{{ route('shop') }}"
                                    class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Gaming</a>
                            </li>
                            <li><a href="{{ route('shop') }}"
                                    class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Business</a>
                            </li>
                            <li><a href="{{ route('shop') }}"
                                    class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Student</a>
                            </li>
                        </ul>
                    </li>

                    <li><a href="{{ route('shop') }}" class="hover:text-secondary font-semibold">Shop</a></li>
                    <li><a href="{{ route('product') }}" class="hover:text-secondary font-semibold">Product</a></li>
                    <li><a href="{{ route('not-found') }}" class="hover:text-secondary font-semibold">404 page</a></li>
                    <li><a href="{{ route('checkout') }}" class="hover:text-secondary font-semibold">Checkout</a></li>
                </ul>
            </nav>

            <div class="hidden lg:flex items-center space-x-4 relative">
                {{-- url bound to register --}}
                <a href="{{ url('/register') }}"
                    class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">Register</a>
                {{-- url bound to login --}}
                    <a href="{{ url('/login') }}"
                    class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">Login</a>
                <div class="relative group cart-wrapper">
                    <a href="{{ route('cart') }}">
                        <img src="{{ asset('tailstore4-main/assets/images/cart-shopping.svg') }}" alt="Cart"
                            class="h-6 w-6 group-hover:scale-120">
                    </a>
                    <div class="absolute right-0 mt-1 w-80 bg-white shadow-lg p-4 rounded hidden group-hover:block">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-4 border-b border-gray-line">
                                <div class="flex items-center">
                                    <img src="{{ asset('tailstore4-main/laptop/laptop_asus_rtx3060.jpg') }}"
                                        alt="Product image" class="h-12 w-12 object-cover rounded mr-2">
                                    <div>
                                        <p class="font-semibold">Gaming Laptop RTX 4060</p>
                                        <p class="text-sm">Quantity: 1</p>
                                    </div>
                                </div>
                                <p class="font-semibold">$25.00</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ asset('tailstore4-main/pc_part/rtx-4070.jpg') }}"
                                        alt="Product image" class="h-12 w-12 object-cover rounded mr-2">
                                    <div>
                                        <p class="font-semibold">NVIDIA RTX 4070 GPU</p>
                                        <p class="text-sm">Quantity: 1</p>
                                    </div>
                                </div>
                                <p class="font-semibold">$125.00</p>
                            </div>
                        </div>
                        <a href="{{ route('cart') }}"
                            class="block text-center mt-4 border border-primary bg-primary hover:bg-transparent text-white hover:text-primary py-2 rounded-full font-semibold">Go
                            to Cart</a>
                    </div>
                </div>
                <a id="search-icon" href="javascript:void(0);" class="text-white hover:text-secondary group">
                    <img src="{{ asset('tailstore4-main/assets/images/search-icon.svg') }}" alt="Search"
                        class="h-6 w-6 transition-transform transform group-hover:scale-120">
                </a>
                <div id="search-field"
                    class="hidden absolute top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
                    <input type="text" class="w-full p-2 border border-gray-300 rounded"
                        placeholder="Search for products...">
                </div>
            </div>
        </div>
    </header>

    <nav id="mobile-menu-placeholder" class="mobile-menu hidden flex-col items-center space-y-8 lg:hidden">
        <ul class="w-full">
            <li><a href="{{ url('/') }}" class="hover:text-secondary font-bold block py-2">Home</a></li>
            <li class="relative group" x-data="{ open: false }">
                <a @click="open = !open; $event.preventDefault()"
                    class="hover:text-secondary font-bold py-2 flex justify-center items-center cursor-pointer">
                    <span>Laptop</span>
                    <span @click.stop="open = !open">
                        <i :class="open ? 'fas fa-chevron-up text-xs ml-2' : 'fas fa-chevron-down text-xs ml-2'"></i>
                    </span>
                </a>
                <ul class="mobile-dropdown-menu" x-show="open" x-transition class="space-y-2">
                    <li><a href="{{ route('shop') }}" class="hover:text-secondary font-bold block pt-2 pb-3">Shop Laptop</a>
                    </li>
                    <li><a href="{{ route('product') }}"
                            class="hover:text-secondary font-bold block py-2">Gaming</a></li>
                    <li><a href="{{ route('product') }}"
                            class="hover:text-secondary font-bold block py-2">Business</a></li>
                    <li><a href="{{ route('product') }}"
                            class="hover:text-secondary font-bold block py-2">Student</a></li>
                </ul>
            </li>

            <li class="relative group" x-data="{ open: false }">
                <a @click="open = !open; $event.preventDefault()"
                    class="hover:text-secondary font-bold py-2 flex justify-center items-center cursor-pointer">
                    <span>Pc part</span>
                    <span @click.stop="open = !open">
                        <i :class="open ? 'fas fa-chevron-up text-xs ml-2' : 'fas fa-chevron-down text-xs ml-2'"></i>
                    </span>
                </a>
                <ul class="mobile-dropdown-menu" x-show="open" x-transition class="pl-4 space-y-2">
                    <li><a href="{{ route('shop') }}" class="hover:text-secondary font-bold block py-2">Shop PC Part</a></li>
                    <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">CPU</a>
                    </li>
                    <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">GPU</a>
                    </li>
                    <li><a href="{{ route('product') }}"
                            class="hover:text-secondary font-bold block py-2">Motherboard</a></li>
                </ul>
            </li>

            <li><a href="{{ route('shop') }}" class="hover:text-secondary font-bold block py-2">Shop</a></li>
            <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Product</a></li>
            <li><a href="{{ route('not-found') }}" class="hover:text-secondary font-bold block py-2">404 page</a></li>
            <li><a href="{{ route('checkout') }}" class="hover:text-secondary font-bold block py-2">Checkout</a></li>
        </ul>
        <div class="flex flex-col mt-6 space-y-2 items-center">
            <a href="{{ url('/register') }}"
                class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Register</a>
            <a href="{{ url('/login') }}"
                class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Login</a>
            <a href="{{ route('cart') }}"
                class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Cart
                -&nbsp;<span>5</span>&nbsp;items</a>
        </div>
        <div class="  top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
            <input type="text" class="w-full p-2 border border-gray-300 rounded"
                placeholder="Search for products...">
        </div>
    </nav>

    <section id="product-slider">
        <div class="main-slider swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('tailstore4-main/carousel/banner_1.jpg') }}" alt="Laptop banner">
                    <div class="swiper-slide-content">
                        <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4">Laptop</h2>
                        <p class="mb-4 text-white md:text-2xl">Power through work and play with <br>high-performance gaming and business laptops.</p>
                        <a href="{{ url('/') }}"
                            class="bg-primary hover:bg-transparent text-white hover:text-white border border-transparent hover:border-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                            now</a>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('tailstore4-main/carousel/banner_2.jpg') }}" alt="PC parts banner">
                    <div class="swiper-slide-content">
                        <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4">PC Part</h2>
                        <p class="mb-4 text-white md:text-2xl">Build your dream setup with premium CPUs, GPUs,<br>and motherboard essentials.</p>
                        <a href="{{ url('/') }}"
                            class="bg-white hover:bg-transparent text-black hover:text-white font-semibold px-4 py-2 rounded-full inline-block border border-transparent hover:border-white">Shop
                            now</a>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('tailstore4-main/carousel/banner_3.jpg') }}" alt="Accessories banner">
                    <div class="swiper-slide-content">
                        <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4">Accessories</h2>
                        <p class="mb-4 text-white md:text-2xl">Upgrade your setup with essential gear<br>for cooling, storage, and productivity.</p>
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
                        <img src="{{ asset('tailstore4-main/assets/images/cat-image1.jpg') }}" alt="Category 1"
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
                        <img src="{{ asset('tailstore4-main/assets/images/cat-image4.jpg') }}" alt="Category 2"
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
                        <img src="{{ asset('tailstore4-main/assets/images/cat-image5.jpg') }}" alt="Category 3"
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

    <!-- index.blade.php (#popular-products) -->
    <section id="popular-products" x-data="{ showModal: false, modalTitle: '', modalCategory: '', modalPrice: '', modalOldPrice: '', modalImg: '' }">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8">Popular products</h2>
            <div class="flex flex-wrap -mx-4">

                <!-- Laptop 1 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <div class="bg-white p-3 rounded-lg shadow-lg">
                        <img src="{{ asset('tailstore4-main/laptop/laptop_asus_rtx3060.jpg') }}" alt="Gaming Laptop"
                            class="w-full object-cover mb-4 rounded-lg cursor-pointer"
                            @click="showModal = true; modalTitle = 'Gaming Laptop RTX 4060'; modalCategory = 'Laptop'; modalPrice = '$999.99'; modalOldPrice = '$1199.99'; modalImg = '{{ asset('tailstore4-main/laptop/laptop_asus_rtx3060.jpg') }}'">
                        <a href="{{ route('product') }}" class="text-lg font-semibold mb-2 block">Gaming Laptop RTX 4060</a>
                        <p class="my-2 text-gray-500">Laptop</p>
                        <div class="flex items-center mb-4">
                            <span class="text-lg font-bold text-primary">$999.99</span>
                            <span class="text-sm line-through ml-2 text-gray-400">$1199.99</span>
                        </div>
                        <button
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                            to Cart</button>
                    </div>
                </div>

                <!-- PC Part 1 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <div class="bg-white p-3 rounded-lg shadow-lg">
                        <img src="{{ asset('tailstore4-main/pc_part/rtx-4070.jpg') }}" alt="NVIDIA RTX 4070"
                            class="w-full object-cover mb-4 rounded-lg cursor-pointer"
                            @click="showModal = true; modalTitle = 'NVIDIA RTX 4070 GPU'; modalCategory = 'PC Part'; modalPrice = '$599.99'; modalOldPrice = ''; modalImg = '{{ asset('tailstore4-main/pc_part/rtx-4070.jpg') }}'">
                        <a href="{{ route('product') }}" class="text-lg font-semibold mb-2 block">NVIDIA RTX 4070 GPU</a>
                        <p class="my-2 text-gray-500">PC Part</p>
                        <div class="flex items-center mb-4">
                            <span class="text-lg font-bold text-gray-900">$599.99</span>
                        </div>
                        <button
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                            to Cart</button>
                    </div>
                </div>

                <!-- PC Part 2 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <div class="bg-white p-3 rounded-lg shadow-lg">
                        <img src="{{ asset('tailstore4-main/pc_part/intel-core-i7-13700k.jpg') }}" alt="Intel i7 13700K"
                            class="w-full object-cover mb-4 rounded-lg cursor-pointer"
                            @click="showModal = true; modalTitle = 'Intel i7 13700K CPU'; modalCategory = 'PC Part'; modalPrice = '$389.99'; modalOldPrice = '$429.99'; modalImg = '{{ asset('tailstore4-main/pc_part/intel-core-i7-13700k.jpg') }}'">
                        <a href="{{ route('product') }}" class="text-lg font-semibold mb-2 block">Intel i7 13700K CPU</a>
                        <p class="my-2 text-gray-500">PC Part</p>
                        <div class="flex items-center mb-4">
                            <span class="text-lg font-bold text-gray-900">$389.99</span>
                            <span class="text-sm line-through ml-2 text-gray-400">$429.99</span>
                        </div>
                        <button
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                            to Cart</button>
                    </div>
                </div>

                <!-- Laptop 2 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <div class="bg-white p-3 rounded-lg shadow-lg">
                        <img src="{{ asset('tailstore4-main/laptop/slim_laptop.jpg') }}"
                            alt="Slim Business Laptop" class="w-full object-cover mb-4 rounded-lg cursor-pointer"
                            @click="showModal = true; modalTitle = 'Slim Business Laptop'; modalCategory = 'Laptop'; modalPrice = '$749.99'; modalOldPrice = '$849.99'; modalImg = '{{ asset('tailstore4-main/laptop/slim_laptop.jpg') }}'">
                        <a href="{{ route('product') }}" class="text-lg font-semibold mb-2 block">Slim Business Laptop</a>
                        <p class="my-2 text-gray-500">Laptop</p>
                        <div class="flex items-center mb-4">
                            <span class="text-lg font-bold text-primary">$749.99</span>
                            <span class="text-sm line-through ml-2 text-gray-400">$849.99</span>
                        </div>
                        <button
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                            to Cart</button>
                    </div>
                </div>

            </div>
        </div>

    </section>

    <section id="latest-products" class="py-10">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8">Latest products</h2>
            <div class="flex flex-wrap -mx-4">

                <!-- PC Part 1 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <div class="bg-white p-3 rounded-lg shadow-lg">
                        <img src="{{ asset('tailstore4-main/pc_part/AMD_ryzen7_7800X3D.jpg') }}"
                            alt="AMD Ryzen 7 7800X3D" class="w-full object-cover mb-4 rounded-lg cursor-pointer"
                            @click="showModal = true; modalTitle = 'AMD Ryzen 7 7800X3D'; modalCategory = 'PC Part'; modalPrice = '$384.00'; modalOldPrice = '$449.00'; modalImg = '{{ asset('tailstore4-main/pc_part/AMD_ryzen7_7800X3D.jpg') }}'">
                        <a href="{{ route('product') }}" class="text-lg font-semibold mb-2 block">AMD Ryzen 7 7800X3D</a>
                        <p class="my-2 text-gray-500">PC Part</p>
                        <div class="flex items-center mb-4">
                            <span class="text-lg font-bold text-primary">$384.00</span>
                            <span class="text-sm line-through ml-2 text-gray-400">$449.00</span>
                        </div>
                        <button
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                            to Cart</button>
                    </div>
                </div>

                <!-- Laptop 1 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <div class="bg-white p-3 rounded-lg shadow-lg">
                        <img src="{{ asset('tailstore4-main/laptop/zephyrus_G16.jpg') }}"
                            alt="ASUS ROG Zephyrus G16" class="w-full object-cover mb-4 rounded-lg cursor-pointer"
                            @click="showModal = true; modalTitle = 'ASUS ROG Zephyrus G16'; modalCategory = 'Laptop'; modalPrice = '$1449.99'; modalOldPrice = ''; modalImg = '{{ asset('tailstore4-main/laptop/zephyrus_G16.jpg') }}'">
                        <a href="{{ route('product') }}" class="text-lg font-semibold mb-2 block">ASUS ROG Zephyrus G16</a>
                        <p class="my-2 text-gray-500">Laptop</p>
                        <div class="flex items-center mb-4">
                            <span class="text-lg font-bold text-gray-900">$1449.99</span>
                        </div>
                        <button
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                            to Cart</button>
                    </div>
                </div>

                <!-- PC Part 2 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <div class="bg-white p-3 rounded-lg shadow-lg">
                        <img src="{{ asset('tailstore4-main/pc_part/Corsair_Vengeance_32G_DDR5.jpg') }}"
                            alt="Corsair Vengeance 32GB DDR5"
                            class="w-full object-cover mb-4 rounded-lg cursor-pointer"
                            @click="showModal = true; modalTitle = 'Corsair Vengeance 32GB DDR5'; modalCategory = 'PC Part'; modalPrice = '$115.99'; modalOldPrice = '$139.99'; modalImg = '{{ asset('tailstore4-main/pc_part/Corsair_Vengeance_32G_DDR5.jpg') }}'">
                        <a href="{{ route('product') }}" class="text-lg font-semibold mb-2 block">Corsair Vengeance 32GB DDR5</a>
                        <p class="my-2 text-gray-500">PC Part</p>
                        <div class="flex items-center mb-4">
                            <span class="text-lg font-bold text-gray-900">$115.99</span>
                            <span class="text-sm line-through ml-2 text-gray-400">$139.99</span>
                        </div>
                        <button
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                            to Cart</button>
                    </div>
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

    <section id="banner" class="relative my-16">
        <div class="container mx-auto px-4 py-20 rounded-lg relative bg-cover bg-center"
            style="background-image: url('{{ asset('tailstore4-main/assets/images/banner1.jpg') }}');">
            <div class="absolute inset-0 bg-black opacity-40 rounded-lg"></div>
            <div class="relative flex flex-col items-center justify-center h-full text-center text-white py-20">
                <h2 class="text-4xl font-bold mb-4">Welcome to Our Shop</h2>
                <div class="flex space-x-4">
                    <a href="#"
                        class="bg-primary hover:bg-transparent text-white hover:text-primary border border-transparent hover:border-primary font-semibold px-4 py-2 rounded-full inline-block">Shop
                        Now</a>
                    <a href="#"
                        class="bg-primary hover:bg-transparent text-white hover:text-primary border border-transparent hover:border-primary font-semibold px-4 py-2 rounded-full inline-block">New
                        Arrivals</a>
                    <a href="#"
                        class="bg-primary hover:bg-transparent text-white hover:text-primary border border-transparent hover:border-primary font-semibold px-4 py-2 rounded-full inline-block">Sale</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="text-center mb-12 lg:mb-20">
            <h2 class="text-5xl font-bold mb-4">Discover <span class="text-primary">Our</span> Blog</h2>
            <p class="my-7">Stay updated with the latest trends, tips, and stories in the world of fashion</p>
        </div>
        <div class="relative items-center w-full px-5 py-12 mx-auto md:px-12 lg:px-24 max-w-7xl">
            <div class="grid w-full grid-cols-1 gap-6 mx-auto lg:grid-cols-3">
                <div class="flex flex-col p-6 bg-white rounded-xl shadow-lg">
                    <img class="object-cover object-center w-full mb-8 rounded-xl"
                        src="{{ asset('tailstore4-main/assets/images/fashion-trends.jpg') }}" alt="blog">
                    <h2 class="mb-2 text-xs font-semibold tracking-widest text-primary uppercase">Fashion Trends</h2>
                    <h1 class="mb-4 text-2xl font-semibold leading-none tracking-tighter text-gray-dark lg:text-3xl">
                        Latest Shirt Trends for 2024</h1>
                    <p class="flex-grow text-base font-medium leading-relaxed text-gray-txt">Explore the hottest shirt
                        trends of 2024. From bold prints to classic styles, stay ahead of the fashion curve with our
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
                    <h2 class="mb-2 text-xs font-semibold tracking-widest text-primary uppercase">Styling Tips</h2>
                    <h1 class="mb-4 text-2xl font-semibold leading-none tracking-tighter text-gray-dark lg:text-3xl">
                        How to Style Your Shirt for Any Occasion</h1>
                    <p class="flex-grow text-base font-medium leading-relaxed text-gray-txt">Learn how to style your
                        shirt for different occasions, whether it's a casual day out or a formal event. Get tips from
                        fashion experts.</p>
                    <div class="mt-8">
                        <a href="#"
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Read
                            more</a>
                    </div>
                </div>
                <div class="flex flex-col p-6 bg-white rounded-xl shadow-lg">
                    <img class="object-cover object-center w-full mb-8 rounded-xl"
                        src="{{ asset('tailstore4-main/assets/images/customer-stories.jpg') }}" alt="blog">
                    <h2 class="mb-2 text-xs font-semibold tracking-widest text-primary uppercase">Customer Stories</h2>
                    <h1 class="mb-4 text-2xl font-semibold leading-none tracking-tighter text-gray-dark lg:text-3xl">
                        Real Stories from Our Happy Customers</h1>
                    <p class="flex-grow text-base font-medium leading-relaxed text-gray-txt">Read about the experiences
                        of our customers. Discover how our shirts have made a difference in their lives and their
                        personal style.</p>
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
                        <li><a href="{{ route('product') }}" class="hover:text-primary">PC part</a></li>
                        <li><a href="{{ route('shop') }}" class="hover:text-primary">Laptop</a></li>
                        <li><a href="{{ route('product') }}" class="hover:text-primary">Shoes</a></li>
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
                        <li><a href="{{ url('/register') }}" class="hover:text-primary">Registration</a></li>
                        <li><a href="{{ url('/login') }}" class="hover:text-primary">Login</a></li>
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
                <div class="w-full sm:w-2/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <p><img src="{{ asset('tailstore4-main/assets/images/template-logo.png') }}" alt="Logo"
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








