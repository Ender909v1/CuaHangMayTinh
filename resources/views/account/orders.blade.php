<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="{{ asset('tailstore4-main/logo/logo.jpg') }}" />
    <title>My Orders</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('tailstore4-main/assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('tailstore4-main/assets/css/custom.css') }}">
</head>
<body class="bg-gray-50">
    @include('partials.store-header')

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl">
                <h1 class="text-2xl font-bold mb-2">My Orders</h1>
                <p class="text-gray-500 mb-6">What you bought — and whether it has been delivered.</p>

                @forelse ($orders as $order)
                    <div class="mb-4 rounded-xl bg-white p-5 shadow">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="font-mono font-bold">{{ $order->bill_code ?? '#'.$order->id }}</p>
                            @if ($order->status === 'delivered')
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">DELIVERED</span>
                            @else
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold uppercase text-yellow-700">{{ $order->status }}</span>
                            @endif
                        </div>
                        <ul class="mt-3 divide-y text-sm">
                            @foreach ($order->items as $item)
                                <li class="flex justify-between gap-3 py-2">
                                    <span>{{ $item->product?->name ?? 'Product #'.$item->product_id }} × {{ $item->quantity }}</span>
                                    <span class="font-semibold">{{ number_format((float) $item->unit_price * (int) $item->quantity, 0, ',', '.') }} ₫</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3 flex justify-between text-sm">
                            <span class="text-gray-500">{{ $order->order_date?->format('d/m/Y H:i') }}</span>
                            <span class="font-bold">Total: {{ number_format((float) $order->total_amount, 0, ',', '.') }} ₫</span>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl bg-white p-8 text-center shadow">
                        <p class="font-semibold">You haven't bought anything yet.</p>
                        <a href="{{ route('cuahangmaytinh') }}" class="mt-4 inline-block rounded-full bg-primary px-5 py-2 font-semibold text-white">Start Shopping</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('tailstore4-main/assets/js/script.js') }}"></script>
</body>
</html>
