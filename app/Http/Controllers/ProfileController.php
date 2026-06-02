<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Profil sahifasini ko'rsatish
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Profil ma'lumotlarini yangilash (Ism va Username)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $fields = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($fields);

        return redirect()->route('profile.edit')->with('success', 'Profil ma’lumotlari muvaffaqiyatli yangilandi!');
    }

    /**
     * Parolni xavfsiz o'zgartirish
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'], // joriy paroli to'g'riligini tekshiradi
            'password' => ['required', 'string', 'min:6', 'confirmed'], // yangi parol va uning tasdig'i (password_confirmation)
        ], [
            'current_password.current_password' => 'Amaldagi eski parol noto‘g‘ri kiritildi.',
            'password.confirmed' => 'Yangi parol tasdig‘i mos kelmadi.',
            'password.min' => 'Yangi parol kamida 6 ta belgidan iborat bo‘lishi shart.'
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')->with('password_success', 'Parolingiz muvaffaqiyatli o‘zgartirildi!');
    }
}
