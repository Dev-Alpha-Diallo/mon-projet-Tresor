<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trésorerie Pro')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        'primary-dark': '#4338CA',
                    }
                }
            }
        }
    </script>
    <style>
        html, body {
            height: 100vh;
            overflow: hidden;
        }
        .content-wrapper {
            height: calc(100vh - 56px - 40px - 36px); /* hauteur totale - navbar - footer - messages */
            overflow-y: auto;
        }
        .main-content {
            min-height: 0;
        }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-blue-500 to-gray-100">
    <!-- Navigation minimaliste -->
    <nav class="bg-white/80 backdrop-blur-md border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-14">
               <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <!-- Image du logo -->
                <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo Trésorerie" class="w-full h-full object-cover">
                </div>
                <span class="text-lg font-bold text-gray-800 group-hover:text-primary transition">Trésorerie Et Gestion des Paiements</span>
            </a>


                <!-- Menu desktop -->
                <div class="hidden md:flex items-center space-x-1">
                    @php
                        $navItems = [
                            ['route' => 'dashboard', 'icon' => '📊', 'label' => 'Dashboard'],
                            ['route' => 'etudiants.index', 'icon' => '👨‍🎓', 'label' => 'Étudiants'],
                            ['route' => 'paiements.index', 'icon' => '💳', 'label' => 'Paiements'],
                            ['route' => 'maisons.index', 'icon' => '🏠', 'label' => 'Maisons'],
                            ['route' => 'bailleurs.index', 'icon' => '👨‍💼', 'label' => 'Bailleurs'],
                            ['route' => 'rapports.index', 'icon' => '📈', 'label' => 'Rapports'],
                        ];
                    @endphp
                    
                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" 
                           class="px-4 py-2 rounded-lg text-gray-600 hover:text-primary hover:bg-gray-50 transition flex items-center space-x-2">
                            <span>{{ $item['icon'] }}</span>
                            <span class="font-medium">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </nav>

    <!-- Messages Flash -->
    <div class="max-w-7xl mx-auto px-4 py-1">
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 text-green-700 px-4 py-2 rounded-lg text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-sm">
                ⚠️ {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Contenu principal avec wrapper pour scroll interne -->
    <div class="content-wrapper">
        <main class="max-w-7xl mx-auto px-4 py-2 main-content">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="max-w-7xl mx-auto px-4 py-2 text-center text-gray-500 text-xs border-t mt-2">
            © {{ date('Y') }} Trésorerie CET • Simplifiez votre gestion
        </footer>
    </div>
</body>
</html>