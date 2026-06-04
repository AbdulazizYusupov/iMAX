@extends('layouts.app')

@section('title', 'Moliya va Analitika Paneli')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
    <div>
        <h1 class="text-2xl font-bold text-white tracking-tight">Do'kon Tahlili va Moliya Paneli</h1>
        <p class="text-sm text-slate-400">Umumiy aylanma mablag'lar, real foyda va ombor balansi</p>
    </div>
    <div class="text-xs font-mono text-slate-500 bg-slate-900 border border-slate-850 px-3 py-1.5 rounded-xl self-start sm:self-auto">
        Bugungi sana: {{ date('d.m.Y') }}
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-800/80 p-5 rounded-2xl shadow-lg relative overflow-hidden group">
        <div class="absolute -right-2 -top-2 text-6xl text-blue-500/10 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-money-bill-trend-up"></i>
        </div>
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Jami Oborot</span>
        <h3 class="text-xl font-black text-blue-400">{{ number_format($total_turnover, 0, '.', ' ') }} so'm</h3>
        <p class="text-[11px] text-slate-500 mt-2"><i class="fa-solid fa-circle-check text-blue-500/50"></i> Umumiy savdo tushumi</p>
    </div>

    <div class="bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-800/80 p-5 rounded-2xl shadow-lg relative overflow-hidden group">
        <div class="absolute -right-2 -top-2 text-6xl text-emerald-500/10 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-sack-dollar"></i>
        </div>
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Sof Foyda (Net)</span>
        <h3 class="text-xl font-black {{ $real_net_profit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
            {{ $real_net_profit >= 0 ? '+' : '' }}{{ number_format($real_net_profit, 0, '.', ' ') }} so'm
        </h3>
        <p class="text-[11px] text-slate-550 mt-2 text-emerald-500/70"><i class="fa-solid fa-chart-line"></i> Chiqimlar chegirilgan foyda</p>
    </div>

    <div class="bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-800/80 p-5 rounded-2xl shadow-lg relative overflow-hidden group">
        <div class="absolute -right-2 -top-2 text-6xl text-rose-500/10 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-money-bill-transfer"></i>
        </div>
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Umumiy Xarajatlar</span>
        <h3 class="text-xl font-black text-rose-400">{{ number_format($total_expenses, 0, '.', ' ') }} so'm</h3>
        <p class="text-[11px] text-slate-550 mt-2 text-rose-500/70"><i class="fa-solid fa-wallet"></i> Do'kon doimiy chiqimlari</p>
    </div>

    <div class="bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-800/80 p-5 rounded-2xl shadow-lg relative overflow-hidden group">
        <div class="absolute -right-2 -top-2 text-6xl text-amber-500/10 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-box-open"></i>
        </div>
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Ombor (Sarmoya)</span>
        <h3 class="text-xl font-black text-amber-400">{{ number_format($active_phones_cost, 0, '.', ' ') }} so'm</h3>
        <p class="text-[11px] text-slate-500 mt-2"><i class="fa-solid fa-layer-group text-amber-500/50"></i> {{ $active_phones_count }} ta sotuvdagi telefon</p>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-basket-shopping text-emerald-500"></i> Oxirgi Sotuvlar
            </h3>
            <a href="{{ route('sales.index') }}" class="text-xs text-blue-400 hover:underline">Barcha sotuvlar <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 pb-2">
                        <th class="pb-2 font-semibold">Model</th>
                        <th class="pb-2 font-semibold">Xaridor</th>
                        <th class="pb-2 font-semibold text-right">Sotildi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse($latest_sales as $sale)
                    <tr>
                        <td class="py-2.5">
                            <p class="font-bold text-slate-200">{{ $sale->phone->name }}</p>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-950 text-slate-400 uppercase border border-slate-850">{{ $sale->payment_method }}</span>
                        </td>
                        <td class="py-2.5 text-slate-400">
                            <p class="text-slate-300">{{ $sale->customer_name ?? 'Ismsiz' }}</p>
                            <span class="text-[10px] text-slate-500">{{ $sale->customer_phone ?? '-' }}</span>
                        </td>
                        <td class="py-2.5 font-black text-emerald-400 text-right text-sm">{{ number_format($sale->sold_price, 0, '.', ' ') }} so'm</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-6 text-center text-slate-600">Hozircha sotuvlar amalga oshirilmagan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-mobile-screen-button text-blue-500"></i> Sotuvdagi Oxirgi Tovarar
            </h3>
            <a href="{{ route('phones.index') }}" class="text-xs text-blue-400 hover:underline">Omborga o'tish <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 pb-2">
                        <th class="pb-2 font-semibold">Model</th>
                        <th class="pb-2 font-semibold">Kategoriya</th>
                        <th class="pb-2 font-semibold text-right">Belgilangan narx</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse($latest_phones as $phone)
                    <tr>
                        <td class="py-2.5 font-bold text-slate-200">{{ $phone->name }}</td>
                        <td class="py-2.5 text-slate-400">{{ $phone->category->name }}</td>
                        <td class="py-2.5 font-bold text-blue-400 text-right">{{ number_format($phone->selling_price, 0, '.', ' ') }} so'm</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-6 text-center text-slate-600">Ombor bo'sh, hamma tovar sotilgan!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection