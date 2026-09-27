<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
