<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('tailstore4-main/logo/logo.jpg') }}" />
    <title>Single product page</title>

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
            <a href="{{ route('cuahangmaytinh') }}" class="flex items-center">
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
                  <li><a href="{{ route('cuahangmaytinh') }}" class="hover:text-secondary font-semibold">Home</a></li>

                  <!-- Men Dropdown -->
                  <li class="relative group" x-data="{ open: false }">
                      <a href="{{ route('shop') }}" @mouseover="open = true" @mouseleave="open = false" class="hover:text-secondary font-semibold flex items-center">
                          Laptop
                          <i :class="open ? 'fas fa-chevron-up ml-1 text-xs' : 'fas fa-chevron-down ml-1 text-xs'"></i>
                      </a>
                      <ul
                          x-show="open"
                          @mouseover="open = true"
                          @mouseleave="open = false"
                          class="absolute left-0 bg-white text-black space-y-2 mt-1 p-2 rounded shadow-lg"
                          x-transition:enter="transition ease-out duration-100"
                          x-transition:enter-start="opacity-0 scale-90"
                          x-transition:enter-end="opacity-100 scale-100"
                          x-transition:leave="transition ease-in duration-100"
                          x-transition:leave-start="opacity-100 scale-100"
                          x-transition:leave-end="opacity-0 scale-90"
                      >
                          <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Gaming Laptop</a></li>
                          <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Business Laptop</a></li>
                          <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Student Laptop</a></li>
                      </ul>
                  </li>

                  <!-- Women Dropdown -->
                  <li class="relative group" x-data="{ open: false }">
                      <a href="{{ route('shop') }}" @mouseover="open = true" @mouseleave="open = false" class="hover:text-secondary font-semibold flex items-center">
                          PC Part
                          <i :class="open ? 'fas fa-chevron-up ml-1 text-xs' : 'fas fa-chevron-down ml-1 text-xs'"></i>
                      </a>
                      <ul
                          x-show="open"
                          @mouseover="open = true"
                          @mouseleave="open = false"
                          class="absolute left-0 bg-white text-black space-y-2 mt-1 p-2 rounded shadow-lg"
                          x-transition:enter="transition ease-out duration-100"
                          x-transition:enter-start="opacity-0 scale-90"
                          x-transition:enter-end="opacity-100 scale-100"
                          x-transition:leave="transition ease-in duration-100"
                          x-transition:leave-start="opacity-100 scale-100"
                          x-transition:leave-end="opacity-0 scale-90"
                      >
                          <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">CPU</a></li>
                          <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">GPU</a></li>
                          <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Mother broad</a></li>
                      </ul>
                  </li>

                  <li><a href="{{ route('shop') }}" class="hover:text-secondary font-semibold">Shop</a></li>
                  <li><a href="{{ route('product') }}" class="hover:text-secondary font-semibold">Product</a></li>
                  <li><a href="{{ route('not-found') }}" class="hover:text-secondary font-semibold">404 page</a></li>
                  <li><a href="{{ route('checkout') }}" class="hover:text-secondary font-semibold">Checkout</a></li>
              </ul>
            </nav>

            <!-- Right section: Buttons (for desktop) -->
            <div class="hidden lg:flex items-center space-x-4 relative">
              <a href="{{ route('register') }}"
                  class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">Register</a>
              <a href="{{ route('login') }}"
                  class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">Login</a>
              <div class="relative group cart-wrapper">
                  <a href="{{ route('cart') }}" >
                      <img src="{{ asset('tailstore4-main/assets/images/cart-shopping.svg') }}" alt="Cart" class="h-6 w-6 group-hover:scale-120">
                  </a>
                  <!-- Cart dropdown -->
                  <div class="absolute right-0 mt-1 w-80 bg-white shadow-lg p-4 rounded hidden group-hover:block">
                      <div class="space-y-4">
                          <!-- product item -->
                          <div class="flex items-center justify-between pb-4 border-b border-gray-line">
                              <div class="flex items-center">
                                  <img src="{{ asset('tailstore4-main/laptop/laptop_asus_rtx3060.jpg') }}" alt="Gaming Laptop RTX 4060" class="h-12 w-12 object-cover rounded mr-2">
                                  <div>
                                      <p class="font-semibold">Gaming Laptop RTX 4060</p>
                                      <p class="text-sm">Quantity: 1</p>
                                  </div>
                              </div>
                              <p class="font-semibold">$25.00</p>
                          </div>
                          <!-- product item -->
                          <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="{{ asset('tailstore4-main/pc_part/rtx-4070.jpg') }}" alt="NVIDIA RTX 4070 GPU" class="h-12 w-12 object-cover rounded mr-2">
                                <div>
                                    <p class="font-semibold">NVIDIA RTX 4070 GPU</p>
                                    <p class="text-sm">Quantity: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold">$125.00</p>
                        </div>
                      </div>
                      <a href="{{ route('cart') }}" class="block text-center mt-4 border border-primary bg-primary hover:bg-transparent text-white hover:text-primary py-2 rounded-full font-semibold">Go to Cart</a>
                  </div>
              </div>
              <a id="search-icon" href="javascript:void(0);" class="text-white hover:text-secondary group">
                  <img src="{{ asset('tailstore4-main/assets/images/search-icon.svg') }}" alt="Search"
                      class="h-6 w-6 transition-transform transform group-hover:scale-120">
              </a>
              <!-- Search field -->
              <div id="search-field"
                  class="hidden absolute top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
                  <input type="text" class="w-full p-2 border border-gray-300 rounded"
                      placeholder="Search for products...">
              </div>
          </div>
        </div>
    </header>

    <!-- Mobile menu -->
    <nav id="mobile-menu-placeholder" class="mobile-menu hidden flex-col items-center space-y-8 lg:hidden">
      <ul class="w-full">
          <li><a href="{{ route('cuahangmaytinh') }}" class="hover:text-secondary font-bold block py-2">Home</a></li>

          <!-- Men Dropdown -->
          <li class="relative group" x-data="{ open: false }">
              <a @click="open = !open; $event.preventDefault()" class="hover:text-secondary font-bold py-2 flex justify-center items-center cursor-pointer">
                <span>Men</span>
                <span @click.stop="open = !open">
                    <i :class="open ? 'fas fa-chevron-up text-xs ml-2' : 'fas fa-chevron-down text-xs ml-2'"></i>
                </span>
              </a>
              <ul class="mobile-dropdown-menu" x-show="open" x-transition class="space-y-2">
                  <li><a href="{{ route('shop') }}" class="hover:text-secondary font-bold block pt-2 pb-3">Shop Men</a></li>
                  <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Men item 1</a></li>
                  <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Men item 2</a></li>
                  <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Men item 3</a></li>
              </ul>
          </li>

          <!-- Women Dropdown -->
          <li class="relative group" x-data="{ open: false }">
              <a @click="open = !open; $event.preventDefault()" class="hover:text-secondary font-bold py-2 flex justify-center items-center cursor-pointer">
                    <span>Women</span>
                    <span @click.stop="open = !open">
                        <i :class="open ? 'fas fa-chevron-up text-xs ml-2' : 'fas fa-chevron-down text-xs ml-2'"></i>
                    </span>
              </a>
              <ul class="mobile-dropdown-menu" x-show="open" x-transition class="pl-4 space-y-2">
                  <li><a href="{{ route('shop') }}" class="hover:text-secondary font-bold block py-2">Shop Women</a></li>
                  <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Women item 1</a></li>
                  <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Women item 2</a></li>
                  <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Women item 3</a></li>
              </ul>
          </li>

          <li><a href="{{ route('shop') }}" class="hover:text-secondary font-bold block py-2">Shop</a></li>
          <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Product</a></li>
          <li><a href="{{ route('not-found') }}" class="hover:text-secondary font-bold block py-2">404 page</a></li>
          <li><a href="{{ route('checkout') }}" class="hover:text-secondary font-bold block py-2">Checkout</a></li>
      </ul>
      <div class="flex flex-col mt-6 space-y-2 items-center">
          <a href="{{ route('register') }}"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full items-center justify-center min-w-[110px]">Register</a>
          <a href="{{ route('login') }}"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full items-center justify-center min-w-[110px]">Login</a>
          <a href="{{ route('cart') }}"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full items-center justify-center min-w-[110px]">Cart -&nbsp;<span>5</span>&nbsp;items</a>
      </div>
      <!-- Search field -->
      <div
          class="  top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
          <input type="text" class="w-full p-2 border border-gray-300 rounded"
              placeholder="Search for products...">
      </div>
    </nav>

    <!-- Breadcrumbs -->
    <section id="breadcrumbs" class="pt-6 bg-gray-50">
        <div class="container mx-auto px-4">
            <ol class="list-reset flex">
                <li><a href="{{ route('cuahangmaytinh') }}" class="font-semibold hover:text-primary">Home</a></li>
                <li><span class="mx-2">&gt;</span></li>
                <li><a href="{{ route('shop') }}" class="font-semibold hover:text-primary">Shop</a></li>
                <li><span class="mx-2">&gt;</span></li>
                <li><a href="{{ route('shop') }}" class="font-semibold hover:text-primary">Laptops</a></li>
                <li><span class="mx-2">&gt;</span></li>
                <li>Gaming Laptop RTX 4060</li>
            </ol>
        </div>
    </section>

    <!-- Product info -->
    <section id="product-info">
        <div class="container mx-auto px-4">
            <div class="py-6">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Image Section -->
                    <div class="w-full lg:w-1/2">
                        <div class="grid gap-4">
                            <!-- Big Image -->
                            <div id="main-image-container">
                                <img id="main-image"
                                    class="h-auto w-full max-w-full rounded-lg object-cover object-center md:h-[480px]"
                                    src="{{ asset('tailstore4-main/laptop/laptop_asus_rtx3060.jpg') }}"
                                    alt="Main Product Image" />
                            </div>
                            <!-- Small Images -->
                            <div class="grid grid-cols-5 gap-4">
                                <div>
                                    <img onclick="changeImage(this)"
                                    data-full="{{ asset('tailstore4-main/laptop/laptop_asus_rtx3060.jpg') }}"
                                    src="{{ asset('tailstore4-main/laptop/laptop_asus_rtx3060.jpg') }}"
                                    class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer"
                                    alt="Gallery Image 1" />
                                </div>
                                <div>
                                    <img onclick="changeImage(this)"
                                    data-full="{{ asset('tailstore4-main/laptop/laptop-hp-omen-16-2025.jpg') }}"
                                    src="{{ asset('tailstore4-main/laptop/laptop-hp-omen-16-2025.jpg') }}"
                                    class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer"
                                    alt="Gallery Image 2" />
                                </div>
                                <div>
                                    <img onclick="changeImage(this)"
                                    data-full="{{ asset('tailstore4-main/laptop/slim_laptop.jpg') }}"
                                    src="{{ asset('tailstore4-main/laptop/slim_laptop.jpg') }}"
                                    class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer"
                                    alt="Gallery Image 3" />
                                </div>
                                <div>
                                    <img onclick="changeImage(this)"
                                    data-full="{{ asset('tailstore4-main/laptop/surface_laptop.jpg') }}"
                                    src="{{ asset('tailstore4-main/laptop/surface_laptop.jpg') }}"
                                    class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer"
                                    alt="Gallery Image 4" />
                                </div>
                                <div>
                                    <img onclick="changeImage(this)"
                                    data-full="{{ asset('tailstore4-main/laptop/zephyrus_G16.jpg') }}"
                                    src="{{ asset('tailstore4-main/laptop/zephyrus_G16.jpg') }}"
                                    class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer"
                                    alt="Gallery Image 5" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product Details Section -->
                    <div class="w-full lg:w-1/2 flex flex-col justify-between">
                        <div class="pb-8 border-b border-gray-line">
                            <h1 class="text-3xl font-bold mb-4">Gaming Laptop RTX 4060</h1>
                            <div class="flex items-center mb-8">
                                <span>★★★★★</span>
                                <span class="ml-2">(0 Reviews)</span>
                                <a href="#" class="ml-4 text-primary font-semibold">Write a review</a>
                            </div>
                            <div class="mb-4 pb-4 border-b border-gray-line">
                                <p class="mb-2">Category:<strong><a href="{{ route('shop') }}" class="hover:text-primary"> Laptop</a></strong>
                                </p>
                                <p class="mb-2">Product code:<strong> LAP-RTX4060</strong></p>
                                <p class="mb-2">Availability:<strong> In Stock</strong></p>
                            </div>
                            <div class="text-2xl font-semibold mb-8">$999.99</div>
                            <div class="flex items-center mb-8">
                                <button id="decrease"
                                    class="bg-primary hover:bg-transparent border border-transparent hover:border-primary text-white hover:text-primary font-semibold w-10 h-10 rounded-full flex items-center justify-center focus:outline-none"
                                    disabled>-</button>
                                <input id="quantity" type="number" value="1"
                                    class="w-16 py-2 text-center focus:outline-none" readonly>
                                <button id="increase"
                                    class="bg-primary hover:bg-transparent border border-transparent hover:border-primary text-white hover:text-primary font-semibold  w-10 h-10 rounded-full focus:outline-none">+</button>
                            </div>
                            <button
                                class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full">Add
                                to Cart</button>
                        </div>
                        <!-- Social sharing -->
                        <div class="flex space-x-4 my-6">
                            <a href="#" class="w-4 h-4 flex items-center justify-center">
                                <img src="{{ asset('tailstore4-main/assets/images/social_icons/facebook.svg') }}" alt="Facebook"
                                    class="w-4 h-4 transition-transform transform hover:scale-110">
                            </a>
                            <a href="#" class="w-4 h-4 flex items-center justify-center">
                                <img src="{{ asset('tailstore4-main/assets/images/social_icons/instagram.svg') }}" alt="Instagram"
                                    class="w-4 h-4 transition-transform transform hover:scale-110">
                            </a>
                            <a href="#" class="w-4 h-4 flex items-center justify-center">
                                <img src="{{ asset('tailstore4-main/assets/images/social_icons/pinterest.svg') }}" alt="Pinterest"
                                    class="w-4 h-4 transition-transform transform hover:scale-110">
                            </a>
                            <a href="#" class="w-4 h-4 flex items-center justify-center">
                                <img src="{{ asset('tailstore4-main/assets/images/social_icons/twitter.svg') }}" alt="Twitter"
                                    class="w-4 h-4 transition-transform transform hover:scale-110">
                            </a>
                            <a href="#" class="w-4 h-4 flex items-center justify-center">
                                <img src="{{ asset('tailstore4-main/assets/images/social_icons/viber.svg') }}" alt="Viber"
                                    class="w-4 h-4 transition-transform transform hover:scale-110">
                            </a>
                        </div>
                        <!-- Additional Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Product Description</h3>
                            <p>Gaming laptop with RTX 4060 graphics, fast SSD storage, and a high-refresh display.
                                Built for reliable work, study, and gaming performance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product tabs description -->
    <section>
        <div class="container mx-auto px-4">
            <div class="py-12">
                <div class="mt-10">
                    <div class="flex space-x-4" role="tablist">
                        <button id="description-tab" role="tab" aria-controls="description-content" aria-selected="true"
                            class="tab active">Description</button>
                        <button id="additional-info-tab" role="tab" aria-controls="additional-info-content"
                            aria-selected="false" class="tab">Additional information</button>
                        <button id="size-shape-tab" role="tab" aria-controls="size-shape-content" aria-selected="false"
                            class="tab">Specifications</button>
                        <button id="reviews-tab" role="tab" aria-controls="reviews-content" aria-selected="false"
                            class="tab">Reviews (3)</button>
                    </div>
                    <div class="mt-8">
                        <div id="description-content" role="tabpanel" aria-labelledby="description-tab"
                            class="tab-content">
                            <div class="flex flex-col lg:flex-row lg:space-x-8">
                                <div class="w-full lg:w-1/2">
                                    <h3 class="text-xl font-semibold mb-2">Gaming performance for work, study, and play.</h3>
                                    <p class="mb-4">This ASUS laptop combines RTX 4060 graphics, a high-refresh display,
                                        and fast storage for demanding applications and modern games.</p>
                                </div>
                                <div class="w-full lg:w-1/4">
                                    <h3 class="text-xl font-semibold mb-5">Hardware</h3>
                                    <p class="mb-2 pb-2 border-b border-gray-line">Processor: <span
                                            class="font-semibold">AMD Ryzen 7 class</span></p>
                                    <p class="mb-2 pb-2 border-b border-gray-line">Memory: <span
                                            class="font-semibold">16GB RAM</span></p>
                                    <p class="mb-2">Storage: <span class="font-semibold">512GB NVMe SSD</span></p>
                                </div>
                                <div class="w-full lg:w-1/4">
                                    <h3 class="text-xl font-semibold mb-5">Display & Design</h3>
                                    <p class="mb-2 pb-2 border-b border-gray-line">Screen: <span
                                            class="font-semibold">16-inch QHD, high refresh rate</span></p>
                                    <p class="mb-2 pb-2 border-b border-gray-line">Keyboard: <span
                                            class="font-semibold">Backlit keyboard</span></p>
                                    <p class="mb-2 pb-2 border-b border-gray-line">Display: <span
                                            class="font-semibold">16-inch QHD</span></p>
                                    <p class="mb-2">Graphics: <span class="font-semibold">RTX 4060</span></p>
                                </div>
                            </div>
                        </div>
                        <div id="additional-info-content" role="tabpanel" aria-labelledby="additional-info-tab"
                            class="tab-content hidden">
                            <p>Additional information about the product.</p>
                            <div class="flex flex-col space-y-8">
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Colors</h3>
                                    <p class="text-base text-gray-700">
                                        Available configurations:
                                        <a href="{{ route('product') }}" class="text-primary hover:underline">16GB RAM</a>,
                                        <a href="{{ route('product') }}" class="text-primary hover:underline">512GB SSD</a>,
                                        <a href="{{ route('product') }}" class="text-primary hover:underline">RTX 4060</a>.
                                    </p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Brand</h3>
                                    <p class="text-base text-gray-700">
                                        This laptop is made by
                                        <a href="{{ route('product') }}" class="text-primary hover:underline">ASUS</a>.
                                    </p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Material & Care</h3>
                                    <p class="text-base text-gray-700">
                                        Chassis: Aluminum alloy
                                        <br>
                                        Features: Backlit keyboard, Wi-Fi 6, and high-performance cooling.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="size-shape-content" role="tabpanel" aria-labelledby="size-shape-tab"
                            class="tab-content hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-6 py-3 border-b border-gray-line bg-gray-100 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider">
                                                Size
                                            </th>
                                            <th
                                                class="px-6 py-3 border-b border-gray-line bg-gray-100 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider">
                                                Chest (inches)
                                            </th>
                                            <th
                                                class="px-6 py-3 border-b border-gray-line bg-gray-100 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider">
                                                Waist (inches)
                                            </th>
                                            <th
                                                class="px-6 py-3 border-b border-gray-line bg-gray-100 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider">
                                                Sleeve Length (inches)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                Small
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                34-36
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                28-30
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                32-33
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                Medium
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                38-40
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                32-34
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                33-34
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                Large
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                42-44
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                36-38
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                34-35
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                X-Large
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                46-48
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                40-42
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-line text-sm leading-5 text-gray-700">
                                                35-36
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div id="reviews-content" role="tabpanel" aria-labelledby="reviews-tab"
                            class="tab-content hidden">
                            <!-- Reviews List -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold mb-4">Customer Reviews</h3>
                                <div id="reviews-list">
                                    <!-- Review 1 -->
                                    <div class="py-4">
                                        <div class="flex items-center mb-2">
                                            <span class="text-lg font-semibold text-gray-700">John Doe</span>
                                            <span class="ml-2 text-primary">★★★★★</span>
                                        </div>
                                        <p>Excellent performance and display. Highly recommend this laptop.</p>
                                    </div>
                                    <!-- Review 2 -->
                                    <div class="border-t border-gray-line py-4">
                                        <div class="flex items-center mb-2">
                                            <span class="text-lg font-semibold text-gray-700">Jane Smith</span>
                                            <span class="ml-2 text-primary">★★★★☆</span>
                                        </div>
                                        <p>Fast graphics and comfortable keyboard. Battery life could be longer.</p>
                                    </div>
                                    <!-- Review 3 -->
                                    <div class="border-t border-gray-line py-4">
                                        <div class="flex items-center mb-2">
                                            <span class="text-lg font-semibold text-gray-700">Alice Johnson</span>
                                            <span class="ml-2 text-primary">★★★★★</span>
                                        </div>
                                        <p>Solid build quality and smooth gaming performance. Would buy again.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Review Form -->
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold mb-4">Write a Review</h3>
                                <form id="review-form" class="space-y-4">
                                    <div class="space-y-4 md:flex md:space-x-4 md:space-y-0">
                                        <div class="md:flex-1">
                                            <label for="review-name"
                                                class="block text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" id="review-name" name="review-name"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                        </div>
                                        <div class="md:flex-1">
                                            <label for="review-email"
                                                class="block text-sm font-medium text-gray-700">Email</label>
                                            <input type="email" id="review-email" name="review-email"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                        </div>
                                        <div class="md:flex-1">
                                            <label for="review-rating"
                                                class="block text-sm font-medium text-gray-700">Rating</label>
                                            <select id="review-rating" name="review-rating"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                                <option value="5">★★★★★</option>
                                                <option value="4">★★★★☆</option>
                                                <option value="3">★★★☆☆</option>
                                                <option value="2">★★☆☆☆</option>
                                                <option value="1">★☆☆☆☆</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="review-text"
                                            class="block text-sm font-medium text-gray-700">Review</label>
                                        <textarea id="review-text" name="review-text" rows="4"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"></textarea>
                                    </div>
                                    <div>
                                        <button type="submit"
                                            class="bg-primary hover:bg-transparent border border-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full focus:outline-none">Submit
                                            Review</button>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest-products -->
    <section id="latest-products" class="py-10">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8">Latest products</h2>
            <div class="flex flex-wrap -mx-4">
                <!-- Product 1 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                  <div class="bg-white p-3 rounded-lg shadow-lg">
                    <img src="{{ asset('tailstore4-main/laptop/laptop_asus_rtx3060.jpg') }}" alt="Gaming Laptop RTX 4060" class="w-full object-cover mb-4 rounded-lg">
                    <a href="{{ route('product') }}" class="text-lg font-semibold mb-2">Gaming Laptop RTX 4060</a>
                    <p class=" my-2">Laptop</p>
                    <div class="flex items-center mb-4">
                      <span class="text-lg font-bold text-primary">$999.99</span>
                      <span class="text-sm line-through ml-2">$1199.99</span>
                    </div>
                    <button class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add to Cart</button>
                  </div>
                </div>
                <!-- Product 2 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                  <div class="bg-white p-3 rounded-lg shadow-lg">
                    <img src="{{ asset('tailstore4-main/pc_part/rtx-4070.jpg') }}" alt="NVIDIA RTX 4070 GPU" class="w-full object-cover mb-4 rounded-lg">
                    <a href="{{ route('product') }}" class="text-lg font-semibold mb-2">NVIDIA RTX 4070 GPU</a>
                    <p class=" my-2">PC Part</p>
                    <div class="flex items-center mb-4">
                      <span class="text-lg font-bold text-gray-900">$599.99</span>
                    </div>
                    <button class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add to Cart</button>
                  </div>
                </div>
                <!-- Product 3 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                  <div class="bg-white p-3 rounded-lg shadow-lg">
                    <img src="{{ asset('tailstore4-main/pc_part/intel-core-i7-13700k.jpg') }}" alt="Intel i7 13700K CPU" class="w-full object-cover mb-4 rounded-lg">
                    <a href="{{ route('product') }}" class="text-lg font-semibold mb-2">Intel i7 13700K CPU</a>
                    <p class="my-2">PC Part</p>
                    <div class="flex items-center mb-4">
                      <span class="text-lg font-bold text-gray-900">$389.99</span>
                      <span class="text-sm line-through  ml-2">$429.99</span>
                    </div>
                    <button class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add to Cart</button>
                  </div>
                </div>
                <!-- Product 4 -->
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                  <div class="bg-white p-3 rounded-lg shadow-lg">
                    <img src="{{ asset('tailstore4-main/laptop/Macbook_M13_pro_14inch.jpg') }}" alt="MacBook Pro M3 14-inch" class="w-full object-cover mb-4 rounded-lg">
                    <a href="{{ route('product') }}" class="text-lg font-semibold mb-2">MacBook Pro M3 14-inch</a>
                    <p class="my-2">Laptop</p>
                    <div class="flex items-center mb-4">
                        <span class="text-lg font-bold text-primary">$1599.00</span>
                        <span class="text-sm line-through ml-2">$1799.00</span>
                    </div>
                    <button class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add to Cart</button>
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
                <li><a href="{{ route('product') }}" class="hover:text-primary">Laptop</a></li>
                <li><a href="{{ route('shop') }}" class="hover:text-primary">Gaming Laptop</a></li>
                <li><a href="{{ route('product') }}" class="hover:text-primary">Business Laptop</a></li>
                <li><a href="{{ route('product') }}" class="hover:text-primary">Student Laptop</a></li>
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
                <li><a href="{{ route('register') }}" class="hover:text-primary">Registration</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-primary">Login</a></li>
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

    <script src="node_modules/swiper/swiper-bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('tailstore4-main/assets/js/script.js') }}"></script>

</body>

</html>


