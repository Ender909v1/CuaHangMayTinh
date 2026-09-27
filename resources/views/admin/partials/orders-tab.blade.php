<div class="mb-6">
    <h2 class="text-xl font-bold">Orders Management</h2>
</div>
@if ($orders->isEmpty())
    <p class="text-gray-500">No orders yet. Orders placed from checkout will appear here with their bill code.</p>
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
                        <td class="px-4 py-3 font-semibold">${{ number_format((float) $order->total_amount, 2) }}</td>
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
