<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trésorerie C.E.T')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#EEF2FF',
                            100: '#E0E7FF',
                            500: '#6366F1',
                            600: '#4F46E5',
                            700: '#4338CA',
                            800: '#3730A3',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        html, body { height: 100vh; overflow: hidden; }
        .content-wrapper { height: calc(100vh - 64px); overflow-y: auto; scroll-behavior: smooth; }
        .content-wrapper::-webkit-scrollbar { width: 8px; }
        .content-wrapper::-webkit-scrollbar-track { background: #5da6ef; }
        .content-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .content-wrapper::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .nav-link { position: relative; transition: all 0.3s ease; }
        .nav-link::after { content: ''; position: absolute; bottom: -2px; left: 50%; width: 0; height: 2px; background: currentColor; transition: all 0.3s ease; transform: translateX(-50%); }
        .nav-link:hover::after, .nav-link.active::after { width: 80%; }
        .glass-effect { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        @keyframes slide-in { from { transform: translateY(-100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .animate-slide-in { animation: slide-in 0.3s ease-out; }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-slate-50 via-blue-500 to-indigo-100">
    
    <!-- Navigation -->
    <nav class="glass-effect border-b border-gray-200/50 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                        <div class="relative">
                            <img src="{{ asset('images/logo.jpeg') }}" 
                                alt="Logo"
                                class="w-9 h-9 object-cover rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
                        </div>
                        <div class="hidden sm:flex flex-col">
                            <h1 class="text-base font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent leading-tight">C.E.T</h1>
                            <p class="text-xs text-gray-900 -mt-0.5">Gestion De L'Amicale</p>
                        </div>
                    </a>
                </div>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center space-x-0.5 flex-1 justify-center">
                    @php
                        $navItems = [
                            ['route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Tableau'],
                            ['route' => 'etudiants.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Étudiants'],
                            ['route' => 'paiements.index', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'label' => 'Paiements'],
                            ['route' => 'maisons.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Maisons'],
                            ['route' => 'bailleurs.index', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Bailleurs'],
                            ['route'=> 'paiements-bailleurs.index', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'P.bailleurs'],
                            ['route' => 'rapports.index', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Rapports'],
                        ];
                    @endphp
                    
                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" class="nav-link text-gray-600 hover:text-indigo-600 px-2 py-2 rounded-lg transition-all duration-200 flex items-center space-x-1 font-medium text-xs whitespace-nowrap">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                            <span class="hidden lg:inline">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-2">
                    <div class="relative group">
                        <button class="p-2 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('etudiants.create') }}" class="block px-3 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-t-lg">+ Étudiant</a>
                            <a href="{{ route('paiements.create') }}" class="block px-3 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">+ Paiement</a>
                            <a href="{{ route('maisons.create') }}" class="block px-3 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-b-lg">+ Maison</a>
                        </div>
                    </div>

                    <div class="relative group">
                        <button class="flex items-center space-x-1.5 px-2.5 py-1.5 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
                            <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium hidden sm:inline">Trésorier</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-36 bg-white rounded-lg shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg border-b border-gray-200">
        <div class="px-4 py-3 space-y-2">
            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => 'Tableau de bord'],
                    ['route' => 'etudiants.index', 'label' => 'Étudiants'],
                    ['route' => 'paiements.index', 'label' => 'Paiements'],
                    ['route' => 'maisons.index', 'label' => 'Maisons'],
                    ['route' => 'bailleurs.index', 'label' => 'Bailleurs'],
                    ['route'=> 'paiements-bailleurs.index', 'label' => 'Paiements bailleurs'],
                    ['route' => 'rapports.index', 'label' => 'Rapports'],
                ];
            @endphp
            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium text-sm">
                    {{ $item['label'] }}
                </a>
            @endforeach
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 font-medium text-sm">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success') || session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        @if(session('success'))
            <div class="glass-effect border-l-4 border-green-500 p-4 rounded-lg shadow-lg animate-slide-in">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="glass-effect border-l-4 border-red-500 p-4 rounded-lg shadow-lg animate-slide-in">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif
    </div>
    @endif

    <!-- Contenu -->
    <div class="content-wrapper">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 mt-12">
            <div class="glass-effect rounded-2xl p-6 border border-gray-200/50">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center space-x-2 mb-4 md:mb-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">© {{ date('Y') }} ALPHA OUMAR DIALLO  Trésorier C.E.T </span>
                    </div>
                    <div class="flex items-center space-x-6 text-xs text-gray-500">
                        <span class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            Système opérationnel
                        </span>
                        <span>Version 2.0</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
    const btn = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
    </script>
</body>
</html>