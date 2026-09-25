<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<<<<<<< HEAD
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        <nav class="bg-gray-900 text-white">
            <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold">Edit Product</h1>
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
                <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium">Name</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full rounded-lg border px-3 py-2" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full rounded-lg border px-3 py-2" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium">Price</label>
                            <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" class="w-full rounded-lg border px-3 py-2" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium">Discount Price</label>
                            <input type="number" name="discount_price" step="0.01" value="{{ old('discount_price', $product->discount_price) }}" class="w-full rounded-lg border px-3 py-2">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium">Stock Quantity</label>
                            <input type="number" name="stock_qty" value="{{ old('stock_qty', $product->stock_qty) }}" class="w-full rounded-lg border px-3 py-2" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium">Type</label>
                            <input type="text" name="type" value="{{ old('type', $product->type) }}" class="w-full rounded-lg border px-3 py-2" required>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border px-3 py-2">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="h-4 w-4">
                        <label>Active product</label>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="rounded bg-red-600 px-5 py-2 font-semibold text-white hover:bg-red-500">Save</button>
                        <a href="{{ route('admin.products.index') }}" class="rounded border border-gray-300 px-5 py-2 font-semibold hover:bg-gray-100">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>


=======
<body class="bg-slate-100 text-slate-800">
    <nav class="bg-slate-900 text-white">
        <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold">Edit Product</h1>
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

    <main class="mx-auto max-w-3xl px-4 py-10">
        <div class="rounded-xl bg-white p-8 shadow">
            <form method="POST" action="{{ route('admin.products.update', $product) }}">
                @csrf
                @method('PUT')

                <div class="grid gap-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium">Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full rounded border border-slate-300 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full rounded border border-slate-300 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">Price</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="w-full rounded border border-slate-300 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">Stock</label>
                        <input type="number" name="stock_qty" value="{{ old('stock_qty', $product->stock_qty) }}" class="w-full rounded border border-slate-300 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">Description</label>
                        <textarea name="description" rows="5" class="w-full rounded border border-slate-300 px-3 py-2">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300">
                        <label class="text-sm font-medium">Active</label>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="rounded bg-red-600 px-5 py-3 font-semibold text-white hover:bg-red-500">Save Changes</button>
                        <a href="{{ route('admin.products.index') }}" class="rounded border border-slate-300 px-5 py-3 font-semibold text-slate-700">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
>>>>>>> 07e3ef880b20a27898947149096f7c1a02933c6f
