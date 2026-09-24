<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('tailstore4-main/assets/images/favicon.png') }}" />
    <title>404 - Page not found</title>

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
            <a href="index.html" class="flex items-center">
              <div>
                  <img src="assets/images/template-white-logo.png" alt="Logo" class="h-14 w-auto mr-4">
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
                  <li><a href="index.html" class="hover:text-secondary font-semibold">Home</a></li>

                  <!-- Laptop Dropdown -->
                  <li class="relative group" x-data="{ open: false }">
                      <a href="shop.html" @mouseover="open = true" @mouseleave="open = false" href="#" class="hover:text-secondary font-semibold flex items-center">
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
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Gaming Laptop</a></li>
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Business Laptop</a></li>
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Student Laptop</a></li>
                      </ul>
                  </li>

                  <!-- PC Part Dropdown -->
                  <li class="relative group" x-data="{ open: false }">
                      <a href="shop.html" @mouseover="open = true" @mouseleave="open = false" href="#" class="hover:text-secondary font-semibold flex items-center">
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
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">CPU</a></li>
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">GPU</a></li>
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Motherboard</a></li>
                      </ul>
                  </li>

                  <li><a href="shop.html" class="hover:text-secondary font-semibold">Shop</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-semibold">Product</a></li>
                  <li><a href="404.html" class="hover:text-secondary font-semibold">404 page</a></li>
                  <li><a href="checkout.html" class="hover:text-secondary font-semibold">Checkout</a></li>
              </ul>
            </nav>

            <!-- Right section: Buttons (for desktop) -->
            <div class="hidden lg:flex items-center space-x-4 relative">
              <a href="register.html"
                  class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">Register</a>
              <a href="register.html"
                  class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">Login</a>
              <div class="relative group cart-wrapper">
                  <a href="/cart.html" >
                      <img src="assets/images/cart-shopping.svg" alt="Cart" class="h-6 w-6 group-hover:scale-120">
                  </a>
                  <!-- Cart dropdown -->
                  <div class="absolute right-0 mt-1 w-80 bg-white shadow-lg p-4 rounded hidden group-hover:block">
                      <div class="space-y-4">
                          <!-- product item -->
                          <div class="flex items-center justify-between pb-4 border-b border-gray-line">
                              <div class="flex items-center">
                                  <img src="/assets/images/single-product/1.jpg" alt="Product" class="h-12 w-12 object-cover rounded mr-2">
                                  <div>
                                      <p class="font-semibold">Summer black dress</p>
                                      <p class="text-sm">Quantity: 1</p>
                                  </div>
                              </div>
                              <p class="font-semibold">$25.00</p>
                          </div>
                          <!-- product item -->
                          <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="/assets/images/single-product/2.jpg" alt="Product" class="h-12 w-12 object-cover rounded mr-2">
                                <div>
                                    <p class="font-semibold">Black suit</p>
                                    <p class="text-sm">Quantity: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold">$125.00</p>
                        </div>
                      </div>
                      <a href="/cart.html" class="block text-center mt-4 border border-primary bg-primary hover:bg-transparent text-white hover:text-primary py-2 rounded-full font-semibold">Go to Cart</a>
                  </div>
              </div>
              <a id="search-icon" href="javascript:void(0);" class="text-white hover:text-secondary group">
                  <img src="assets/images/search-icon.svg" alt="Search"
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
          <li><a href="index.html" class="hover:text-secondary font-bold block py-2">Home</a></li>

          <!-- Laptop Dropdown -->
          <li class="relative group" x-data="{ open: false }">
              <a @click="open = !open; $event.preventDefault()" class="hover:text-secondary font-bold py-2 flex justify-center items-center cursor-pointer">
                <span>Laptop</span>
                <span @click.stop="open = !open">
                    <i :class="open ? 'fas fa-chevron-up text-xs ml-2' : 'fas fa-chevron-down text-xs ml-2'"></i>
                </span>
              </a>
              <ul class="mobile-dropdown-menu" x-show="open" x-transition class="space-y-2">
                  <li><a href="shop.html" class="hover:text-secondary font-bold block pt-2 pb-3">Shop Laptop</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Gaming Laptop</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Business Laptop</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Ultrabook</a></li>
              </ul>
          </li>

          <!-- PC Part Dropdown -->
          <li class="relative group" x-data="{ open: false }">
              <a @click="open = !open; $event.preventDefault()" class="hover:text-secondary font-bold py-2 flex justify-center items-center cursor-pointer">
                    <span>PC Part</span>
                    <span @click.stop="open = !open">
                        <i :class="open ? 'fas fa-chevron-up text-xs ml-2' : 'fas fa-chevron-down text-xs ml-2'"></i>
                    </span>
              </a>
              <ul class="mobile-dropdown-menu" x-show="open" x-transition class="pl-4 space-y-2">
                  <li><a href="shop.html" class="hover:text-secondary font-bold block py-2">Shop PC Part</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">CPU</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">GPU</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Motherboard</a></li>
              </ul>
          </li>

          <li><a href="shop.html" class="hover:text-secondary font-bold block py-2">Shop</a></li>
          <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Product</a></li>
          <li><a href="404.html" class="hover:text-secondary font-bold block py-2">404 page</a></li>
          <li><a href="checkout.html" class="hover:text-secondary font-bold block py-2">Checkout</a></li>
      </ul>
      <div class="flex flex-col mt-6 space-y-2 items-center">
          <a href="register.html"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Register</a>
          <a href="register.html"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Login</a>
          <a href="register.html"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Cart -&nbsp;<span>5</span>&nbsp;items</a>
      </div>
      <!-- Search field -->
      <div 
          class="  top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
          <input type="text" class="w-full p-2 border border-gray-300 rounded"
              placeholder="Search for products...">
      </div>
    </nav>

    <!-- Shop -->
    <section id="shop">
        <div class="container mx-auto">
            <!-- Top Filter -->
            <div class="flex flex-col md:flex-row justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <button
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">Show
                        On
                        Sale</button>
                    <button
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">List
                        View</button>
                    <button
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">Grid
                        View</button>
                </div>
                <div class="flex mt-5 md:mt-0 space-x-4">
                    <div class="relative">
                        <select
                            class="block appearance-none w-full bg-white border  hover:border-primary px-4 py-2 pr-8 rounded-full shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option>Sort by Latest</option>
                            <option>Sort by Popularity</option>
                            <option>Sort by A-Z</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center justify-center px-2">
                            <img id="arrow-down" class="h-4 w-4" src="/assets/images/filter-down-arrow.svg"
                                alt="filter arrow">
                            <img id="arrow-up" class="h-4 w-4 hidden" src="/assets/images/filter-up-arrow.svg"
                                alt="filter arrow">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filter Toggle Button for Mobile -->
            <div class="block md:hidden text-center mb-4">
                <button id="products-toggle-filters"
                    class="bg-primary text-white py-2 px-4 rounded-full focus:outline-none">Show Filters</button>
            </div>
            <div class="flex flex-col md:flex-row">
                <!-- Filters -->
                <div id="filters" class="w-full md:w-1/4 p-4 hidden md:block">
                    <!-- Category Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Category</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">Laptop</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">PC Part</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">Accessories</span>
                            </label>
                        </div>
                    </div>
                    <!-- Filter by Specs -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Specs</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">Intel Core</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">AMD Ryzen</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">RTX Graphics</span>
                            </label>
                        </div>
                    </div>
                    <!-- Price Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Price</h3>
                        <div class="space-y-2">
                            <label class="flex items-center custom-color-checkbox" data-color="#ff0000">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">Under $500</span>
                            </label>
                            <label class="flex items-center custom-color-checkbox" data-color="#0000ff">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">$500 - $1000</span>
                            </label>
                            <label class="flex items-center custom-color-checkbox" data-color="#00ff00">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">Above $1000</span>
                            </label>
                        </div>
                    </div>
                    <!-- Brand Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Brand</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">ASUS</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">MSI</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">NVIDIA</span>
                            </label>
                        </div>
                    </div>
                    <!-- Rating Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-6">Rating</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">★★★★★</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">★★★★☆</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">★★★☆☆</span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Products List -->
                <div class="w-full md:w-3/4 p-4">
                    <!-- Products grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Product 1 -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <img src="assets/images/products/5.jpg" alt="Product 1"
                                class="w-full object-cover mb-4 rounded-lg">
                            <a href="#" class="text-lg font-semibold mb-2">Gaming Laptop RTX 4060</a>
                            <p class=" my-2">Laptop</p>
                            <div class="flex items-center mb-4">
                                <span class="text-lg font-bold text-primary">$999.99</span>
                                <span class="text-sm line-through ml-2">$1199.99</span>
                            </div>
                            <button
                                class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                                to Cart</button>
                        </div>
                        <!-- Product 2 -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <img src="assets/images/products/6.jpg" alt="Product 2"
                                class="w-full object-cover mb-4 rounded-lg">
                            <a href="#" class="text-lg font-semibold mb-2">NVIDIA RTX 4070 GPU</a>
                            <p class=" my-2">PC Part</p>
                            <div class="flex items-center mb-4">
                                <span class="text-lg font-bold text-gray-900">$599.99</span>
                            </div>
                            <button
                                class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                                to Cart</button>
                        </div>
                        <!-- Product 3 -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <img src="assets/images/products/7.jpg" alt="Product 3"
                                class="w-full object-cover mb-4 rounded-lg">
                            <a href="#" class="text-lg font-semibold mb-2">Intel i7 13700K CPU</a>
                            <p class="my-2">PC Part</p>
                            <div class="flex items-center mb-4">
                                <span class="text-lg font-bold text-gray-900">$389.99</span>
                                <span class="text-sm line-through  ml-2">$429.99</span>
                            </div>
                            <button
                                class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                                to Cart</button>
                        </div>
                        <!-- Product 4 -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <img src="assets/images/products/8.jpg" alt="Product 4"
                                class="w-full object-cover mb-4 rounded-lg">
                            <a href="#" class="text-lg font-semibold mb-2">Gaming Monitor 27" 4K</a>
                            <p class="my-2">Accessories</p>
                            <div class="flex items-center mb-4">
                                <span class="text-lg font-bold text-primary">$349.99</span>
                                <span class="text-sm line-through ml-2">$429.99</span>
                            </div>
                            <button
                                class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                                to Cart</button>
                        </div>
                        <!-- Product 5 -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <img src="assets/images/products/4.jpg" alt="Product 4"
                                class="w-full object-cover mb-4 rounded-lg">
                            <a href="#" class="text-lg font-semibold">RGB Mechanical Keyboard</a>
                            <p class="my-2">Accessories</p>
                            <div class="flex items-center mb-4">
                                <span class="text-lg font-bold text-primary">$89.99</span>
                                <span class="text-sm line-through ml-2">$119.99</span>
                            </div>
                            <button
                                class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                                to Cart</button>
                        </div>
                        <!-- Product 6 -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <img src="assets/images/products/3.jpg" alt="Product 3"
                                class="w-full object-cover mb-4 rounded-lg">
                            <a href="#" class="text-lg font-semibold mb-2">Wireless Mouse Pro</a>
                            <p class=" my-2">Accessories</p>
                            <div class="flex items-center mb-4">
                                <span class="text-lg font-bold text-gray-900">$15.99</span>
                                <span class="text-sm line-through  ml-2">$19.99</span>
                            </div>
                            <button
                                class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add
                                to Cart</button>
                        </div>
                    </div>
                    <!-- Pagination -->
                    <div class="flex justify-center mt-8">
                        <nav aria-label="Page navigation">
                            <ul class="inline-flex space-x-2">
                                <li>
                                    <a href="#"
                                        class="bg-primary text-white w-10 h-10 flex items-center justify-center rounded-full">1</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-primary hover:text-white">2</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-primary hover:text-white">3</a>
                                </li>
                                <li>
                                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop category description -->
    <section id="shop-category-description" class="py-8">
        <div class="container mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-4">Laptop & PC Part Collection</h2>
                <p class="mb-4">
                    Discover our full lineup of high-performance laptops and premium PC components designed for gaming,
                    productivity, and everyday use. From ultrabooks to powerful workstations, our collection is built to
                    match your workflow and performance needs.
                </p>
                <p>
                    Explore carefully selected CPUs, GPUs, motherboards, and accessories from trusted brands to create a
                    reliable and efficient setup. Shop now and upgrade your tech with hardware that delivers speed,
                    stability, and modern performance.
                </p>
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
                        <li><a href="/shop.html" class="hover:text-primary">Shop</a></li>
                        <li><a href="/single-product-page.html" class="hover:text-primary">Laptop</a></li>
                        <li><a href="/shop.html" class="hover:text-primary">PC Part</a></li>
                        <li><a href="/single-product-page.html" class="hover:text-primary">Accessories</a></li>
                        <li><a href="/single-product-page.html" class="hover:text-primary">Peripherals</a></li>
                    </ul>
                </div>
                <!-- Menu 2 -->
                <div class="w-full sm:w-1/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Pages</h3>
                    <ul>
                        <li><a href="/shop.html" class="hover:text-primary">Shop</a></li>
                        <li><a href="/single-product-page.html" class="hover:text-primary">Product</a></li>
                        <li><a href="/checkout.html" class="hover:text-primary">Checkout</a></li>
                        <li><a href="/404.html" class="hover:text-primary">404</a></li>
                    </ul>
                </div>
                <!-- Menu 3 -->
                <div class="w-full sm:w-1/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Account</h3>
                    <ul>
                        <li><a href="/cart.html" class="hover:text-primary">Cart</a></li>
                        <li><a href="/register.html" class="hover:text-primary">Registration</a></li>
                        <li><a href="/register.html" class="hover:text-primary">Login</a></li>
                    </ul>
                </div>
                <!-- Social Media -->
                <div class="w-full sm:w-1/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                    <ul>
                        <li class="flex items-center mb-2">
                            <img src="/assets/images/social_icons/facebook.svg" alt="Facebook"
                                class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-primary">Facebook</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="/assets/images/social_icons/twitter.svg" alt="Twitter"
                                class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-primary">Twitter</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="/assets/images/social_icons/instagram.svg" alt="Instagram"
                                class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-primary">Instagram</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="/assets/images/social_icons/pinterest.svg" alt="Instagram"
                                class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-primary">Pinterest</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="/assets/images/social_icons/youtube.svg" alt="Instagram"
                                class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-primary">YouTube</a>
                        </li>
                    </ul>
                </div>
                <!-- Contact Information -->
                <div class="w-full sm:w-2/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <p><img src="assets/images/template-logo.png" alt="Logo" class="h-[60px] mb-4"></p>
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
                    <p class="text-sm mt-4">Your shop's description goes here. This is a brief introduction to your shop
                        and what you offer.</p>
                </div>
                <!-- Payment Icons -->
                <div class="w-full lg:w-1/4 text-center lg:text-right">
                    <img src="/assets/images/social_icons/paypal.svg" alt="PayPal" class="inline-block h-8 mr-2">
                    <img src="/assets/images/social_icons/stripe.svg" alt="Stripe" class="inline-block h-8 mr-2">
                    <img src="/assets/images/social_icons/visa.svg" alt="Visa" class="inline-block h-8">
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="assets/js/script.js"></script>
</body>
</html>