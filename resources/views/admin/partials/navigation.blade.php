<nav class="bg-gray-900 text-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-2.5">
        <h1 class="text-lg font-bold leading-tight">{{ $pageTitle }}</h1>
        <div class="flex shrink-0 items-center gap-2">
            <a href="{{ route('cuahangmaytinh') }}" class="rounded-full border border-white/20 px-3 py-1.5 text-xs font-semibold hover:bg-white/10">Home</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-full bg-red-600 px-3 py-1.5 text-xs font-semibold hover:bg-red-500">Logout</button>
            </form>
        </div>
    </div>
    <div class="border-t border-white/10">
        <nav aria-label="Admin sections" class="mx-auto max-w-7xl overflow-x-auto px-4">
            <div class="flex min-w-max gap-1.5 py-2">
                @foreach ([
                    ['key' => 'dashboard', 'label' => 'Dashboard', 'url' => route('admin.dashboard')],
                    ['key' => 'products', 'label' => 'Products', 'url' => route('admin.products.index')],
                    ['key' => 'orders', 'label' => 'Orders', 'url' => route('admin.orders.index')],
                    ['key' => 'users', 'label' => 'Users', 'url' => route('admin.users.index')],
                    ['key' => 'categories', 'label' => 'Categories', 'url' => route('admin.categories.index')],
                    ['key' => 'inventory', 'label' => 'Inventory', 'url' => route('admin.inventory.index')],
                    ['key' => 'reviews', 'label' => 'Reviews', 'url' => route('admin.dashboard', ['tab' => 'reviews'])],
                    ['key' => 'history', 'label' => 'History', 'url' => route('admin.history')],
                ] as $tab)
                    <a
                        href="{{ $tab['url'] }}"
                        @if ($activeTab === $tab['key']) aria-current="page" @endif
                        class="rounded-lg px-3 py-1.5 text-sm font-semibold {{ $activeTab === $tab['key'] ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                    >{{ $tab['label'] }}</a>
                @endforeach
            </div>
        </nav>
    </div>
</nav>
