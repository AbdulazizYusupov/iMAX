@extends('layouts.app')

@section('content')
<div class="p-6 bg-slate-950 min-h-screen text-slate-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-white">Ombordagi Telefonlar</h2>
        <button onclick="openAddModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-semibold transition cursor-pointer">
            + Yangi qo'shish
        </button>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm">
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-4 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-sm">
        {{ $errors->first() }}
    </div>
    @endif

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950 border-b border-slate-800 text-xs font-bold uppercase text-slate-400 tracking-wider">
                        <th class="p-4">Model & Kategoriya</th>
                        <th class="p-4">Xarakteristika</th>
                        <th class="p-4">Narxlar</th>
                        <th class="p-4 text-center">Ustama</th>
                        <th class="p-4">Sana</th>
                        <th class="p-4">Zaxira (Ombor)</th>
                        <th class="p-4 text-right">Amallar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm">
                    @forelse($phones as $phone)
                    <tr class="hover:bg-slate-850/40 transition">
                        <td class="p-4">
                            <div class="font-semibold text-white">{{ $phone->name }}</div>
                            <div class="text-xs text-slate-500">{{ $phone->category->name ?? 'Kategoriyasiz' }}</div>
                        </td>
                        <td class="p-4">
                            <div class="text-xs text-slate-300">RAM/ROM: {{ $phone->ram ?: '-' }} / {{ $phone->storage ?: '-' }}</div>
                            <div class="text-xs text-slate-500">IMEI: {{ $phone->imei ?: '-' }}</div>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-emerald-400">{{ number_format($phone->selling_price, 0, '.', ' ') }} so'm</div>
                            <div class="text-xs text-slate-500">K: {{ number_format($phone->cost_price, 0, '.', ' ') }}</div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-lg text-xs font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                +{{ number_format($phone->margin_percent, 0) }}%
                            </span>
                        </td>
                        <td class="p-4 text-xs text-slate-400">
                            {{ $phone->arrival_date->format('d.m.Y H:i') }}
                        </td>
                        <td class="p-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-slate-200">{{ $phone->current_stock }} / {{ $phone->quantity }} d.</div>
                            <div class="text-xs text-slate-500">Sotildi: {{ $phone->sold_quantity }} ta</div>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="openEditModal({{ json_encode($phone) }})" class="p-2 bg-amber-500/10 text-amber-400 hover:bg-amber-500/20 rounded-xl transition cursor-pointer">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                <form action="{{ route('phones.destroy', $phone->id) }}" method="POST" onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 rounded-xl transition cursor-pointer">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500">Omborda telefonlar mavjud emas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($phones->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $phones->links() }}
        </div>
        @endif
    </div>
</div>

<div id="phoneModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-2xl w-full max-w-lg relative z-10 my-8">

        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-lg font-bold text-white">Yangi telefon qo'shish</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="phoneForm" method="POST" class="space-y-4">
            @csrf
            <div id="methodField"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Kategoriya *</label>
                    <select id="f_category_id" name="category_id" required class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Model Nomi *</label>
                    <input type="text" id="f_name" name="name" required class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">IMEI Kodi</label>
                    <input type="text" id="f_imei" name="imei" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Rangi</label>
                    <input type="text" id="f_color" name="color" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">RAM</label>
                    <input type="text" id="f_ram" name="ram" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Xotira</label>
                    <input type="text" id="f_storage" name="storage" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-blue-400 uppercase tracking-wider mb-1.5">Miqdori *</label>
                    <input type="number" id="f_quantity" name="quantity" required min="1" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-950/40 p-3 rounded-xl border border-slate-800/80">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Kelgan Narxi *</label>
                    <input type="number" id="f_cost_price" name="cost_price" required min="0" class="calc-input block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-purple-400 uppercase tracking-wider mb-1.5">Ustama (%)</label>
                    <input type="number" id="f_margin_percent" name="margin_percent" min="0" class="calc-input block w-full px-3 py-2 bg-slate-950 border border-purple-900/50 rounded-xl text-purple-300 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-emerald-400 uppercase tracking-wider mb-1.5">Sotuv Narxi *</label>
                    <input type="number" id="f_selling_price" name="selling_price" required min="0" class="calc-input block w-full px-3 py-2 bg-slate-950 border border-emerald-900/50 rounded-xl text-emerald-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Kelgan sana va vaqt *</label>
                <input type="datetime-local" id="f_arrival_date" name="arrival_date" required class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center">
                <input id="f_status" type="checkbox" name="status" value="1" class="h-4 w-4 rounded bg-slate-950 border-slate-700 text-blue-500">
                <label for="f_status" class="ml-2 text-sm text-slate-300 select-none cursor-pointer">Sotuvda mavjud</label>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-sm font-medium text-slate-300 cursor-pointer">Bekor qilish</button>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium text-white cursor-pointer">Saqlash</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('phoneModal');
    const form = document.getElementById('phoneForm');

    // Auto calculate margin and prices
    document.querySelectorAll('.calc-input').forEach(input => {
        input.addEventListener('input', function() {
            const cost = parseFloat(document.getElementById('f_cost_price').value) || 0;
            if (this.id === 'f_margin_percent' && cost > 0) {
                const margin = parseFloat(this.value) || 0;
                document.getElementById('f_selling_price').value = Math.round(cost + (cost * margin / 100));
            } else if (this.id === 'f_selling_price' && cost > 0) {
                const selling = parseFloat(this.value) || 0;
                document.getElementById('f_margin_percent').value = Math.round(((selling - cost) / cost) * 100);
            }
        });
    });

    function openAddModal() {
        form.reset();
        form.action = "{{ route('phones.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('modalTitle').innerText = "Yangi telefon qo'shish";
        document.getElementById('submitBtn').className = "px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium text-white cursor-pointer";
        document.getElementById('submitBtn').innerText = "Saqlash";

        // Sana maydoniga avtomatik joriy vaqtni yozish
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('f_arrival_date').value = now.toISOString().slice(0, 16);
        document.getElementById('f_status').checked = true;

        modal.classList.remove('hidden');
    }

    function openEditModal(phone) {
        form.reset();
        form.action = `/dashboard/phones/${phone.id}`;
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        document.getElementById('modalTitle').innerText = "Telefon ma'lumotlarini tahrirlash";
        document.getElementById('submitBtn').className = "px-4 py-2 bg-amber-600 hover:bg-amber-500 rounded-xl text-sm font-medium text-white cursor-pointer";
        document.getElementById('submitBtn').innerText = "Yangilash";

        // Set Values
        document.getElementById('f_category_id').value = phone.category_id;
        document.getElementById('f_name').value = phone.name;
        document.getElementById('f_imei').value = phone.imei || '';
        document.getElementById('f_color').value = phone.color || '';
        document.getElementById('f_ram').value = phone.ram || '';
        document.getElementById('f_storage').value = phone.storage || '';
        document.getElementById('f_quantity').value = phone.quantity || 1;
        document.getElementById('f_cost_price').value = parseInt(phone.cost_price);
        document.getElementById('f_selling_price').value = parseInt(phone.selling_price);
        document.getElementById('f_margin_percent').value = parseInt(phone.margin_percent) || 0;

        if (phone.arrival_date) {
            document.getElementById('f_arrival_date').value = phone.arrival_date.substring(0, 16);
        }
        document.getElementById('f_status').checked = phone.status == 1;

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>
@endsection