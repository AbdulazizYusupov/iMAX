@extends('layouts.app')

@section('title', 'Yangi Sotuv - SmartStore')

@section('content')
<div class="mb-6">
    <a href="{{ route('sales.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 mb-2"><i class="fa-solid fa-arrow-left"></i> Tariqqa qaytish</a>
    <h1 class="text-2xl font-bold text-white">Yangi sotuv (Kassa)</h1>
    <p class="text-sm text-slate-400">Omborda tayyor turgan mahsulotni mijozga sotish formasini to'ldiring</p>
</div>

<div class="max-w-2xl bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
    <form action="{{ route('sales.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Sotiladigan Telefon *</label>
            <select name="phone_id" id="phone_select" required onchange="updatePrice()" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="" data-price="0">-- Telefonni tanlang --</option>
                @foreach($available_phones as $phone)
                <option value="{{ $phone->id }}" data-price="{{ $phone->selling_price }}"
                    {{ (isset($selected_phone) && $selected_phone->id == $phone->id) ? 'selected' : '' }}>
                    {{ $phone->name }} ({{ $phone->category->name }}) - {{ number_format($phone->selling_price, 0, '.', ' ') }} so'm
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Sotuv Narxi (so'mda) *</label>
            <input type="number" name="sold_price" id="sold_price" required min="0"
                value="{{ isset($selected_phone) ? $selected_phone->selling_price : old('sold_price') }}"
                placeholder="0" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <span class="text-[11px] text-slate-500 mt-1 block">Tizim avtomatik belgilangan sotuv narxini qo'yadi, xohlasangiz skidka qilib o'zgartirishingiz mumkin.</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">To'lov Usuli *</label>
                <select name="payment_method" id="payment_method" required onchange="toggleInstallmentFields()" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="naqd">Naqd pul</option>
                    <option value="karta">Plastik karta (Click/Payme/Uzcard)</option>
                    <option value="muddatli">Muddatli to'lov (Rassrochka)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Xaridor Ismi</label>
                <input type="text" name="customer_name" placeholder="Masalan: Asadbek" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>

        <div id="installment_box" class="hidden bg-slate-950/40 p-4 border border-slate-800 rounded-xl grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-amber-400 uppercase tracking-wider mb-1.5">Bo'lib berish muddati (Oylar) *</label>
                <input type="number" name="installment_months" id="installment_months" min="1" max="24" value="3" oninput="calculateMonthlyPayment()"
                    class="block w-full px-4 py-2.5 bg-slate-950 border border-amber-600/50 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div class="flex flex-col justify-center bg-amber-500/5 border border-amber-500/10 px-4 py-2 rounded-xl">
                <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Har oylik to'lov (Foizsiz)</span>
                <p class="text-base font-black text-amber-400" id="monthly_payment_text">0 so'm / oyiga</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Xaridor Telefon Raqami</label>
                <input type="text" name="customer_phone" placeholder="+998 90 123-4567" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Qo'shimcha Izoh</label>
                <input type="text" name="notes" placeholder="Kafolat muddati yoki sovg'alar haqida..." class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
            <a href="{{ route('sales.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 rounded-xl text-sm font-medium text-slate-300">Bekor qilish</a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 rounded-xl text-sm font-medium text-white shadow-lg shadow-emerald-500/10 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-circle-check"></i> Sotuvni Yakunlash
            </button>
        </div>
    </form>
</div>

<script>
    // Select o'zgarganda narxni avtomatik inputga yozish
    function updatePrice() {
        const select = document.getElementById('phone_select');
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price');

        if (price && price > 0) {
            document.getElementById('sold_price').value = price;
        }
        calculateMonthlyPayment();
    }

    // Har safar narx o'zgarganda ham oylik to'lov qayta hisoblansin
    document.getElementById('sold_price').addEventListener('input', calculateMonthlyPayment);

    // Muddatli to'lov tanlanganda inputni ochish/yopish
    function toggleInstallmentFields() {
        const method = document.getElementById('payment_method').value;
        const box = document.getElementById('installment_box');
        const monthsInput = document.getElementById('installment_months');

        if (method === 'muddatli') {
            box.classList.remove('hidden');
            monthsInput.required = true;
            calculateMonthlyPayment();
        } else {
            box.classList.add('hidden');
            monthsInput.required = false;
        }
    }

    // Oylik to'lovni hisoblash (Sotuv narxi / Oylar)
    function calculateMonthlyPayment() {
        const price = parseFloat(document.getElementById('sold_price').value) || 0;
        const months = parseInt(document.getElementById('installment_months').value) || 1;

        if (price > 0 && months > 0) {
            const monthly = Math.round(price / months);
            // Raqamlarni chiroyli formatda chiqarish (masalan: 1 200 000)
            const formatted = new Intl.NumberFormat('fr-FR').format(monthly);
            document.getElementById('monthly_payment_text').innerText = formatted + " so'm / oyiga";
        } else {
            document.getElementById('monthly_payment_text').innerText = "0 so'm / oyiga";
        }
    }
</script>
@endsection