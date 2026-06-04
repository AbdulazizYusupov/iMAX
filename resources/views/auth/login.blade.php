<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPhone Store - Tizimga kirish</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-900 flex items-center justify-center h-screen font-sans overflow-hidden antialiased">

    <div class="absolute w-[500px] h-[500px] bg-blue-600/20 blur-[120px] rounded-full -top-40 -left-40 pointer-events-none"></div>
    <div class="absolute w-[400px] h-[400px] bg-cyan-500/10 blur-[100px] rounded-full -bottom-20 -right-20 pointer-events-none"></div>

    <div class="bg-slate-800/80 backdrop-blur-xl border border-slate-700/50 p-8 rounded-2xl shadow-2xl w-full max-w-md relative z-10 mx-4">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tr from-blue-600 to-cyan-400 text-white text-2xl mb-4 shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-mobile-screen-button animate-bounce"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-white tracking-tight">IQuva</h2>
            <p class="text-sm text-slate-400 mt-2">Boshqaruv paneliga kirish</p>
        </div>

        @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-3 rounded-xl mb-6 text-sm flex items-start gap-2">
            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
            <div>
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Foydalanuvchi nomi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-user text-sm"></i>
                    </div>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="admin"
                        class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Parol</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </div>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>
            </div>

            <div class="flex items-center justify-between pt-1">
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember"
                        class="h-4 w-4 bg-slate-900 border-slate-700 rounded text-blue-500 focus:ring-blue-500/30 focus:ring-offset-slate-800 cursor-pointer">
                    <label for="remember" class="ml-2 block text-sm text-slate-400 select-none cursor-pointer hover:text-slate-200 transition-colors">Meni eslab qol</label>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/20 active:scale-[0.98] transition-all duration-150 cursor-pointer flex items-center justify-center gap-2">
                    <span>Tizimga kirish</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </form>

        <div class="text-center mt-8 pt-6 border-t border-slate-700/40">
            <p class="text-xs text-slate-500">&copy; {{ date('Y') }} IQuva CRM. Barcha huquqlar himoyalangan.</p>
        </div>
    </div>

</body>

</html>