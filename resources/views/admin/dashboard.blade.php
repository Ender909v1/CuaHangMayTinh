<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">
    <nav class="bg-slate-900 text-white">
        <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold">Admin Dashboard</h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.products.index') }}" class="hover:text-red-400">Products</a>
                <a href="{{ route('admin.history') }}" class="hover:text-red-400">History</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-500">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-10">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl bg-white p-6 shadow">
                <p class="text-sm text-slate-500">Total Products</p>
                <p class="mt-2 text-3xl font-bold">{{ \App\Models\Product::count() }}</p>
            </div>
            <div class="rounded-xl bg-white p-6 shadow">
                <p class="text-sm text-slate-500">Active Products</p>
                <p class="mt-2 text-3xl font-bold">{{ \App\Models\Product::where('is_active', true)->count() }}</p>
            </div>
            <div class="rounded-xl bg-white p-6 shadow">
                <p class="text-sm text-slate-500">Low Stock</p>
                <p class="mt-2 text-3xl font-bold">{{ \App\Models\Product::where('stock_qty', '<=', 5)->count() }}</p>
            </div>
        </div>

        <div class="mt-10 rounded-xl bg-white p-6 shadow">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-bold">Recent history</h2>
                <a href="{{ route('admin.history') }}" class="text-sm font-semibold text-red-600">View all</a>
            </div>

            @php $history = \App\Models\ProductHistory::with(['user', 'product'])->latest()->take(8)->get(); @endphp
            @if ($history->isEmpty())
                <p class="text-slate-500">No product history yet.</p>
            @else
                <div class="space-y-3">
                    @foreach ($history as $entry)
                        <div class="flex items-center justify-between rounded-lg border border-slate-200 p-3">
                            <div>
                                <p class="font-semibold">{{ $entry->product ? $entry->product->name : 'Deleted product' }}</p>
                                <p class="text-sm text-slate-500">{{ $entry->details }}</p>
                            </div>
                            <div class="text-right text-sm text-slate-500">
                                <p>{{ $entry->action }}</p>
                                <p>{{ $entry->created_at ? $entry->created_at->format('d/m/Y H:i') : '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
</body>
</html>
