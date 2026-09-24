<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        <nav class="bg-gray-900 text-white">
            <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold">Manage Products</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-400">Dashboard</a>
                    <a href="{{ route('admin.history') }}" class="hover:text-red-400">History</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-500">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-4 py-10">
            @if (session('success'))
                <div class="mb-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-xl bg-white p-6 shadow">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-bold">Product list</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">SKU</th>
                                <th class="px-4 py-3">Price</th>
                                <th class="px-4 py-3">Stock</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                                    <td class="px-4 py-3">{{ $product->sku }}</td>
                                    <td class="px-4 py-3">${{ number_format($product->price, 2) }}</td>
                                    <td class="px-4 py-3">{{ $product->stock_qty }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }} px-2 py-1 text-xs font-semibold">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.products.show', $product) }}" class="rounded bg-blue-600 px-3 py-2 text-white hover:bg-blue-500">Detail</a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="rounded bg-yellow-500 px-3 py-2 text-white hover:bg-yellow-400">Edit</a>
                                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded bg-red-600 px-3 py-2 text-white hover:bg-red-500">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>


