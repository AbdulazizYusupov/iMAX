@extends('layouts.app')

@section('title', 'Asosiy Panel - SmartStore')

@section('content')
<div class="bg-gradient-to-r from-blue-900/40 to-cyan-900/20 border border-blue-500/20 p-6 rounded-2xl shadow-xl">
    <h1 class="text-2xl font-bold text-white mb-1">Xush kelibsiz, {{ Auth::user()->name }}! 👋</h1>
    <p class="text-sm text-slate-400">Telefon sotish loyihangiz boshqaruv paneliga muvaffaqiyatli kirdingiz.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
    <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-md flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jami Telefonlar</p>
            <h3 class="text-2xl font-bold mt-1 text-white">0 dona</h3>
        </div>
        <div class="w-12 h-12 bg-blue-500/10 text-blue-400 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-boxes-stacked"></i>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-md flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Bugungi Savdo</p>
            <h3 class="text-2xl font-bold mt-1 text-white">0 so'm</h3>
        </div>
        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-chart-line"></i>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-md flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Yangi Buyurtmalar</p>
            <h3 class="text-2xl font-bold mt-1 text-white">0 ta</h3>
        </div>
        <div class="w-12 h-12 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-basket-shopping"></i>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-md flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Adminlar</p>
            <h3 class="text-2xl font-bold mt-1 text-white">1 nafar</h3>
        </div>
        <div class="w-12 h-12 bg-purple-500/10 text-purple-400 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-users-gear"></i>
        </div>
    </div>
</div>
@endsection