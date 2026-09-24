<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class logincontroller extends Controller
{
    //
    public function showForm()
    {
        return view('verify.login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email', $request->input('username'));

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $email)->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password_hash)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended('/admin');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
