@extends('layouts.app')

@section('title', 'Kategoriyalar - SmartStore')

@section('content')
@if(session('success'))
<div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-sm flex items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-check"></i> <span>{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl text-sm flex items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-exclamation"></i> <span>{{ session('error') }}</span>
</div>
@endif
@if($errors->any())
<div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl text-sm mb-4">
    <ul class="list-disc pl-5">
        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
    </ul>
</div>
@endif

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Kategoriyalar boshqaruvi</h1>
        <p class="text-sm text-slate-400">Telefonlar uchun brend va turkumlar ro'yxati</p>
    </div>
    <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-500 text-white font-medium py-2.5 px-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all cursor-pointer text-sm flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Yangi kategoriya
    </button>
</div>

<div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-800/50 text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-800">
                <th class="p-4 w-16">ID</th>
                <th class="p-4">Kategoriya nomi</th>
                <th class="p-4 w-32">Status</th>
                <th class="p-4 w-32 text-right">Amallar</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/60 text-sm text-slate-300">
            @forelse($categories as $category)
            <tr class="hover:bg-slate-800/30 transition-colors">
                <td class="p-4 font-mono text-slate-500">#{{ $category->id }}</td>
                <td class="p-4 font-semibold text-white">{{ $category->name }}</td>
                <td class="p-4">
                    @if($category->status)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Faol</span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">Nofaol</span>
                    @endif
                </td>
                <td class="p-4 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', {{ $category->status ? 'true' : 'false' }})"
                            class="p-2 bg-slate-800 hover:bg-amber-500/20 hover:text-amber-400 rounded-lg border border-slate-700 transition-colors cursor-pointer">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </button>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('O‘chirilsinmi?')" class="inline">
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
                <td colspan="4" class="p-8 text-center text-slate-500">Kategoriyalar hali qo'shilmagan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeCreateModal()"></div>
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-2xl w-full max-w-md relative z-10">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Yangi kategoriya qo'shish</h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-white cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kategoriya nomi</label>
                <input type="text" name="name" required placeholder="Apple, Samsung..."
                    class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-center">
                <input id="create_status" type="checkbox" name="status" value="1" checked class="h-4 w-4 rounded bg-slate-950 border-slate-700 text-blue-500">
                <label for="create_status" class="ml-2 text-sm text-slate-300 select-none cursor-pointer">Faol holatda yaratish</label>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-sm font-medium cursor-pointer">Bekor qilish</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium text-white cursor-pointer">Saqlash</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-2xl w-full max-w-md relative z-10">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Kategoriyani tahrirlash</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-white cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kategoriya nomi</label>
                <input type="text" id="edit_name" name="name" required
                    class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-center">
                <input id="edit_status" type="checkbox" name="status" value="1" class="h-4 w-4 rounded bg-slate-950 border-slate-700 text-blue-500">
                <label for="edit_status" class="ml-2 text-sm text-slate-300 select-none cursor-pointer">Faol (Aktiv)</label>
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
    define_close = () => {
        document.getElementById('createModal').classList.add('hidden');
    }

    function closeCreateModal() {
        define_close();
    }

    function openEditModal(id, name, status) {
        document.getElementById('editForm').action = `/dashboard/categories/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_status').checked = status;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection