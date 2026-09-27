@php
    $imageUrl = $product->images->first()?->resolvedUrl() ?? asset('tailstore4-main/logo/logo.jpg');
    $displayPrice = $product->effectivePrice();
    $oldPrice = $product->originalPrice();
    $outOfStock = (int) $product->stock_qty <= 0;
@endphp
<div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
    <div class="bg-white p-3 rounded-lg shadow-lg {{ $outOfStock ? 'opacity-60' : '' }}" data-product-id="{{ $product->id }}">
        <div class="relative">
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                class="w-full object-cover mb-4 rounded-lg cursor-pointer {{ $outOfStock ? 'grayscale' : '' }}"
                @click="showModal = true; modalTitle = '{{ addslashes($product->name) }}'; modalCategory = '{{ addslashes($product->category->name ?? 'Product') }}'; modalPrice = '${{ number_format((float) $displayPrice, 2) }}'; modalOldPrice = '{{ $oldPrice ? '$'.number_format((float) $oldPrice, 2) : '' }}'; modalImg = '{{ $imageUrl }}'">
            @if ($outOfStock)
                <span class="absolute left-2 top-2 rounded-full bg-gray-900 px-3 py-1 text-xs font-bold uppercase text-white">Out of stock</span>
            @endif
        </div>
        <a href="{{ route('product', $product) }}" class="text-lg font-semibold mb-2 block">{{ $product->name }}</a>
        <p class="my-2 text-gray-500">{{ $product->category->name ?? 'Product' }}</p>
        <div class="flex items-center mb-4">
            <span class="text-lg font-bold text-primary">${{ number_format((float) $displayPrice, 2) }}</span>
            @if ($oldPrice)
                <span class="text-sm line-through ml-2 text-gray-400">${{ number_format((float) $oldPrice, 2) }}</span>
            @endif
        </div>
        @if ($outOfStock)
            <a href="{{ route('product', $product) }}" class="block border border-gray-400 text-center text-gray-500 font-semibold py-2 px-4 rounded-full w-full">View Details</a>
        @else
            <button class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add to Cart</button>
        @endif
    </div>
</div>
