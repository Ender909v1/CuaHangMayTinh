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
                @if ($errors->any())
                <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

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

                    <section class="mt-8" aria-labelledby="product-statistics-heading">
                        <h2 id="product-statistics-heading" class="sr-only">Product statistics</h2>
                        <div class="grid gap-6 xl:grid-cols-[1.45fr_1fr]">
                            <div class="rounded-xl bg-white p-6 shadow">
                                <div class="mb-6">
                                    <h3 class="text-lg font-bold">Product additions</h3>
                                    <p class="mt-1 text-sm text-gray-500">Products added each month over the last 8 months</p>
                                </div>

                                <div class="relative h-48" role="list" aria-label="Monthly product additions over the last eight months">
                                    <div class="pointer-events-none absolute inset-0 flex flex-col justify-between">
                                        @foreach (range(0, 3) as $line)
                                            <div class="border-t border-gray-100"></div>
                                        @endforeach
                                    </div>
                                    <div class="absolute inset-0 flex items-end justify-around gap-2 px-1">
                                        @foreach ($monthlyProductStats as $month)
                                            <div class="relative z-10 flex h-full min-w-0 flex-1 flex-col items-center justify-end" role="listitem" aria-label="{{ $month['label'] }}: {{ $month['count'] }} products added">
                                                <span class="mb-2 text-xs font-medium text-gray-500">{{ $month['count'] }}</span>
                                                <div
                                                    class="w-full max-w-8 rounded-t-md bg-red-500 transition-colors hover:bg-red-600"
                                                    style="height: {{ $month['count'] > 0 ? max(4, (int) round($month['count'] / $maxMonthlyProductCount * 100)) : 0 }}%"
                                                    aria-hidden="true"
                                                ></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mt-3 grid grid-cols-8 gap-2 text-center text-xs font-medium text-gray-400">
                                    @foreach ($monthlyProductStats as $month)
                                        <span>{{ $month['label'] }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-xl bg-white p-6 shadow">
                                <div class="mb-6">
                                    <h3 class="text-lg font-bold">Stock health</h3>
                                    <p class="mt-1 text-sm text-gray-500">Product availability across your catalog</p>
                                </div>

                                <div class="flex flex-col items-center gap-6 sm:flex-row sm:justify-around">
                                    <div class="relative h-44 w-44 shrink-0" role="img" aria-label="Stock health: {{ $stockTotal }} products">
                                        <svg class="h-full w-full" viewBox="0 0 120 120" aria-hidden="true">
                                            <circle cx="60" cy="60" r="44" fill="none" stroke="#f3f4f6" stroke-width="18"></circle>
                                            @foreach ($stockStats as $stat)
                                                <circle
                                                    cx="60"
                                                    cy="60"
                                                    r="44"
                                                    fill="none"
                                                    stroke="{{ $stat['color'] }}"
                                                    stroke-width="18"
                                                    stroke-dasharray="{{ $stat['dashLength'] }} {{ $stockChartCircumference }}"
                                                    stroke-dashoffset="-{{ $stat['offset'] }}"
                                                    transform="rotate(-90 60 60)"
                                                ></circle>
                                            @endforeach
                                        </svg>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                                            <span class="text-3xl font-bold text-gray-800">{{ $stockTotal }}</span>
                                            <span class="text-xs text-gray-500">products</span>
                                        </div>
                                    </div>

                                    <ul class="w-full space-y-4 sm:max-w-48">
                                        @foreach ($stockStats as $stat)
                                            <li class="flex items-center justify-between gap-3 text-sm" aria-label="{{ $stat['label'] }}: {{ $stat['count'] }}">
                                                <span class="flex items-center gap-2 text-gray-600">
                                                    <span class="h-3 w-3 shrink-0 rounded-full" style="background-color: {{ $stat['color'] }}" aria-hidden="true"></span>
                                                    {{ $stat['label'] }}
                                                </span>
                                                <span class="shrink-0 font-medium text-gray-700">{{ $stat['count'] }} <span class="text-gray-400">({{ $stat['percent'] }}%)</span></span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>

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
                                            @if ($entry->note)
                                                <p class="text-sm text-gray-700">Note: {{ $entry->note }}</p>
                                            @endif
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
                    @include('admin.partials.products-tab')
                </div>

                <!-- Orders Tab -->
                <div x-show="activeTab === 'orders'" class="rounded-xl bg-white p-6 shadow">
                    @include('admin.partials.orders-tab')
                </div>

                <!-- Users Tab -->
                <div x-show="activeTab === 'users'" class="rounded-xl bg-white p-6 shadow">
                    @include('admin.partials.users-tab')
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
                    @include('admin.partials.inventory-tab')
                </div>

                <!-- Reviews Tab -->
                <div x-show="activeTab === 'reviews'" class="rounded-xl bg-white p-6 shadow">
                    @include('admin.partials.reviews-tab')
                </div>
            </div>
        </main>
    </div>
</body>
</html>
