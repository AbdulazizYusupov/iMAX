@extends('layouts.app')

@section('title', 'Telefonlar - SmartStore')

@section('content')
<!-- Xabarnomalar (Success / Error) -->
@if(session('success'))
<div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-sm flex items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-check"></i> <span>{{ session('success') }}</span>
</div>
@endif
@if($errors->any())
<div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl text-sm mb-4">
    <ul class="list-disc pl-5">
        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
    </ul>
</div>
@endif

<!-- Sarlavha va Qo'shish Tugmasi -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Telefonlar ombori</h1>
        <p class="text-sm text-slate-400">Tizimdagi barcha telefonlar, ularning narxlari va IMEI kodlari</p>
    </div>
    <button onclick="openCreateModal()" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white font-medium py-2.5 px-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all cursor-pointer text-sm flex items-center justify-center gap-2">
        <i class="fa-solid fa-plus"></i> Yangi telefon qo'shish
    </button>
</div>

<!-- Telefonlar Jadvali -->
<div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse min-w-[1000px]">
            <thead>
                <tr class="bg-slate-800/50 text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-800">
                    <th class="p-4">Nomi & Kategoriya</th>
                    <th class="p-4">IMEI & Rang</th>
                    <th class="p-4">Kelgan Narxi</th>
                    <th class="p-4">Sotuv Narxi</th>
                    <th class="p-4 text-center">Ustama (%)</th>
                    <th class="p-4">Kelgan vaqti</th>
                    <th class="p-4 text-center">Holati</th>
                    <th class="p-4 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-sm text-slate-300">
                @forelse($phones as $phone)
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="p-4">
                        <p class="font-bold text-white text-base">{{ $phone->name }}</p>
                        <span class="text-xs text-blue-400 bg-blue-500/10 px-2 py-0.5 rounded font-medium border border-blue-500/10">{{ $phone->category->name }}</span>
                    </td>
                    <td class="p-4">
                        <p class="font-mono text-xs text-slate-200"><i class="fa-solid fa-barcode text-slate-500 mr-1"></i>{{ $phone->imei ?? 'Kiritilmagan' }}</p>
                        <p class="text-xs text-slate-400 mt-1">Rang: <span class="text-slate-300 font-medium">{{ $phone->color ?? '-' }}</span></p>
                    </td>
                    <td class="p-4 font-semibold text-slate-400">
                        {{ number_format($phone->cost_price, 0, '.', ' ') }} so'm
                    </td>
                    <td class="p-4 font-bold text-emerald-400 text-base">
                        {{ number_format($phone->selling_price, 0, '.', ' ') }} so'm
                    </td>
                    <td class="p-4 text-center">
                        <span class="inline-block px-2 py-1 rounded-lg text-xs font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                            +{{ $phone->margin_percent }}%
                        </span>
                    </td>
                    <td class="p-4 text-xs text-slate-400">
                        {{ $phone->arrival_date->format('d.m.Y H:i') }}
                    </td>
                    <td class="p-4 text-center">
                        @if($phone->status)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Omborda</span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">Sotilgan</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="openEditModal({{ json_encode($phone) }})"
                                class="p-2 bg-slate-800 hover:bg-amber-500/20 hover:text-amber-400 rounded-lg border border-slate-700 transition-colors cursor-pointer" title="Tahrirlash">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form action="{{ route('phones.destroy', $phone->id) }}" method="POST" onsubmit="return confirm('Ushbu telefonni ombordan o‘chirmoqchimisiz?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-slate-800 hover:bg-red-500/20 hover:text-red-400 rounded-lg border border-slate-700 transition-colors cursor-pointer" title="O'chirish">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-12 text-center text-slate-500">
                        <i class="fa-solid fa-mobile-screen text-3xl mb-2 text-slate-700"></i>
                        <p>Omborda telefonlar mavjud emas.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================================== -->
