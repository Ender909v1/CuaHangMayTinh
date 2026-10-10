<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold">Categories Management</h2>
        <p class="mt-1 text-sm text-gray-500">Search by name. Filter by parent.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.categories.index') }}" class="rounded bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-700">Full Categories Page</a>
        <a href="{{ route('admin.categories.create') }}" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Add Category</a>
    </div>
</div>
<form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6 grid gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 md:grid-cols-2">
    <input type="hidden" name="tab" value="categories">
    <div>
        <label for="dashboard-category-search" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search</label>
        <input id="dashboard-category-search" type="text" name="c_search" value="{{ $categoryFilters['search'] ?? '' }}" placeholder="Category name…" class="w-full rounded border px-3 py-2 text-sm" oninput="clearTimeout(window._adminFilterT);window._adminFilterT=setTimeout(()=>this.form.submit(),600)">
    </div>
    <div>
        <label for="dashboard-category-parent" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Parent</label>
        <select id="dashboard-category-parent" name="c_parent" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">All parents</option>
            <option value="main" {{ ($categoryFilters['parent'] ?? '') === 'main' ? 'selected' : '' }}>Main categories</option>
            @foreach ($categoryParents as $parentOption)
                <option value="{{ $parentOption->id }}" {{ ($categoryFilters['parent'] ?? '') === (string) $parentOption->id ? 'selected' : '' }}>{{ $parentOption->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex gap-2 md:col-span-2">
        <a href="{{ route('admin.dashboard', ['tab' => 'categories']) }}" class="rounded border px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Reset</a>
    </div>
</form>
@if ($categories->isEmpty())
    <p class="text-gray-500">No categories match these filters.</p>
@else
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($categories as $category)
            <div class="rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $category->name }}</p>
                        <p class="text-sm text-gray-500">{{ $category->parent?->name ? 'Parent: '.$category->parent->name : 'Main category' }}</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="rounded bg-yellow-500 px-3 py-2 text-sm font-semibold text-white hover:bg-yellow-400">Edit</a>
                    <a href="{{ route('admin.categories.index') }}" class="rounded bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500">View</a>
                </div>
            </div>
        @endforeach
    </div>
@endif
