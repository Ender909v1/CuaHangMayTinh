<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        <nav class="bg-gray-900 text-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
                <div>
                    <h1 class="text-xl font-bold">Product Detail</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.products.index') }}" class="hover:text-red-400">Back</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-500">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-4 py-10">
            @php
                $productImages = $product->images->sortByDesc('is_primary')->values();
                $primaryImage = $productImages->first();
                $displayImage = $primaryImage?->resolvedUrl() ?? asset('tailstore4-main/logo/logo.jpg');
            @endphp

            <div class="rounded-xl bg-white p-6 shadow md:p-8">
                <div class="mb-6 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <a href="{{ route('admin.products.index') }}" class="hover:text-red-500">Products</a>
                        <span class="mx-2">/</span>
                        <span>{{ $product->name }}</span>
                    </div>
                    <a href="{{ route('admin.products.edit', $product) }}" class="rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-400">Edit</a>
                </div>

                <div class="grid gap-8 lg:grid-cols-2">
                    <div class="space-y-4">
                        <div class="overflow-hidden rounded-2xl border bg-gray-100">
                            <img id="main-image" src="{{ $displayImage }}" alt="{{ $product->name }}" class="h-[420px] w-full object-cover md:h-[520px]">
                        </div>

                        @if ($productImages->isNotEmpty())
                            <div class="grid grid-cols-5 gap-3">
                                @foreach ($productImages as $image)
                                    @php
                                        $imageUrl = $image->resolvedUrl();
                                    @endphp
                                    <button type="button" class="image-thumb overflow-hidden rounded-lg border bg-gray-100 p-1 transition {{ $loop->first ? 'ring-2 ring-red-500' : 'ring-1 ring-gray-200' }}"
                                        data-image="{{ $imageUrl }}">
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-20 w-full rounded object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col justify-between">
                        <div class="space-y-6">
                            <div>
                                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-red-700">
                                    {{ $product->category?->name ?? 'General' }}
                                </span>
                                <h2 class="mt-4 text-3xl font-bold text-gray-900">{{ $product->name }}</h2>
                            </div>

                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <span class="text-yellow-400">★★★★★</span>
                                <span>({{ $product->reviews->count() }} reviews)</span>
                            </div>

                            <div class="border-y border-gray-200 py-5">
                                <div class="flex items-center gap-3">
                                    <p class="text-3xl font-bold text-red-600">
                                        ${{ number_format((float) $product->price, 2) }}
                                    </p>
                                    @if ($product->discount_price && $product->discount_price < $product->price)
                                        <span class="text-lg text-gray-400 line-through">
                                            ${{ number_format((float) $product->discount_price, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <p class="text-sm text-gray-500">SKU</p>
                                    <p class="mt-1 font-semibold">{{ $product->sku }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Type</p>
                                    <p class="mt-1 font-semibold">{{ $product->type }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Stock</p>
                                    <p class="mt-1 font-semibold">{{ $product->stock_qty }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Availability</p>
                                    <p class="mt-1 font-semibold">{{ $product->stock_qty > 0 ? 'In Stock' : 'Out of Stock' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 space-y-6">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Description</p>
                                <p class="mt-2 whitespace-pre-line text-gray-700">{{ $product->description ?: 'No description available.' }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Specifications</p>
                                @if ($product->specifications->isEmpty())
                                    <p class="mt-2 text-gray-400">No specifications recorded.</p>
                                @else
                                    <ul class="mt-3 divide-y divide-gray-200 rounded-lg border border-gray-200">
                                        @foreach ($product->specifications as $spec)
                                            <li class="flex items-center justify-between gap-4 px-4 py-3">
                                                <span class="text-gray-500">{{ $spec->spec_name }}</span>
                                                <span class="font-semibold text-gray-800">{{ $spec->spec_value }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mainImage = document.getElementById('main-image');

            document.querySelectorAll('.image-thumb').forEach((button) => {
                button.addEventListener('click', function () {
                    if (!mainImage) {
                        return;
                    }

                    const imageUrl = this.dataset.image;
                    if (imageUrl) {
                        mainImage.src = imageUrl;
                    }

                    document.querySelectorAll('.image-thumb').forEach((thumb) => {
                        thumb.classList.remove('ring-2', 'ring-red-500');
                        thumb.classList.add('ring-1', 'ring-gray-200');
                    });

                    this.classList.remove('ring-1', 'ring-gray-200');
                    this.classList.add('ring-2', 'ring-red-500');
                });
            });
        });
    </script>
</body>
</html>
