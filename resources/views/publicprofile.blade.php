@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Profil utilisateur
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Informations générales
                    </p>
                </div>
                
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-24 w-24">
                            <img class="h-24 w-24 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ $user->role == 'individual' ? 'Particulier' : 'Entreprise' }}
                            </p>
                            @if ($user->bio)
                                <p class="mt-2 text-sm text-gray-500">{{ $user->bio }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <dl>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Membre depuis
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $user->created_at->format('d/m/Y') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Statistiques
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Résumé de l'activité sur la plateforme.
                </p>
            </div>
            <div class="border-t border-gray-200">
                <div class="bg-gray-50 px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Offres actives
                                    </dt>
                                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                        {{ $user->offers()->where('status', 'active')->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Contrats actifs
                                    </dt>
                                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                        {{ $user->contracts()->where('status', 'active')->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Contrats complétés
                                    </dt>
                                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                        {{ $user->contracts()->where('status', 'completed')->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