<!-- MODAL 1: YANGI TELEFON QO'SHISH (CREATE) -->
<!-- ========================================================================================== -->
<div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeCreateModal()"></div>
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-2xl w-full max-w-lg relative z-10 my-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Yangi telefon qo'shish</h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-white cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('phones.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Kategoriya *</label>
                    <select name="category_id" required class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Kategoriyani tanlang</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Model Nomi *</label>
                    <input type="text" name="name" required placeholder="Masalan: iPhone 15 Pro Max" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">IMEI Kodi</label>
                    <input type="text" name="imei" placeholder="15 xonali raqam" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Rangi</label>
                    <input type="text" name="color" placeholder="Black, Titanium..." class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">RAM</label>
                    <input type="text" name="ram" placeholder="Masalan: 8 GB, 12 GB" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Xotira (Storage)</label>
                    <input type="text" name="storage" placeholder="Masalan: 256 GB, 512 GB" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- 🔥 NARXLAR VA FOIZ INPUTLARI (Avtomatik kalkulyatorli) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-950/40 p-3 rounded-xl border border-slate-800/80">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Kelgan Narxi *</label>
                    <input type="number" id="create_cost_price" name="cost_price" required min="0" placeholder="Tan narxi" class="calc-input block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-purple-400 uppercase tracking-wider mb-1.5">Ustama (%)</label>
                    <input type="number" id="create_margin_percent" name="margin_percent" min="0" placeholder="Foiz" class="calc-input block w-full px-3 py-2 bg-slate-950 border border-purple-900/50 rounded-xl text-purple-300 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-emerald-400 uppercase tracking-wider mb-1.5">Sotuv Narxi *</label>
                    <input type="number" id="create_selling_price" name="selling_price" required min="0" placeholder="Sotish narxi" class="calc-input block w-full px-3 py-2 bg-slate-950 border border-emerald-900/50 rounded-xl text-emerald-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <!-- 📅 KALENDAR SANA INPUTI -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Kelgan sana va vaqt *</label>
                <div class="relative">
                    <input type="datetime-local" name="arrival_date" required value="{{ date('Y-m-d\TH:i') }}" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 custom-calendar">
                </div>
            </div>

            <div class="flex items-center">
                <input id="create_status" type="checkbox" name="status" value="1" checked class="h-4 w-4 rounded bg-slate-950 border-slate-700 text-blue-500">
                <label for="create_status" class="ml-2 text-sm text-slate-300 select-none cursor-pointer">Sotuvda mavjud (Omborda)</label>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-sm font-medium cursor-pointer">Bekor qilish</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium text-white cursor-pointer">Saqlash</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================================== -->
<!-- MODAL 2: TELEFONNI TAHRIRLASH (EDIT) -->
<!-- ========================================================================================== -->
<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-2xl w-full max-w-lg relative z-10 my-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Telefon ma'lumotlarini tahrirlash</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-white cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Kategoriya *</label>
                    <select id="edit_category_id" name="category_id" required class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Model Nomi *</label>
                    <input type="text" id="edit_name" name="name" required class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">IMEI Kodi</label>
                    <input type="text" id="edit_imei" name="imei" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Rangi</label>
                    <input type="text" id="edit_color" name="color" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">RAM</label>
                    <input type="text" id="edit_ram" name="ram" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Xotira (Storage)</label>
                    <input type="text" id="edit_storage" name="storage" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- 🔥 TAHRIRLASH UCHUN NARXLAR VA FOIZ INPUTLARI -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-950/40 p-3 rounded-xl border border-slate-800/80">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Kelgan Narxi *</label>
                    <input type="number" id="edit_cost_price" name="cost_price" required min="0" class="calc-input block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-purple-400 uppercase tracking-wider mb-1.5">Ustama (%)</label>
                    <input type="number" id="edit_margin_percent" name="margin_percent" min="0" class="calc-input block w-full px-3 py-2 bg-slate-950 border border-purple-900/50 rounded-xl text-purple-300 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-emerald-400 uppercase tracking-wider mb-1.5">Sotuv Narxi *</label>
                    <input type="number" id="edit_selling_price" name="selling_price" required min="0" class="calc-input block w-full px-3 py-2 bg-slate-950 border border-emerald-900/50 rounded-xl text-emerald-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <!-- 📅 KALENDAR SANA INPUTI (EDIT) -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Kelgan sana va vaqt *</label>
                <input type="datetime-local" id="edit_arrival_date" name="arrival_date" required class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 custom-calendar">
            </div>

            <div class="flex items-center">
                <input id="edit_status" type="checkbox" name="status" value="1" class="h-4 w-4 rounded bg-slate-950 border-slate-700 text-blue-500">
                <label for="edit_status" class="ml-2 text-sm text-slate-300 select-none cursor-pointer">Sotuvda mavjud (Omborda)</label>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-sm font-medium cursor-pointer">Bekor qilish</button>
                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 rounded-xl text-sm font-medium text-white cursor-pointer">Yangilash</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================================== -->
