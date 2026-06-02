@extends('layouts.app')

@section('title', 'Profil Sozlamalari - SmartStore')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">Profil sozlamalari</h1>
    <p class="text-sm text-slate-400">Shaxsiy ma'lumotlaringiz va tizimga kirish parolini boshqarish</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-4 mb-6 border-b border-slate-800/60 pb-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-blue-500/20">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-400">@ {{ $user->username }} &bull; Tizim Administratori</p>
                </div>
            </div>

            @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-sm flex items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-check"></i> <span>{{ session('success') }}</span>
            </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">To'liq ismingiz *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Login (Username) *</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                        class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('username') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
        </div>
        <div class="flex justify-end pt-4 border-t border-slate-800/40 mt-6">
            <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white font-medium py-2.5 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all cursor-pointer text-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> O'zgarishlarni saqlash
            </button>
        </div>
        </form>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-6 border-b border-slate-800/60 pb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-base">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Xavfsizlik</h3>
                    <p class="text-xs text-slate-400">Tizimga kirish parolini yangilash</p>
                </div>
            </div>

            @if(session('password_success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-sm flex items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-check"></i> <span>{{ session('password_success') }}</span>
            </div>
            @endif

            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Amaldagi eski parol *</label>
                    <input type="password" name="current_password" required placeholder="••••••••"
                        class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('current_password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Yangi parol *</label>
                        <input type="password" name="password" required placeholder="Kamida 6 ta belgi"
                            class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                        @error('password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Yangi parolni takrorlang *</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                            class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
        </div>
        <div class="flex justify-end pt-4 border-t border-slate-800/40 mt-6">
            <button type="submit" class="w-full sm:w-auto bg-amber-600 hover:bg-amber-500 text-white font-medium py-2.5 px-6 rounded-xl shadow-lg shadow-amber-500/10 transition-all cursor-pointer text-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-key"></i> Parolni yangilash
            </button>
        </div>
        </form>
    </div>

</div>
@endsection