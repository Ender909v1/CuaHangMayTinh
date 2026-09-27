<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="{{ asset('tailstore4-main/logo/logo.jpg') }}" />
    <title>Order placed</title>
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
            <div class="mx-auto max-w-2xl rounded-xl bg-white p-8 shadow">
                <h1 class="text-2xl font-bold text-green-700">Thank you! Your order was placed.</h1>
                <p class="mt-2 text-gray-600">Your bill code is:</p>
                <p class="mt-1 text-3xl font-bold tracking-wide">{{ $order->bill_code }}</p>
                <p class="mt-2 text-sm text-gray-500">Please keep this code to track your order.</p>

                <div class="mt-6 border-t pt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total paid</span>
                        <span class="font-bold">${{ number_format((float) $order->total_amount, 2) }}</span>
                    </div>
                    <div class="mt-2 flex justify-between text-sm">
                        <span class="text-gray-500">Payment</span>
                        <span class="font-semibold">{{ $order->payment_method ?? 'cod' }}</span>
                    </div>
                    <div class="mt-2 text-sm">
                        <span class="text-gray-500">Shipping to:</span>
                        <p class="font-medium text-gray-800">{{ $order->shipping_address }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h2 class="font-semibold">Items</h2>
                    <ul class="mt-2 divide-y text-sm">
                        @foreach ($order->items as $item)
                            <li class="flex justify-between gap-3 py-2">
                                <span>{{ $item->product?->name ?? 'Product #'.$item->product_id }} × {{ $item->quantity }}</span>
                                <span class="font-semibold">${{ number_format((float) $item->unit_price * (int) $item->quantity, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('cuahangmaytinh') }}" class="rounded-full bg-primary px-5 py-2 font-semibold text-white">Continue Shopping</a>
                    <a href="{{ route('cart') }}" class="rounded-full border px-5 py-2 font-semibold">Back to Cart</a>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Order is placed: empty the local cart so it is not re-submitted.
        try { localStorage.removeItem('computer-store-cart'); } catch (e) {}
    </script>
</body>

</html>
