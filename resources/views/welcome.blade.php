<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion Électricien - Accueil</title>
    @vite('resources/css/app.css')
</head>
<body class="antialiased bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <!-- Logo ou Texte -->
                        <span class="text-xl font-semibold text-emerald-600">GestionÉlectricien</span>
                    </div>
                    
                    <!-- Boutons de connexion/inscription -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" 
                               class="text-gray-600 hover:text-emerald-600 transition-colors">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="text-gray-600 hover:text-emerald-600 transition-colors">
                                    Déconnexion
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" 
                               class="text-gray-600 hover:text-emerald-600 transition-colors">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" 
                               class="bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600 transition-colors">
                                Inscription
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <main class="flex-grow">
            <!-- Section Connexion/Bienvenue -->
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <!-- Message de bienvenue -->
                    <div class="space-y-6">
                        <h1 class="text-4xl font-bold text-gray-800">
                            Plateforme de gestion pour électriciens
                        </h1>
                        <p class="text-lg text-gray-600">
                            Connectez-vous pour accéder à vos projets et gérer vos échanges d'électricité.
                        </p>
                    </div>

                    <!-- Formulaire de connexion rapide -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Connexion rapide</h2>
                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                <input type="password" name="password" id="password" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <button type="submit"
                                    class="w-full bg-emerald-500 text-white py-2 px-4 rounded-lg hover:bg-emerald-600 transition-colors">
                                Se connecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Section Offres Récentes -->
            <div class="bg-white py-12">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Offres récentes</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Exemple d'offre 1 -->
                        <div class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-100">
                            <div class="text-emerald-600 font-semibold mb-2">Offre d'échange</div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Échange 100kW</h3>
                            <p class="text-gray-600 mb-4">Disponible pour échange immédiat. Conditions avantageuses.</p>
                            <div class="text-sm text-gray-500">Prix: 0.15€/kWh</div>
                        </div>

                        <!-- Exemple d'offre 2 -->
                        <div class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-100">
                            <div class="text-emerald-600 font-semibold mb-2">Demande d'échange</div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Recherche 50kW</h3>
                            <p class="text-gray-600 mb-4">Besoin urgent pour projet industriel.</p>
                            <div class="text-sm text-gray-500">Prix proposé: 0.18€/kWh</div>
                        </div>

                        <!-- Exemple d'offre 3 -->
                        <div class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-100">
                            <div class="text-emerald-600 font-semibold mb-2">Offre spéciale</div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Forfait 75kW</h3>
                            <p class="text-gray-600 mb-4">Offre limitée pour projet résidentiel.</p>
                            <div class="text-sm text-gray-500">Prix: 0.12€/kWh</div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 py-6">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} GestionÉlectricien. Tous droits réservés.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>