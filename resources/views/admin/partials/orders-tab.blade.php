<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <h2 class="text-xl font-bold">Orders Management</h2>
    <a href="{{ route('admin.orders.index') }}" class="rounded bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-700">Full Orders Page</a>
</div>
<form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6 grid gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 md:grid-cols-2 xl:grid-cols-5">
    <input type="hidden" name="tab" value="orders">
    <div class="xl:col-span-2">
        <label for="dashboard-order-search" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search</label>
        <input id="dashboard-order-search" type="text" name="search" value="{{ $orderFilters['search'] ?? '' }}" placeholder="Bill code, customer, email, address…" class="w-full rounded border px-3 py-2 text-sm" oninput="clearTimeout(window._adminFilterT);window._adminFilterT=setTimeout(()=>this.form.submit(),600)">
    </div>
    <div>
        <label for="dashboard-order-status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Status</label>
        <select id="dashboard-order-status" name="status" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach (\App\Models\Order::STATUSES as $statusOption)
                <option value="{{ $statusOption }}" {{ ($orderFilters['status'] ?? '') === $statusOption ? 'selected' : '' }}>{{ ucfirst($statusOption) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="dashboard-order-from" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Order date from</label>
        <input id="dashboard-order-from" type="date" name="date_from" value="{{ $orderFilters['date_from'] ?? '' }}" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
    </div>
    <div>
        <label for="dashboard-order-to" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Order date to</label>
        <input id="dashboard-order-to" type="date" name="date_to" value="{{ $orderFilters['date_to'] ?? '' }}" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
    </div>
    <div class="flex gap-2 md:col-span-2 xl:col-span-5">
        <a href="{{ route('admin.dashboard', ['tab' => 'orders']) }}" class="rounded border px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Reset</a>
    </div>
</form>
@if ($orders->isEmpty())
    <p class="text-gray-500">No orders match these filters. Orders placed from checkout will appear here with their bill code.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-4 py-3">Bill Code</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Items</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="border-b">
                        <td class="px-4 py-3 font-mono font-bold">{{ $order->bill_code ?? '#'.$order->id }}</td>
                        <td class="px-4 py-3">{{ $order->user?->full_name ?? 'Guest' }}</td>
                        <td class="px-4 py-3">{{ $order->items->sum('quantity') }}</td>
                        <td class="px-4 py-3 font-semibold">{{ number_format((float) $order->total_amount, 0, ',', '.') }} ₫</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex items-center gap-2">
                                @csrf @method('PUT')
                                <select name="status" class="rounded border px-2 py-1 text-xs" onchange="this.form.submit()">
                                    @foreach (\App\Models\Order::STATUSES as $statusOption)
                                        <option value="{{ $statusOption }}" {{ $order->status === $statusOption ? 'selected' : '' }}>{{ ucfirst($statusOption) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="rounded bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-500">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
