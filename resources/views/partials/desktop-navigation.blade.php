<nav class="hidden lg:flex md:flex-grow justify-center">
    <ul class="flex justify-center space-x-4 text-white">
        <li><a href="{{ route('cuahangmaytinh') }}" class="hover:text-secondary font-semibold">Home</a></li>
        <li class="relative group" x-data="{ open: false }">
            <a href="{{ route('shop') }}" @mouseover="open = true" @mouseleave="open = false" class="hover:text-secondary font-semibold flex items-center">
                PC Part
                <i :class="open ? 'fas fa-chevron-up ml-1 text-xs' : 'fas fa-chevron-down ml-1 text-xs'"></i>
            </a>
            <ul x-show="open" @mouseover="open = true" @mouseleave="open = false" class="absolute left-0 bg-white text-black space-y-2 mt-1 p-2 rounded shadow-lg" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">CPU</a></li>
                <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">GPU</a></li>
                <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Motherboard</a></li>
            </ul>
        </li>
        <li class="relative group" x-data="{ open: false }">
            <a href="{{ route('shop') }}" @mouseover="open = true" @mouseleave="open = false" class="hover:text-secondary font-semibold flex items-center">
                Laptop
                <i :class="open ? 'fas fa-chevron-up ml-1 text-xs' : 'fas fa-chevron-down ml-1 text-xs'"></i>
            </a>
            <ul x-show="open" @mouseover="open = true" @mouseleave="open = false" class="absolute left-0 bg-white text-black space-y-2 mt-1 p-2 rounded shadow-lg" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Gaming</a></li>
                <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Business</a></li>
                <li><a href="{{ route('shop') }}" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Student</a></li>
            </ul>
        </li>
        <li><a href="{{ route('shop') }}" class="hover:text-secondary font-semibold">Shop</a></li>
        <li><a href="{{ route('product') }}" class="hover:text-secondary font-semibold">Product</a></li>
        <li><a href="{{ route('cuahangmaytinh') }}#contact" class="hover:text-secondary font-semibold">Contact</a></li>
        <li><a href="{{ route('checkout') }}" class="hover:text-secondary font-semibold">Checkout</a></li>
    </ul>
</nav>
