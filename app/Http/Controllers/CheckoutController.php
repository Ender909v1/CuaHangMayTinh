<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('cart.checkout', [
            'billingName' => old('full_name', $user?->full_name ?? ''),
            'billingEmail' => old('email', $user?->email ?? ''),
            'billingAddress' => old('address', $user?->address ?? ''),
            'billingPhone' => old('phone', $user?->phone ?? ''),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'zip' => ['nullable', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:50'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $productIds = collect($validated['items'])->pluck('product_id')->unique()->all();
        $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

        $orderItems = [];
        $total = 0;

        foreach ($validated['items'] as $item) {
            $product = $products->get($item['product_id']);

            if (! $product || ! $product->is_active) {
                return back()->withErrors(['items' => 'One of the products in your cart is no longer available.'])->withInput();
            }

            if ($product->stock_qty < $item['quantity']) {
                return back()->withErrors([
                    'items' => "Not enough stock for {$product->name}. Only {$product->stock_qty} left.",
                ])->withInput();
            }

            $unitPrice = $product->effectivePrice();
            $total += $unitPrice * $item['quantity'];

            $orderItems[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
            ];
        }

        $shippingParts = array_filter([
            $validated['address'],
            $validated['city'] ?? null,
            $validated['state'] ?? null,
            $validated['zip'] ?? null,
        ]);

        $order = DB::transaction(function () use ($validated, $orderItems, $total, $shippingParts) {
            $order = Order::create([
                'bill_code' => $this->generateUniqueBillCode(),
                'user_id' => Auth::id(),
                'order_date' => now(),
                'total_amount' => round($total, 2),
                'status' => 'pending',
                'payment_method' => $validated['payment_method'] ?? 'cod',
                'payment_status' => 'pending',
                'shipping_address' => $validated['full_name'].' | '.$validated['email'].' | '.$validated['phone'].' | '.implode(', ', $shippingParts),
            ]);

            foreach ($orderItems as $entry) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $entry['product']->id,
                    'quantity' => $entry['quantity'],
                    'unit_price' => $entry['unit_price'],
                ]);

                $entry['product']->decrement('stock_qty', $entry['quantity']);
            }

            return $order;
        });

        return redirect()->route('checkout.success', $order->bill_code);
    }

    public function success(string $billCode)
    {
        $order = Order::with(['items.product'])->where('bill_code', $billCode)->firstOrFail();

        // Customers only see their own bill; admins can see any.
        if (Auth::check() && Auth::user()->role !== 'admin' && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('cart.checkout-success', compact('order'));
    }

    private function generateUniqueBillCode(): string
    {
        do {
            $code = 'BILL-'.strtoupper(substr(bin2hex(random_bytes(5)), 0, 8));
        } while (Order::where('bill_code', $code)->exists());

        return $code;
    }
}
