<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Ma'lumotlarni tekshirish
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Formadagi checkbox bosilgan yoki bosilmaganini aniqlash (true/false)
        $remember = $request->boolean('remember');

        // 2. Tizimga kirish va eslab qolish tokenini uzatish
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')->with('success', 'Xush kelibsiz!');
        }

        return back()->withErrors([
            'username' => 'Kiritilgan username yoki parol noto‘g‘ri.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Tizimdan chiqdingiz.');
    }
}
