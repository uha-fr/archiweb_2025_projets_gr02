@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Recharger le compteur (kWh)
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Entrez un montant pour recharger votre compteur en kWh.
                </p>
            </div>
            <div class="border-t border-gray-200">
                <form action="{{ route('recharge.compteur') }}" method="POST">
                    @csrf
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="col-span-6 sm:col-span-6">
                            <label for="montant" class="block text-sm font-medium text-gray-700">Montant (kWh)</label>
                            <input type="number" step="0.01" name="montant" id="montant" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('montant')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <a href="{{ url()->previous() }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Annuler
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                            Recharger
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
