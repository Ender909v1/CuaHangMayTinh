<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold">Products Management</h2>
        <p class="mt-1 text-sm text-gray-500">Search by name or SKU. Filter by category, brand, status, or stock.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.products.index') }}" class="rounded bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-700">Full Products Page</a>
        <a href="{{ route('admin.products.create') }}" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Add Product</a>
    </div>
</div>
@include('admin.partials.products-filter', ['productsFilterContext' => 'dashboard'])
@if ($products->isEmpty())
    <p class="text-gray-500">No products match these filters.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="border-b">
                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-3">{{ number_format($product->effectivePrice(), 0, ',', '.') }} ₫</td>
                        <td class="px-4 py-3">{{ $product->stock_qty }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.products.show', $product) }}" class="rounded bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-500">Detail</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="rounded bg-yellow-500 px-3 py-1.5 text-white hover:bg-yellow-400">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">@csrf @method('DELETE')<button type="submit" class="rounded bg-red-600 px-3 py-1.5 text-white hover:bg-red-500">Delete</button></form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($products->hasPages())
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
@endif
