@extends('layouts.app')

@section('title', 'Xarajatlar - SmartStore')

@section('content')
@if(session('success'))
<div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-sm flex items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-check"></i> <span>{{ session('success') }}</span>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center mb-6">
    <div class="md:col-span-2">
        <h1 class="text-2xl font-bold text-white">Xarajatlar hisoboti</h1>
        <p class="text-sm text-slate-400">Do'kon uchun qilingan barcha to'lovlar va xarajatlar nazorati</p>
    </div>
    <div class="flex justify-end gap-3">
        <div class="bg-red-500/10 border border-red-500/20 px-4 py-2 rounded-xl text-right">
            <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Jami Xarajat</span>
            <p class="text-lg font-black text-red-400">{{ number_format($total_expenses, 0, '.', ' ') }} so'm</p>
        </div>
        <button onclick="openCreateModal()" class="bg-red-600 hover:bg-red-500 text-white font-medium py-2 px-4 rounded-xl shadow-lg shadow-red-500/20 transition-all cursor-pointer text-sm flex items-center gap-2">
            <i class="fa-solid fa-minus-circle"></i> Xarajat qo'shish
        </button>
    </div>
</div>

<div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-slate-800/50 text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-800">
                    <th class="p-4 w-16">ID</th>
                    <th class="p-4">Xarajat Nomi</th>
                    <th class="p-4">Batafsil Izoh (Nimaga sarflandi)</th>
                    <th class="p-4">Summasi</th>
                    <th class="p-4">Sana va Vaqt</th>
                    <th class="p-4 text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-sm text-slate-300">
                @forelse($expenses as $expense)
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="p-4 font-mono text-slate-500">#{{ $expense->id }}</td>
                    <td class="p-4 font-bold text-white">{{ $expense->title }}</td>
                    <td class="p-4 text-slate-400 max-w-xs truncate" title="{{ $expense->description }}">
                        {{ $expense->description ?? '-' }}
                    </td>
                    <td class="p-4 font-bold text-red-400 text-base">
                        {{ number_format($expense->amount, 0, '.', ' ') }} so'm
                    </td>
                    <td class="p-4 text-xs text-slate-400">
                        {{ $expense->expense_date->format('d.m.Y H:i') }}
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="openEditModal({{ json_encode($expense) }})"
                                class="p-2 bg-slate-800 hover:bg-amber-500/20 hover:text-amber-400 rounded-lg border border-slate-700 transition-colors cursor-pointer">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Ushbu xarajatni o‘chirmoqchimisiz?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-slate-800 hover:bg-red-500/20 hover:text-red-400 rounded-lg border border-slate-700 transition-colors cursor-pointer">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-12 text-center text-slate-500">
                        <i class="fa-solid fa-wallet text-3xl mb-2 text-slate-700"></i>
                        <p>Xarajatlar mavjud emas.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeCreateModal()"></div>
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-2xl w-full max-w-md relative z-10">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Yangi xarajat qo'shish</h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-white cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Xarajat nomi *</label>
                <input type="text" name="title" required placeholder="Masalan: Ijara to'lovi, Oylik, Reklama..." class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Summasi (so'mda) *</label>
                <input type="number" name="amount" required min="0" placeholder="0" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Sana va vaqt *</label>
                <input type="datetime-local" name="expense_date" required value="{{ date('Y-m-d\TH:i') }}" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Batafsil izoh</label>
                <textarea name="description" rows="3" placeholder="Xarajat haqida qo'shimcha ma'lumot..." class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-sm font-medium cursor-pointer">Bekor qilish</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-xl text-sm font-medium text-white cursor-pointer">Saqlash</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-2xl w-full max-w-md relative z-10">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Xarajatni tahrirlash</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-white cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Xarajat nomi *</label>
                <input type="text" id="edit_title" name="title" required class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Summasi (so'mda) *</label>
                <input type="number" id="edit_amount" name="amount" required min="0" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Sana va vaqt *</label>
                <input type="datetime-local" id="edit_expense_date" name="expense_date" required class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Batafsil izoh</label>
                <textarea id="edit_description" name="description" rows="3" class="block w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-sm font-medium cursor-pointer">Bekor qilish</button>
                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 rounded-xl text-sm font-medium text-white cursor-pointer">Yangilash</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(expense) {
        document.getElementById('editForm').action = `/dashboard/expenses/${expense.id}`;
        document.getElementById('edit_title').value = expense.title;
        document.getElementById('edit_amount').value = expense.amount;
        document.getElementById('edit_description').value = expense.description || '';

        if (expense.formatted_expense_date) {
            document.getElementById('edit_expense_date').value = expense.formatted_expense_date;
        }

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection