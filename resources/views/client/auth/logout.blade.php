<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déconnexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta http-equiv="refresh" content="3;url={{ route('client.login') }}">
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-400 to-cyan-500 flex items-center justify-center px-4">
    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full text-center">
        <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-800 mb-2">Déconnexion réussie</h1>
        <p class="text-gray-400 text-sm mb-6">À bientôt, vous allez être redirigé automatiquement.</p>

        {{-- Barre de progression --}}
        <div class="w-full bg-gray-100 rounded-full h-1.5 mb-4 overflow-hidden">
            <div id="progress" class="h-1.5 bg-gradient-to-r from-sky-400 to-cyan-500 rounded-full transition-all duration-100"
                style="width: 100%"></div>
        </div>

        <a href="{{ route('client.login') }}"
            class="text-sky-500 hover:text-sky-700 text-sm font-medium transition">
            Se connecter maintenant →
        </a>
    </div>

    <script>
        let width = 100;
        const bar = document.getElementById('progress');
        const timer = setInterval(() => {
            width -= 100 / 30; // 3 secondes = 30 ticks de 100ms
            bar.style.width = Math.max(0, width) + '%';
            if (width <= 0) clearInterval(timer);
        }, 100);
    </script>
</body>
</html>