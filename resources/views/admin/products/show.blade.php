<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">
    <nav class="bg-slate-900 text-white">
        <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold">Product Detail</h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.products.index') }}" class="hover:text-red-400">Products</a>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-red-400">Dashboard</a>
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
                <a href="{{ route('admin.products.edit', $product) }}" class="rounded bg-yellow-500 px-4 py-2 font-semibold text-white hover:bg-yellow-400">Edit</a>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <p class="text-sm uppercase tracking-wide text-slate-500">SKU</p>
                    <p class="mt-2 font-medium">{{ $product->sku }}</p>
                </div>
                <div>
                    <p class="text-sm uppercase tracking-wide text-slate-500">Price</p>
                    <p class="mt-2 font-medium">${{ number_format($product->price, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm uppercase tracking-wide text-slate-500">Stock</p>
                    <p class="mt-2 font-medium">{{ $product->stock_qty }}</p>
                </div>
                <div>
                    <p class="text-sm uppercase tracking-wide text-slate-500">Status</p>
                    <p class="mt-2 font-medium">{{ $product->is_active ? 'Active' : 'Inactive' }}</p>
                </div>
            </div>

            <div class="mt-8">
                <p class="text-sm uppercase tracking-wide text-slate-500">Description</p>
                <p class="mt-2 leading-relaxed text-slate-700">{{ $product->description }}</p>
            </div>
        </div>
    </main>
</body>
</html>
