<ul class="w-full">
    <li><a href="{{ route('cuahangmaytinh') }}" class="hover:text-secondary font-bold block py-2">Home</a></li>
    <li class="relative group" x-data="{ open: false }">
        <a @click="open = !open; $event.preventDefault()" class="hover:text-secondary font-bold block py-2 flex justify-center items-center cursor-pointer">
            <span>PC Part</span>
            <span @click.stop="open = !open"><i :class="open ? 'fas fa-chevron-up text-xs ml-2' : 'fas fa-chevron-down text-xs ml-2'"></i></span>
        </a>
        <ul class="mobile-dropdown-menu" x-show="open" x-transition class="pl-4 space-y-2">
            <li><a href="{{ route('shop') }}" class="hover:text-secondary font-bold block py-2">Shop PC Part</a></li>
            <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">CPU</a></li>
            <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">GPU</a></li>
            <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Motherboard</a></li>
        </ul>
    </li>
    <li class="relative group" x-data="{ open: false }">
        <a @click="open = !open; $event.preventDefault()" class="hover:text-secondary font-bold py-2 flex justify-center items-center cursor-pointer">
            <span>Laptop</span>
            <span @click.stop="open = !open"><i :class="open ? 'fas fa-chevron-up text-xs ml-2' : 'fas fa-chevron-down text-xs ml-2'"></i></span>
        </a>
        <ul class="mobile-dropdown-menu" x-show="open" x-transition class="space-y-2">
            <li><a href="{{ route('shop') }}" class="hover:text-secondary font-bold block py-2">Shop Laptop</a></li>
            <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Gaming</a></li>
            <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Business</a></li>
            <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Student</a></li>
        </ul>
    </li>
    <li><a href="{{ route('shop') }}" class="hover:text-secondary font-bold block py-2">Shop</a></li>
    <li><a href="{{ route('product') }}" class="hover:text-secondary font-bold block py-2">Product</a></li>
    <li><a href="{{ route('cuahangmaytinh') }}#contact" class="hover:text-secondary font-bold block py-2">Contact</a></li>
    <li><a href="{{ route('checkout') }}" class="hover:text-secondary font-bold block py-2">Checkout</a></li>
</ul>
