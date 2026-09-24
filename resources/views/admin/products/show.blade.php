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
            <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
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

        <main class="mx-auto max-w-4xl px-4 py-10">
            <div class="rounded-xl bg-white p-8 shadow">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-bold">{{ $product->name }}</h2>
                    <a href="{{ route('admin.products.edit', $product) }}" class="rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-400">Edit</a>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">SKU</p>
                        <p class="font-semibold">{{ $product->sku }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Type</p>
                        <p class="font-semibold">{{ $product->type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="font-semibold">${{ number_format($product->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Discount</p>
                        <p class="font-semibold">${{ $product->discount_price ? number_format($product->discount_price, 2) : '0.00' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Stock</p>
                        <p class="font-semibold">{{ $product->stock_qty }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Category</p>
                        <p class="font-semibold">{{ $product->category?->name ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Description</p>
                        <p class="mt-1 whitespace-pre-line">{{ $product->description ?: 'No description available.' }}</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>


