<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Étudiant – @yield('title', 'Tableau de bord')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-gradient-to-r from-sky-400 to-cyan-500 text-white shadow-lg">
        <div class="px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-sm leading-tight">C.E.T</p>
                    <p class="text-white text-xs leading-tight">Gestion De L'Amicale</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('client.profil.index') }}"
                    class="flex items-center gap-1.5 text-sm bg-white/15 hover:bg-gradient-to-br from-emerald-400 to-teal-500 px-3 py-1.5 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="hidden sm:inline text-xs">{{ Auth::user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('client.logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-1.5 text-sm bg-white/15 hover:bg-red-500 px-3 py-1.5 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="hidden sm:inline text-xs">Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Nav tabs --}}
        <div class="px-4 pb-3 flex items-center gap-2">
            <a href="{{ route('client.dashboard') }}"
                class="flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg transition
                {{ request()->routeIs('client.dashboard') ? 'bg-white text-sky-600 font-semibold shadow-sm' : 'bg-white/15 hover:bg-gradient-to-br from-emerald-400 to-teal-500 px-3 py-1.5 rounded-xl transition' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Tableau de bord
            </a>

            <a href="{{ route('client.paiement.wave') }}"
                    class="flex items-center gap-1.5 text-xs px-4 py-2 rounded-xl transition font-bold shadow-md
                    {{ request()->routeIs('client.paiement.*') 
                        ? 'bg-white text-sky-600 shadow-lg scale-105' 
                        : 'bg-gradient-to-r from-yellow-400 to-orange-400 text-white hover:from-yellow-500 hover:to-orange-500' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    💳 Payer
            </a>
        </div>
    </nav>

             

    <main class="px-4 py-6 max-w-2xl mx-auto">
        @yield('content')
    </main>

</body>
</html>