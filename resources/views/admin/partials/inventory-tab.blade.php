<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold">Inventory Management</h2>
        <p class="mt-1 text-sm text-gray-500">Search by name or SKU. Filter by stock status.</p>
    </div>
    <a href="{{ route('admin.inventory.index') }}" class="rounded bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-700">Full Inventory Page</a>
</div>
<p class="mb-6 text-sm text-gray-500">Change any product's stock number (a note is required) and open its history to see every change.</p>
<form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6 grid gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 md:grid-cols-2">
    <input type="hidden" name="tab" value="inventory">
    <div>
        <label for="dashboard-inventory-search" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search</label>
        <input id="dashboard-inventory-search" type="text" name="i_search" value="{{ $inventoryFilters['search'] ?? '' }}" placeholder="Product name or SKU…" class="w-full rounded border px-3 py-2 text-sm" oninput="clearTimeout(window._adminFilterT);window._adminFilterT=setTimeout(()=>this.form.submit(),600)">
    </div>
    <div>
        <label for="dashboard-inventory-stock" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Stock status</label>
        <select id="dashboard-inventory-stock" name="i_stock" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">All stock levels</option>
            <option value="in" {{ ($inventoryFilters['stock'] ?? '') === 'in' ? 'selected' : '' }}>In stock (&gt; 5)</option>
            <option value="low" {{ ($inventoryFilters['stock'] ?? '') === 'low' ? 'selected' : '' }}>Low stock (1–5)</option>
            <option value="out" {{ ($inventoryFilters['stock'] ?? '') === 'out' ? 'selected' : '' }}>Out of stock (0)</option>
        </select>
    </div>
    <div class="flex gap-2 md:col-span-2">
        <a href="{{ route('admin.dashboard', ['tab' => 'inventory']) }}" class="rounded border px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Reset</a>
    </div>
</form>
@if ($inventory->isEmpty())
    <p class="text-gray-500">No products match these filters.</p>
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
