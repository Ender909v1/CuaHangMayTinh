@php
    // Shared products filter bar: dashboard tab uses p_* param names + hidden
    // tab field; the full /admin/products page uses plain param names.
    $isDashboardProductsTab = ($productsFilterContext ?? 'page') === 'dashboard';
    $productsParamPrefix = $isDashboardProductsTab ? 'p_' : '';
    $productsFormAction = $isDashboardProductsTab ? route('admin.dashboard') : route('admin.products.index');
    $productsResetUrl = $isDashboardProductsTab ? route('admin.dashboard', ['tab' => 'products']) : route('admin.products.index');
    $productFilterCategories = $productFilterOptions['categories'] ?? collect();
    $productFilterBrands = $productFilterOptions['brands'] ?? collect();
@endphp
<form method="GET" action="{{ $productsFormAction }}" class="mb-6 grid gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 md:grid-cols-2 xl:grid-cols-6">
    @if ($isDashboardProductsTab)
        <input type="hidden" name="tab" value="products">
    @endif
    <div class="xl:col-span-2">
        <label for="{{ $productsParamPrefix }}product-search" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search</label>
        <input id="{{ $productsParamPrefix }}product-search" type="text" name="{{ $productsParamPrefix }}search" value="{{ $productFilters['search'] ?? '' }}" placeholder="Name or SKU…" class="w-full rounded border px-3 py-2 text-sm" oninput="clearTimeout(window._adminFilterT);window._adminFilterT=setTimeout(()=>this.form.submit(),600)">
    </div>
    <div>
        <label for="{{ $productsParamPrefix }}product-category" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Category</label>
        <select id="{{ $productsParamPrefix }}product-category" name="{{ $productsParamPrefix }}category" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">All categories</option>
            @foreach ($productFilterCategories as $categoryOption)
                <option value="{{ $categoryOption->id }}" {{ ($productFilters['category'] ?? '') === (string) $categoryOption->id ? 'selected' : '' }}>{{ $categoryOption->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="{{ $productsParamPrefix }}product-brand" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Brand</label>
        <select id="{{ $productsParamPrefix }}product-brand" name="{{ $productsParamPrefix }}brand" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">All brands</option>
            @foreach ($productFilterBrands as $brandOption)
                <option value="{{ $brandOption->id }}" {{ ($productFilters['brand'] ?? '') === (string) $brandOption->id ? 'selected' : '' }}>{{ $brandOption->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="{{ $productsParamPrefix }}product-status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Status</label>
        <select id="{{ $productsParamPrefix }}product-status" name="{{ $productsParamPrefix }}status" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">Active + inactive</option>
            <option value="active" {{ ($productFilters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ ($productFilters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div>
        <label for="{{ $productsParamPrefix }}product-stock" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Stock</label>
        <select id="{{ $productsParamPrefix }}product-stock" name="{{ $productsParamPrefix }}stock" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">All stock levels</option>
            <option value="in" {{ ($productFilters['stock'] ?? '') === 'in' ? 'selected' : '' }}>In stock (&gt; 5)</option>
            <option value="low" {{ ($productFilters['stock'] ?? '') === 'low' ? 'selected' : '' }}>Low stock (1–5)</option>
            <option value="out" {{ ($productFilters['stock'] ?? '') === 'out' ? 'selected' : '' }}>Out of stock (0)</option>
        </select>
    </div>
    <div class="flex items-end gap-2 md:col-span-2 xl:col-span-6">
        <a href="{{ $productsResetUrl }}" class="rounded border px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Reset</a>
    </div>
</form>
