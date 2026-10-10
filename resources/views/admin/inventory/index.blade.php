<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        @include('admin.partials.navigation', ['pageTitle' => 'Inventory', 'activeTab' => 'inventory'])
        <main class="mx-auto max-w-7xl px-4 pt-4 pb-10">
            <div class="rounded-xl bg-white p-6 shadow">
                <div class="mb-6">
                    <h2 class="text-xl font-bold">Stock levels (like products, plus quantity left)</h2>
                    <p class="mt-1 text-sm text-gray-500">Search by name or SKU. Filter by stock status.</p>
                </div>
                <form method="GET" action="{{ route('admin.inventory.index') }}" class="mb-6 grid gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 md:grid-cols-2">
                    <div>
                        <label for="inventory-search" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search</label>
                        <input id="inventory-search" type="text" name="search" value="{{ $inventoryFilters['search'] ?? '' }}" placeholder="Product name or SKU…" class="w-full rounded border px-3 py-2 text-sm" oninput="clearTimeout(window._adminFilterT);window._adminFilterT=setTimeout(()=>this.form.submit(),600)">
                    </div>
                    <div>
                        <label for="inventory-stock" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Stock status</label>
                        <select id="inventory-stock" name="stock" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
                            <option value="">All stock levels</option>
                            <option value="in" {{ ($inventoryFilters['stock'] ?? '') === 'in' ? 'selected' : '' }}>In stock (&gt; 5)</option>
                            <option value="low" {{ ($inventoryFilters['stock'] ?? '') === 'low' ? 'selected' : '' }}>Low stock (1–5)</option>
                            <option value="out" {{ ($inventoryFilters['stock'] ?? '') === 'out' ? 'selected' : '' }}>Out of stock (0)</option>
                        </select>
                    </div>
                    <div class="flex gap-2 md:col-span-2">
                        <a href="{{ route('admin.inventory.index') }}" class="rounded border px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Reset</a>
                    </div>
                </form>
                @if ($inventory->isEmpty())
                    <p class="text-gray-500">No products match these filters.</p>
                @else
                @if (session('success'))
                    <div class="mb-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">SKU</th>
                                <th class="px-4 py-3">Price</th>
                                <th class="px-4 py-3">Stock left</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ editingId: null, historyId: null }">
                            @foreach ($inventory as $product)
                                <tr class="border-b align-top {{ $product->stock_qty <= 0 ? 'bg-red-50' : ($product->stock_qty <= 5 ? 'bg-yellow-50' : '') }}">
                                    <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                                    <td class="px-4 py-3">{{ $product->sku }}</td>
                                    <td class="px-4 py-3">{{ number_format($product->effectivePrice(), 0, ',', '.') }} ₫</td>
                                    <td class="px-4 py-3 font-bold {{ $product->stock_qty <= 0 ? 'text-red-600' : '' }}">{{ $product->stock_qty }} left</td>
                                    <td class="px-4 py-3">
                                        @if ($product->stock_qty <= 0)
                                            <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">Out of stock</span>
                                        @elseif ($product->stock_qty <= 5)
                                            <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700">Low stock</span>
                                        @else
                                            <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">In stock</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.products.show', $product) }}" class="rounded bg-gray-700 px-3 py-2 text-white hover:bg-gray-600">Detail</a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="rounded bg-purple-600 px-3 py-2 text-white hover:bg-purple-500">Edit product</a>
                                            @include('admin.partials.inventory-actions', ['product' => $product])
                                        </div>
                                    </td>
                                </tr>
                                @include('admin.partials.inventory-stock-panel', ['product' => $product, 'colspan' => 6])
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $inventory->links() }}</div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
