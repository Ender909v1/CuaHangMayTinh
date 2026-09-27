<div class="mb-6">
    <h2 class="text-xl font-bold">Inventory Management</h2>
    <p class="mt-1 text-sm text-gray-500">Change any product's stock number (a note is required) and open its history to see every change.</p>
</div>
@if ($inventory->isEmpty())
    <p class="text-gray-500">No products to track.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">SKU</th>
                    <th class="px-4 py-3">Stock left</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody x-data="{ editingId: null, historyId: null }">
                @foreach ($inventory as $product)
                    <tr class="border-b align-top">
                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-3">{{ $product->sku }}</td>
                        <td class="px-4 py-3 font-bold">{{ $product->stock_qty }} left</td>
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
                            @include('admin.partials.inventory-actions', ['product' => $product])
                        </td>
                    </tr>
                    @include('admin.partials.inventory-stock-panel', ['product' => $product, 'colspan' => 5])
                @endforeach
            </tbody>
        </table>
    </div>
@endif
