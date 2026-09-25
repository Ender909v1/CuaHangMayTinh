<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        <nav class="bg-gray-900 text-white">
            <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold">Admin</h1>
                </div>
                <div class="flex items-center gap-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-500">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-4 py-10" x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || 'dashboard' }">
            <!-- Tab Navigation -->
            <div class="mb-6 border-b border-gray-300">
                <div class="flex flex-wrap gap-2">
                    <button @click="activeTab = 'dashboard'" :class="activeTab === 'dashboard' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-t-lg font-semibold">Dashboard</button>
                    <button @click="activeTab = 'products'" :class="activeTab === 'products' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-t-lg font-semibold">Products</button>
                    <button @click="activeTab = 'orders'" :class="activeTab === 'orders' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-t-lg font-semibold">Orders</button>
                    <button @click="activeTab = 'users'" :class="activeTab === 'users' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-t-lg font-semibold">Users</button>
                    <button @click="activeTab = 'categories'" :class="activeTab === 'categories' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-t-lg font-semibold">Categories</button>
                    <button @click="activeTab = 'brands'" :class="activeTab === 'brands' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-t-lg font-semibold">Brands</button>
                    <button @click="activeTab = 'inventory'" :class="activeTab === 'inventory' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-t-lg font-semibold">Inventory</button>
                    <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-t-lg font-semibold">Reviews</button>
                </div>
            </div>

            <!-- Tab Content -->
            <div>
                <!-- Dashboard Tab -->
                <div x-show="activeTab === 'dashboard'">
                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="rounded-xl bg-white p-6 shadow">
                            <p class="text-sm text-gray-500">Total Products</p>
                            <p class="mt-2 text-3xl font-bold">{{ $totalProducts }}</p>
                        </div>
                        <div class="rounded-xl bg-white p-6 shadow">
                            <p class="text-sm text-gray-500">Active Products</p>
                            <p class="mt-2 text-3xl font-bold">{{ $activeProducts }}</p>
                        </div>
                        <div class="rounded-xl bg-white p-6 shadow">
                            <p class="text-sm text-gray-500">Low Stock</p>
                            <p class="mt-2 text-3xl font-bold">{{ $lowStockProducts }}</p>
                        </div>
                    </div>

                    <div class="mt-10 rounded-xl bg-white p-6 shadow">
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-xl font-bold">Recent product history</h2>
                            <a href="#" class="text-sm font-semibold text-red-600">View all</a>
                        </div>

                        @if ($history->isEmpty())
                            <p class="text-gray-500">No product history yet.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($history as $entry)
                                    <div class="flex items-center justify-between rounded-lg border border-gray-200 p-3">
                                        <div>
                                            <p class="font-semibold">
                                                @if ($entry->product)
                                                    {{ $entry->product->name }}
                                                @else
                                                    Deleted product
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-500">{{ $entry->details }}</p>
                                        </div>
                                        <div class="text-right text-sm text-gray-500">
                                            <p>{{ $entry->action }}</p>
                                            <p>{{ $entry->created_at ? $entry->created_at->format('d/m/Y H:i') : '' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Products Tab -->
                <div x-show="activeTab === 'products'" class="rounded-xl bg-white p-6 shadow">
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-xl font-bold">Products Management</h2>
                        <a href="{{ route('admin.products.create') }}" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Add Product</a>
                    </div>
                    <p class="text-gray-500">Manage your products here. Add, edit, or remove products from your store.</p>

                    @php
                        $recentProducts = \App\Models\Product::with(['brand', 'category'])->latest()->take(6)->get();
                    @endphp

                    @if ($recentProducts->isEmpty())
                        <p class="mt-6 text-sm text-gray-500">No products yet. Add your first product to start selling.</p>
                    @else
                        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                            @foreach ($recentProducts as $product)
                                <div class="rounded-xl border border-gray-200 p-4">
                                    <div class="mb-3 flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-lg font-bold text-gray-900">{{ $product->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $product->sku }}</p>
                                        </div>
                                        <span class="rounded-full {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }} px-2 py-1 text-xs font-semibold">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>

                                    <div class="space-y-2 text-sm text-gray-600">
                                        <p><span class="font-medium">Brand:</span> {{ $product->brand?->name ?? 'N/A' }}</p>
                                        <p><span class="font-medium">Category:</span> {{ $product->category?->name ?? 'N/A' }}</p>
                                        <p><span class="font-medium">Price:</span> ${{ number_format((float) $product->price, 2) }}</p>
                                        <p><span class="font-medium">Stock:</span> {{ $product->stock_qty }}</p>
                                    </div>

                                    <div class="mt-4 flex gap-2">
                                        <a href="{{ route('admin.products.show', $product) }}" class="rounded bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500">Detail</a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="rounded bg-yellow-500 px-3 py-2 text-sm font-semibold text-white hover:bg-yellow-400">Edit</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Orders Tab -->
                <div x-show="activeTab === 'orders'" class="rounded-xl bg-white p-6 shadow">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold">Orders Management</h2>
                    </div>
                    <p class="text-gray-500">View and manage customer orders. Track order status, process refunds, and handle shipping.</p>
                </div>

                <!-- Users Tab -->
                <div x-show="activeTab === 'users'" class="rounded-xl bg-white p-6 shadow">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold">Users Management</h2>
                    </div>
                    <p class="text-gray-500">Manage user accounts. View user details, manage permissions, and handle account issues.</p>
                </div>

                <!-- Categories Tab -->
                <div x-show="activeTab === 'categories'" class="rounded-xl bg-white p-6 shadow">
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-xl font-bold">Categories Management</h2>
                        <a href="{{ route('admin.categories.create') }}" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Add Category</a>
                    </div>
                    <p class="text-gray-500">Organize your products into categories. Create, edit, or remove product categories.</p>

                    @php
                        $recentCategories = \App\Models\Category::with('parent')->orderByDesc('id')->take(6)->get();
                    @endphp

                    @if ($recentCategories->isEmpty())
                        <p class="mt-6 text-sm text-gray-500">No categories yet. Add your first category to organize products.</p>
                    @else
                        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                            @foreach ($recentCategories as $category)
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
                </div>

                <!-- Brands Tab -->
                <div x-show="activeTab === 'brands'" class="rounded-xl bg-white p-6 shadow">
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-xl font-bold">Brands Management</h2>
                        <button class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Add Brand</button>
                    </div>
                    <p class="text-gray-500">Manage product brands. Add new brands, update brand information, and manage brand visibility.</p>
                </div>

                <!-- Inventory Tab -->
                <div x-show="activeTab === 'inventory'" class="rounded-xl bg-white p-6 shadow">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold">Inventory Management</h2>
                    </div>
                    <p class="text-gray-500">Track stock levels, set low stock alerts, and manage inventory across all products.</p>
                </div>

                <!-- Reviews Tab -->
                <div x-show="activeTab === 'reviews'" class="rounded-xl bg-white p-6 shadow">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold">Reviews Management</h2>
                    </div>
                    <p class="text-gray-500">Moderate customer reviews. Approve, reject, or respond to product reviews.</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>


