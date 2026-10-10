<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        @include('admin.partials.navigation', ['pageTitle' => 'Manage Categories', 'activeTab' => 'categories'])

        <main class="mx-auto max-w-7xl px-4 pt-4 pb-10">
            @if (session('success'))
                <div class="mb-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-xl bg-white p-6 shadow">
                <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold">Category list</h2>
                        <p class="mt-1 text-sm text-gray-500">Search by name. Filter by parent.</p>
                    </div>
                    <a href="{{ route('admin.categories.create') }}" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Add Category</a>
                </div>
                @php($categoryParentOptions = \App\Models\Category::orderBy('name')->get(['id', 'name']))
                <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-6 grid gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 md:grid-cols-2">
                    <div>
                        <label for="category-search" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search</label>
                        <input id="category-search" type="text" name="search" value="{{ $categoryFilters['search'] ?? '' }}" placeholder="Category name…" class="w-full rounded border px-3 py-2 text-sm" oninput="clearTimeout(window._adminFilterT);window._adminFilterT=setTimeout(()=>this.form.submit(),600)">
                    </div>
                    <div>
                        <label for="category-parent" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Parent</label>
                        <select id="category-parent" name="parent" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
                            <option value="">All parents</option>
                            <option value="main" {{ ($categoryFilters['parent'] ?? '') === 'main' ? 'selected' : '' }}>Main categories</option>
                            @foreach ($categoryParentOptions as $parentOption)
                                <option value="{{ $parentOption->id }}" {{ ($categoryFilters['parent'] ?? '') === (string) $parentOption->id ? 'selected' : '' }}>{{ $parentOption->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2 md:col-span-2">
                        <a href="{{ route('admin.categories.index') }}" class="rounded border px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Reset</a>
                    </div>
                </form>
                @if ($categories->isEmpty())
                    <p class="text-gray-500">No categories match these filters.</p>
                @else
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($categories as $category)
                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-lg font-bold text-gray-900">{{ $category->name }}</p>
                                <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">{{ $category->parent?->name ?? 'Main' }}</span>
                            </div>
                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="rounded bg-yellow-500 px-3 py-2 text-sm font-semibold text-white hover:bg-yellow-400">Edit</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $categories->links() }}
                </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
