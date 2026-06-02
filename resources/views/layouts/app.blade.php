<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartStore CRM')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen flex">

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 border-r border-slate-800 transform -translate-x-full md:translate-x-0 transition-transform duration-350 ease-in-out flex flex-col justify-between">

        <div>
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-400 flex items-center justify-center text-white shadow-md">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </div>
                    <span class="text-lg font-extrabold tracking-tight bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">SmartStore</span>
                </div>
                <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <nav class="p-4 space-y-1.5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/10' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="fa-solid fa-chart-pie text-base w-5 text-center"></i>
                    <span>Asosiy Panel</span>
                </a>

                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/10' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="fa-solid fa-layer-group text-base w-5 text-center"></i>
                    <span>Kategoriyalar</span>
                </a>

                <a href="{{ route('phones.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('phones.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/10' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="fa-solid fa-mobile-screen text-base w-5 text-center"></i>
                    <span>Telefonlar</span>
                </a>
                <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('expenses.*') ? 'bg-red-600 text-white shadow-lg shadow-red-500/10' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="fa-solid fa-wallet text-base w-5 text-center"></i>
                    <span>Xarajatlar</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-900/50">
            <div class="flex items-center justify-between">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 overflow-hidden group/prof cursor-pointer flex-1 mr-2" title="Profil sozlamalari">
                    <div class="w-9 h-9 rounded-xl bg-slate-800 group-hover/prof:bg-blue-600 group-hover/prof:text-white flex items-center justify-center text-slate-300 font-bold shrink-0 transition-colors">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-semibold text-slate-200 group-hover/prof:text-white truncate transition-colors">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-500 group-hover/prof:text-slate-400 truncate transition-colors">@ {{ Auth::user()->username }}</p>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="inline shrink-0">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors cursor-pointer" title="Chiqish">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 z-30 bg-slate-950/60 backdrop-blur-sm hidden md:hidden"></div>

    <div class="flex-1 md:pl-64 flex flex-col min-h-screen">

        <header class="h-16 bg-slate-900 border-b border-slate-800 px-6 flex items-center justify-between md:justify-end shrink-0 shadow-md">
            <button onclick="toggleSidebar()" class="md:hidden text-slate-300 hover:text-white p-2 bg-slate-800 rounded-xl border border-slate-700 cursor-pointer">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>

            <div class="md:hidden flex items-center gap-2">
                <span class="text-sm font-bold tracking-tight text-white">SmartStore CRM</span>
            </div>

            <div class="text-xs text-slate-400 hidden sm:block">
                <i class="fa-regular fa-calendar mr-1"></i> {{ date('d.m.Y') }}
            </div>
        </header>

        <main class="flex-1 p-4 md:p-6 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>

        <footer class="bg-slate-950/40 border-t border-slate-900 py-4 text-center text-xs text-slate-500 shrink-0">
            &copy; {{ date('Y') }} SmartStore CRM. Barcha huquqlar himoyalangan.
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>

</html>