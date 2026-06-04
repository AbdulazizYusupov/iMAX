@extends('layouts.app')

@section('title', 'Sotuvlar - SmartStore')

@section('content')
@if(session('success'))
<div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-sm flex items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-check"></i> <span>{{ session('success') }}</span>
</div>
@endif

<!-- 1. BOSH HEAD QISMI (Sarlavha, Jami Summa va Yangi Sotuv tugmasi) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-center mb-6">
    <div class="lg:col-span-1">
        <h1 class="text-2xl font-bold text-white">Sotuvlar tarixi</h1>
        <p class="text-sm text-slate-400">Do'kondan sotilgan barcha mahsulotlar va tushumlar nazorati</p>
    </div>

    <div class="lg:col-span-2 flex flex-col sm:flex-row sm:items-center justify-end gap-3">
        <!-- Jami savdo summasi -->
        <div class="bg-emerald-500/10 border border-emerald-500/20 px-4 py-2 rounded-xl text-left sm:text-right">
            <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold block">Jami Savdo</span>
            <p class="text-lg font-black text-emerald-400">{{ number_format($total_sales_sum, 0, '.', ' ') }} so'm</p>
        </div>

        <!-- Yangi sotuv tugmasi -->
        <a href="{{ route('sales.create') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white font-medium py-3 px-4 rounded-xl shadow-lg shadow-emerald-500/20 transition-all text-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-cart-plus"></i> Yangi Sotuv (Kassa)
        </a>
    </div>
</div>

<!-- 2. ALOHIDA FILTRLAR PANELI (O'z holicha keng va chiroyli turadi) -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 bg-slate-900/50 p-3 border border-slate-800 rounded-2xl">
    <div class="flex flex-wrap items-center gap-1.5 bg-slate-950 p-1 rounded-xl border border-slate-800/80 w-full sm:w-auto">
        <!-- Barchasi -->
        <a href="{{ route('sales.index', ['filter' => 'all']) }}"
            class="flex-1 sm:flex-none text-center px-4 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $filter == 'all' ? 'bg-slate-800 text-white shadow-sm' : 'text-slate-400 hover:text-slate-200' }}">
            Barchasi
        </a>

        <!-- Bugun -->
        <a href="{{ route('sales.index', ['filter' => 'day']) }}"
            class="flex-1 sm:flex-none text-center px-4 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $filter == 'day' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/10' : 'text-slate-400 hover:text-slate-200' }}">
            Bugun
        </a>

        <!-- Shu hafta -->
        <a href="{{ route('sales.index', ['filter' => 'week']) }}"
            class="flex-1 sm:flex-none text-center px-4 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $filter == 'week' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/10' : 'text-slate-400 hover:text-slate-200' }}">
            Shu hafta
        </a>

        <!-- Shu oy -->
        <a href="{{ route('sales.index', ['filter' => 'month']) }}"
            class="flex-1 sm:flex-none text-center px-4 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $filter == 'month' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/10' : 'text-slate-400 hover:text-slate-200' }}">
            Shu oy
        </a>
    </div>

    <!-- Kichik ma'lumot matni -->
    <div class="text-xs text-slate-400 px-2 flex items-center gap-1.5 font-medium">
        @if($filter == 'day')
        <i class="fa-solid fa-calendar-day text-emerald-400 text-sm"></i>
        <span>Faqat bugungi tushumlar ko'rsatilmoqda</span>
        @elseif($filter == 'week')
        <i class="fa-solid fa-calendar-week text-emerald-400 text-sm"></i>
        <span>Dushanbadan boshlab shu haftalik tushumlar</span>
        @elseif($filter == 'month')
        <i class="fa-solid fa-calendar-days text-emerald-400 text-sm"></i>
        <span>Shu oydagi jami sotuvlar</span>
        @else
        <i class="fa-solid fa-database text-slate-500 text-sm"></i>
        <span>Umumiy savdo tarixi</span>
        @endif
    </div>
</div>

<div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead>
                <tr class="bg-slate-800/50 text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-800">
                    <th class="p-4">Sana</th>
                    <th class="p-4">Mahsulot (Telefon)</th>
                    <th class="p-4">Xaridor</th>
                    <th class="p-4">To'lov turi</th>
                    <th class="p-4">Sotilgan Narxi</th>
                    <th class="p-4">Sotuvchi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-sm text-slate-300">
                @forelse($sales as $sale)
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="p-4 text-xs text-slate-400 font-mono">{{ $sale->created_at->format('d.m.Y H:i') }}</td>
                    <td class="p-4">
                        <p class="font-bold text-white">{{ $sale->phone->name }}</p>
                        <span class="text-[10px] text-blue-400">{{ $sale->phone->category->name }}</span>
                    </td>
                    <td class="p-4">
                        <p class="text-slate-200">{{ $sale->customer_name ?? 'Noma’lum' }}</p>
                        <span class="text-xs text-slate-500">{{ $sale->customer_phone ?? '-' }}</span>
                    </td>
                    <td class="p-4">
                        <div class="flex flex-col gap-1">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold uppercase tracking-wider block text-center w-max
            {{ $sale->payment_method == 'naqd' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : '' }}
            {{ $sale->payment_method == 'karta' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
            {{ $sale->payment_method == 'muddatli' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : '' }}
        ">
                                {{ $sale->payment_method }}
                            </span>

                            @if($sale->payment_method == 'muddatli')
                            @php
                            $totalMonths = $sale->installmentPayments->count();
                            $paidMonths = $sale->installmentPayments->where('is_paid', true)->count();
                            @endphp

                            <span class="text-[11px] font-medium text-slate-400 mt-1">
                                Grafik: <strong class="text-amber-400">{{ $paidMonths }}/{{ $totalMonths }} oy</strong> to'landi
                            </span>

                            @if($paidMonths < $totalMonths)
                                <form action="{{ route('sales.payMonth', $sale->id) }}" method="POST" class="mt-1">
                                @csrf
                                <button type="submit" class="w-full bg-amber-600/20 hover:bg-amber-500 border border-amber-500/30 text-amber-400 hover:text-white transition-all text-[11px] font-bold py-1 px-2 rounded-md flex items-center justify-center gap-1 cursor-pointer">
                                    <i class="fa-solid fa-hand-holding-dollar"></i> Keyingi oyni to'lash
                                </button>
                                </form>
                                @else
                                <span class="text-[10px] text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-1.5 py-0.5 rounded font-bold text-center mt-1">
                                    <i class="fa-solid fa-circle-check"></i> To'liq yopildi
                                </span>
                                @endif
                                @endif
                        </div>
                    </td>
                    <td class="p-4 font-black text-emerald-400 text-base">
                        {{ number_format($sale->sold_price, 0, '.', ' ') }} so'm
                    </td>
                    <td class="p-4 text-xs text-slate-400 font-medium">{{ $sale->user->name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-12 text-center text-slate-500">
                        <i class="fa-solid fa-basket-shopping text-3xl mb-2 text-slate-700"></i>
                        <p>Sotuvlar tarixi bo'sh.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection