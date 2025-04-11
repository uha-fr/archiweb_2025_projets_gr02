@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-primary-600">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Énergie renouvelable">
            <div class="absolute inset-0 bg-primary-600 mix-blend-multiply"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Échange d'Électricité</h1>
            <p class="mt-6 max-w-3xl text-xl text-primary-100">
                Plateforme d'échange d'électricité entre particuliers et entreprises. Vendez votre surplus d'électricité ou achetez de l'électricité à un prix avantageux.
            </p>
            <div class="mt-10 flex space-x-4">
                <a href="{{ Auth::check() ? route('dashboard') : route('register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-primary-700 bg-white hover:bg-primary-50">
                    Commencer
                </a>
                <a href="#how-it-works" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-700 bg-opacity-60 hover:bg-opacity-70">
                    Comment ça marche
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-12 bg-white" id="how-it-works">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-primary-600 font-semibold tracking-wide uppercase">Comment ça marche</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Échangez de l'électricité en toute simplicité
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Notre plateforme facilite l'échange d'électricité entre particuliers et entreprises.
                </p>
            </div>

            <div class="mt-10">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Créez une offre</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Publiez une offre de vente ou une demande d'achat d'électricité en spécifiant la quantité, le prix et la période.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Trouvez des correspondances</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Parcourez les offres disponibles et trouvez celles qui correspondent à vos besoins en termes de quantité, prix et période.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Concluez des contrats</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Créez un contrat avec le vendeur ou l'acheteur et effectuez la transaction en toute sécurité via notre plateforme.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-primary-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:py-16 sm:px-6 lg:px-8 lg:py-20">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    Échangez de l'électricité en toute confiance
                </h2>
                <p class="mt-3 text-xl text-primary-200 sm:mt-4">
                    Notre plateforme facilite les échanges d'électricité entre particuliers et entreprises.
                </p>
            </div>
            <dl class="mt-10 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8">
                <div class="flex flex-col">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-primary-200">
                        Utilisateurs
                    </dt>
                    <dd class="order-1 text-5xl font-extrabold text-white">
                        1,000+
                    </dd>
                </div>
                <div class="flex flex-col mt-10 sm:mt-0">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-primary-200">
                        Offres
                    </dt>
                    <dd class="order-1 text-5xl font-extrabold text-white">
                        5,000+
                    </dd>
                </div>
                <div class="flex flex-col mt-10 sm:mt-0">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-primary-200">
                        Contrats
                    </dt>
                    <dd class="order-1 text-5xl font-extrabold text-white">
                        3,000+
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                <span class="block">Prêt à commencer?</span>
                <span class="block text-primary-600">Inscrivez-vous gratuitement dès aujourd'hui.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                @auth
                    <div class="inline-flex rounded-md shadow">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                            Tableau de bord
                        </a>
                    </div>
                @else
                    <div class="inline-flex rounded-md shadow">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                            S'inscrire
                        </a>
                    </div>
                    <div class="ml-3 inline-flex rounded-md shadow">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-primary-50">
                            Se connecter
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection
