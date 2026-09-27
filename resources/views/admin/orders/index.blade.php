<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        <nav class="bg-gray-900 text-white">
            <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
                <div><h1 class="text-xl font-bold">Manage Orders</h1></div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard', ['tab' => 'orders']) }}" class="hover:text-red-400">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-500">Logout</button></form>
                </div>
            </div>
        </nav>
        <main class="mx-auto max-w-7xl px-4 py-10">
            <div class="rounded-xl bg-white p-6 shadow">
                <h2 class="mb-6 text-xl font-bold">Orders (bills from checkout)</h2>
                @if (session('success'))
                    <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="px-4 py-3">Bill Code</th>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Items</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Payment</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-mono font-bold">{{ $order->bill_code ?? '#'.$order->id }}</td>
                                    <td class="px-4 py-3">{{ $order->user?->full_name ?? 'Guest' }}<br><span class="text-xs text-gray-500">{{ $order->shipping_address }}</span></td>
                                    <td class="px-4 py-3">{{ $order->items->sum('quantity') }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ number_format((float) $order->total_amount, 0, ',', '.') }} ₫</td>
                                    <td class="px-4 py-3">{{ $order->payment_method ?? '—' }} / {{ $order->payment_status }}</td>
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
                                    <td class="px-4 py-3 text-xs">{{ $order->order_date }}</td>
                                    <td class="px-4 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="rounded bg-blue-600 px-3 py-2 text-white hover:bg-blue-500">Detail</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-4 py-6 text-center text-gray-500">No orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $orders->links() }}</div>
            </div>
        </main>
    </div>
</body>
</html>
