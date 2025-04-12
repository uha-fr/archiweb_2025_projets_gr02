@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-5">
            <a href="{{ route('profile') }}" class="inline-flex items-center text-primary-600 hover:text-primary-900">
                <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour au profil
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Modifier votre profil
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Mettez à jour vos informations personnelles et préférences.
                </p>
            </div>
            <div class="border-t border-gray-200">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="px-4 py-5 sm:px-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-24 w-24 relative group">
                                <img class="h-24 w-24 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <label for="profile_photo" class="cursor-pointer text-white text-xs text-center p-2">
                                        Changer la photo
                                    </label>
                                </div>
                                <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*">
                            </div>
                            <div class="ml-4">
                                @if ($user->profile_photo)
                                    <button type="button" class="text-sm text-red-600 hover:text-red-900" onclick="document.getElementById('delete-photo-form').submit();">
                                        Supprimer la photo
                                    </button>
                                @endif
                                @error('profile_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700">Adresse e-mail</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="phone_number" class="block text-sm font-medium text-gray-700">Numéro de téléphone</label>
                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('phone_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                                <textarea name="address" id="address" rows="3" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if ($user->role == 'company')
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="company_name" class="block text-sm font-medium text-gray-700">Nom de l'entreprise</label>
                                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $user->company_name) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('company_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="tax_id" class="block text-sm font-medium text-gray-700">Numéro de TVA</label>
                                    <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $user->tax_id) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('tax_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <div class="col-span-6">
                                <label for="bio" class="block text-sm font-medium text-gray-700">Biographie</label>
                                <textarea name="bio" id="bio" rows="4" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('bio', $user->bio) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Brève description de vous-même ou de votre entreprise.</p>
                                @error('bio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <form id="delete-photo-form" action="{{ route('profile.photo.delete') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
