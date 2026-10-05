<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $unseenCount = $user->orders()->where(function ($query) {
            $query->whereNull('user_seen_at')
                ->orWhereColumn('updated_at', '>', 'user_seen_at');
        })->count();

        return view('account.show', compact('user', 'unseenCount'));
    }

    /**
     * Purchase history ("My Orders"): what the user bought + delivered or not.
     * Visiting this page marks everything as seen, clearing the "!" badge.
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders()->with(['items.product'])->latest('id')->get();

        $user->orders()->where(function ($query) {
            $query->whereNull('user_seen_at')
                ->orWhereColumn('updated_at', '>', 'user_seen_at');
        })->update(['user_seen_at' => now()]);

        return view('account.orders', compact('orders'));
    }

    /**
     * Customer cancels their own mistaken order (whole bill).
     * Only while pending + unpaid: restocks every item, marks cancelled.
     */
    public function cancelOrder(Order $order)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            abort(403, 'This is not your order.');
        }

        if (! $order->canBeCancelledByCustomer()) {
            return back()->withErrors([
                'order' => 'This order can no longer be cancelled (it is already '.$order->status.' / payment '.$order->payment_status.'). Please contact support.',
            ]);
        }

        DB::transaction(function () use ($order): void {
            $order->loadMissing('items.product');

            foreach ($order->items as $item) {
                $item->product?->increment('stock_qty', $item->quantity);
            }

            $order->status = 'cancelled';
            if ($order->payment_status === 'pending') {
                $order->payment_status = 'failed';
            }
            $order->updated_at = now()->addSecond();
            $order->save();
        });

        return back()->with('status', 'Order '.($order->bill_code ?? '#'.$order->id).' cancelled. Items were returned to stock.');
    }

    /**
     * Customer removes one mistaken item line from their order.
     * Restocks that line, recalculates the total; if it was the last
     * line, the whole order becomes cancelled.
     */
    public function removeOrderItem(Order $order, OrderItem $item)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id || $item->order_id !== $order->id) {
            abort(403, 'This is not your order.');
        }

        if (! $order->canRemoveItems()) {
            return back()->withErrors([
                'order' => 'Items in this order can no longer be changed (it is already '.$order->status.' / payment '.$order->payment_status.'). Please contact support.',
            ]);
        }

        DB::transaction(function () use ($order, $item): void {
            $item->loadMissing('product');
            $item->product?->increment('stock_qty', $item->quantity);
            $item->delete();

            $order->load('items');
            $order->total_amount = $order->items->sum(fn (OrderItem $line) => (float) $line->unit_price * (int) $line->quantity);

            if ($order->items->isEmpty()) {
                $order->status = 'cancelled';
                if ($order->payment_status === 'pending') {
                    $order->payment_status = 'failed';
                }
            }

            $order->updated_at = now()->addSecond();
            $order->save();
        });

        $fresh = $order->fresh('items');

        return back()->with(
            'status',
            $fresh && $fresh->items->isEmpty()
                ? 'Item removed. That was the last item, so order '.($order->bill_code ?? '#'.$order->id).' is now cancelled.'
                : 'Item removed from order '.($order->bill_code ?? '#'.$order->id).'. Total updated.'
        );
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user->full_name = $validated['full_name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;

        if (! empty($validated['password'])) {
            $user->password_hash = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('status', 'Account updated successfully.');
    }
}
