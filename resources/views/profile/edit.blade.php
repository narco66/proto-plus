<x-proto-layout page-title="Mon profil" :breadcrumbs="[['label' => 'Mon profil']]">
    @section('content')
        <!-- Informations du profil -->
        <x-card title="Informations du profil">
            @include('profile.partials.update-profile-information-form')
        </x-card>

        <!-- Mot de passe -->
        <x-card title="Changer le mot de passe">
            @include('profile.partials.update-password-form')
        </x-card>

        <!-- Supprimer le compte -->
        <x-card title="Supprimer le compte">
            @include('profile.partials.delete-user-form')
        </x-card>
    @endsection
</x-proto-layout>
