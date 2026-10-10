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
        @include('admin.partials.navigation', ['pageTitle' => 'Edit Product', 'activeTab' => 'products'])

        <main class="mx-auto max-w-4xl px-4 pt-4 pb-10">
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
                            <div class="flex items-center gap-3">
                                <span class="rounded-lg border border-gray-200 bg-gray-100 px-3 py-2 font-semibold text-gray-700">{{ $product->stock_qty }}</span>
                                <a href="{{ route('admin.inventory.index') }}" class="text-sm font-semibold text-red-600 hover:underline">Change in Inventory</a>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Stock is read-only here. Use Edit in the Inventory tab so the change is logged with a note.</p>
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
                        <a href="{{ route('admin.products.index') }}" class="rounded border border-gray-300 px-5 py-2 font-semibold hover:bg-gray-100">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
