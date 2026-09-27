<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order {{ $order->bill_code ?? '#'.$order->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        <nav class="bg-gray-900 text-white">
            <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
                <div><h1 class="text-xl font-bold">Order {{ $order->bill_code ?? '#'.$order->id }}</h1></div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.orders.index') }}" class="hover:text-red-400">Back</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-500">Logout</button></form>
                </div>
            </div>
        </nav>
        <main class="mx-auto max-w-4xl px-4 py-10">
            <div class="rounded-xl bg-white p-8 shadow space-y-6">
                @if (session('success'))
                    <div class="rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <div class="grid gap-4 md:grid-cols-2 text-sm">
                    <div><p class="text-gray-500">Bill code</p><p class="font-mono text-lg font-bold">{{ $order->bill_code ?? '—' }}</p></div>
                    <div>
                        <p class="text-gray-500">Status (change + auto-save)</p>
                        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-1 flex items-center gap-2">
                            @csrf @method('PUT')
                            <select name="status" class="rounded border px-2 py-1" onchange="this.form.submit()">
                                @foreach (\App\Models\Order::STATUSES as $statusOption)
                                    <option value="{{ $statusOption }}" {{ $order->status === $statusOption ? 'selected' : '' }}>{{ ucfirst($statusOption) }}</option>
                                @endforeach
                            </select>
                            <span class="text-xs text-gray-400">/ {{ $order->payment_status }}</span>
                        </form>
                    </div>
                    <div><p class="text-gray-500">Customer</p><p class="font-semibold">{{ $order->user?->full_name ?? 'Guest checkout' }} ({{ $order->user?->email ?? 'no account' }})</p></div>
                    <div><p class="text-gray-500">Total</p><p class="font-bold">${{ number_format((float) $order->total_amount, 2) }}</p></div>
                    <div class="md:col-span-2"><p class="text-gray-500">Shipping</p><p class="font-medium">{{ $order->shipping_address }}</p></div>
                    <div><p class="text-gray-500">Payment method</p><p class="font-medium">{{ $order->payment_method ?? '—' }}</p></div>
                    <div><p class="text-gray-500">Date</p><p class="font-medium">{{ $order->order_date }}</p></div>
                </div>
                <div>
                    <h2 class="font-bold">Items</h2>
                    <ul class="mt-2 divide-y text-sm">
                        @foreach ($order->items as $item)
                            <li class="flex justify-between gap-3 py-2">
                                <span>{{ $item->product?->name ?? 'Product #'.$item->product_id }} × {{ $item->quantity }} <span class="text-gray-400">(@ ${{ number_format((float) $item->unit_price, 2) }})</span></span>
                                <span class="font-semibold">${{ number_format((float) $item->unit_price * (int) $item->quantity, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
