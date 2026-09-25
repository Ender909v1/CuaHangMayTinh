<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        <nav class="bg-gray-900 text-white">
            <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold">Edit Product</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard', ['tab' => 'products']) }}" class="hover:text-red-400">Back</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-500">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-4xl px-4 py-10">
            <div class="rounded-xl bg-white p-8 shadow">
                <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
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
                        <div>
                            <label class="mb-1 block text-sm font-medium">Brand</label>
                            <select name="brand_id" class="w-full rounded-lg border px-3 py-2" required>
                                <option value="">Select brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium">Category</label>
                            <select name="category_id" class="w-full rounded-lg border px-3 py-2" required>
                                <option value="">Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border px-3 py-2">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Product Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full rounded-lg border px-3 py-2">
                        @if ($product->images->isNotEmpty())
                            <p class="mt-2 text-xs text-gray-500">Current image: {{ $product->images->first()->resolvedUrl() }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="h-4 w-4">
                        <label>Active product</label>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="rounded bg-red-600 px-5 py-2 font-semibold text-white hover:bg-red-500">Save</button>
                        <a href="{{ route('admin.dashboard', ['tab' => 'products']) }}" class="rounded border border-gray-300 px-5 py-2 font-semibold hover:bg-gray-100">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>