<!-- STYLING: KALENDAR Ikonkasini Chiroyli Qilish -->
<!-- ========================================================================================== -->
<style>
    /* Standart kalendar tugmasini qorong'u dizaynga moslash */
    ::-webkit-calendar-picker-indicator {
        filter: invert(1) sepia(0) saturate(0) hue-rotate(0deg) opacity(0.6);
        cursor: pointer;
        padding: 2px;
    }

    ::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }
</style>

<!-- ========================================================================================== -->
<!-- JAVASCRIPT: AVTOMATIK HISOB-KITOB VA MODALLAR BOSHQARUVI -->
<!-- ========================================================================================== -->
<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(phone) {
        document.getElementById('editForm').action = `/dashboard/phones/${phone.id}`;

        document.getElementById('edit_category_id').value = phone.category_id;
        document.getElementById('edit_name').value = phone.name;
        document.getElementById('edit_imei').value = phone.imei || '';
        document.getElementById('edit_color').value = phone.color || '';
        document.getElementById('edit_ram').value = phone.ram || '';
        document.getElementById('edit_storage').value = phone.storage || '';
        document.getElementById('edit_cost_price').value = phone.cost_price;
        document.getElementById('edit_selling_price').value = phone.selling_price;
        document.getElementById('edit_margin_percent').value = phone.margin_percent;

        if (phone.formatted_arrival_date) {
            document.getElementById('edit_arrival_date').value = phone.formatted_arrival_date;
        }

        document.getElementById('edit_status').checked = phone.status;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // ==========================================
    // DYNAMIQ AVTOMATIK KALKULYATOR MANTIQI
    // ==========================================
    function initCalculator(prefix) {
        const costInput = document.getElementById(`${prefix}_cost_price`);
        const marginInput = document.getElementById(`${prefix}_margin_percent`);
        const sellingInput = document.getElementById(`${prefix}_selling_price`);

        // Oxirgi marta o'zgartirilgan narx inputini aniqlash uchun flag (sotuv yoki foizni to'g'ri yangilash uchun)
        let lastActiveInput = 'selling';

        marginInput.addEventListener('input', () => {
            lastActiveInput = 'margin';
        });
        sellingInput.addEventListener('input', () => {
            lastActiveInput = 'selling';
        });

        function calculate() {
            const cost = parseFloat(costInput.value) || 0;
            const margin = parseFloat(marginInput.value) || 0;
            const selling = parseFloat(sellingInput.value) || 0;

            if (cost <= 0) return;

            // 1-Holat: Agar foydalanuvchi "Kelgan narx" yoki "Sotuv narxi"ni o'zgartirsa -> FOIZNI hisoblaymiz
            if (lastActiveInput === 'selling') {
                const profit = selling - cost;
                marginInput.value = Math.round((profit / cost) * 100);
            }
            // 2-Holat: Agar foydalanuvchi "Foiz"ni o'zgartirsa -> SOTUV NARXINI hisoblaymiz
            else if (lastActiveInput === 'margin') {
                const calculatedSelling = cost + (cost * (margin / 100));
                sellingInput.value = Math.round(calculatedSelling);
            }
        }

        // Barcha tegishli inputlarga "input" hodisasini bog'laymiz
        costInput.addEventListener('input', calculate);
        marginInput.addEventListener('input', calculate);
        sellingInput.addEventListener('input', calculate);
    }

    // Ikkala modal uchun ham kalkulyatorlarni ishga tushiramiz
    initCalculator('create');
    initCalculator('edit');
</script>
@endsection