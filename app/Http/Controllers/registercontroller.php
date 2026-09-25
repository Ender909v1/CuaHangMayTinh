<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class registercontroller extends Controller
{
    //
    public function showForm()
    {
        return view('verify.register');
    }

    public function register(Request $request)
    {
        $credentials = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ], [
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::create([
            'full_name' => $credentials['full_name'],
            'email'     => $credentials['email'],
            'password_hash' => Hash::make($credentials['password']),
            'role' => 'customer',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->intended('/');
    }
}
