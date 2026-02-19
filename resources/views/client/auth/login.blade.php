<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Étudiant – Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center px-4">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">C.E.T</h1>
            <p class="text-blue-200 text-sm mt-1">Gestion De L'Amicale</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-1">Espace Étudiant</h2>
            <p class="text-gray-400 text-sm mb-6">Connectez-vous pour accéder à votre espace</p>

            {{-- Erreur rôle --}}
            @if ($errors->has('role'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                    {{ $errors->first('role') }}
                </div>
            @endif

            <form method="POST" action="{{ route('client.login.post') }}" class="space-y-4">
                @csrf

                {{-- Téléphone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Numéro de téléphone
                    </label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-gray-400 text-sm">|</span>
                        </div>
                        <input type="tel" name="telephone"
                            value="{{ old('telephone') }}"
                            required autofocus
                            placeholder="77 123 45 67"
                            class="w-full pl-12 pr-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition
                                {{ $errors->has('telephone') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50' }}">
                    </div>
                    @error('telephone')
                        <div id="error-box" class="mt-1">
                            <p id="error-message" class="text-xs text-red-600">{{ $message }}</p>
                            @if(str_contains($message, 'secondes'))
                                @php preg_match('/\d+/', $message, $matches); $seconds = $matches[0] ?? 60; @endphp
                                <p class="text-xs text-red-500 mt-1">
                                    Déblocage dans : <span id="countdown" class="font-bold">{{ $seconds }}</span>s
                                </p>
                                <script>
                                    let seconds = {{ $seconds }};
                                    const countdown = document.getElementById('countdown');
                                    const submitBtn = document.getElementById('submit-btn');
                                    if (submitBtn) submitBtn.disabled = true;
                                    const timer = setInterval(() => {
                                        seconds--;
                                        if (countdown) countdown.textContent = seconds;
                                        if (seconds <= 0) {
                                            clearInterval(timer);
                                            if (submitBtn) {
                                                submitBtn.disabled = false;
                                                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                                            }
                                        }
                                    }, 1000);
                                </script>
                            @endif
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Mot de passe
                    </label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 pr-12 border border-gray-200 bg-gray-50 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2">
                    <input id="remember" type="checkbox" name="remember"
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    <label for="remember" class="text-sm text-gray-500">Se souvenir de moi</label>
                </div>

                {{-- Submit --}}
                <button type="submit" id="submit-btn"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800
                           disabled:opacity-50 disabled:cursor-not-allowed
                           text-white font-semibold py-3 rounded-xl transition shadow-md hover:shadow-lg">
                    Se connecter
                </button>

            </form>
        </div>

        <p class="text-center text-xs text-blue-200 mt-6">
            Problème de connexion ? Contactez l'administration.
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>
</html>