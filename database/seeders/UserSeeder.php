<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@ceeac.org'],
            [
                'name' => 'Admin',
                'firstname' => 'DSI',
                'password' => Hash::make('password'),
                'status' => 'actif',
                'function' => 'Directeur des Systemes d\'Information',
                'department' => 'DSI',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        if (app()->environment('local')) {
            $this->createTestUsers();
        }
    }

    protected function createTestUsers(): void
    {
        $fonctionnaire = User::firstOrCreate(
            ['email' => 'fonctionnaire@ceeac.org'],
            [
                'name' => 'Dupont',
                'firstname' => 'Jean',
                'password' => Hash::make('password'),
                'status' => 'actif',
                'function' => 'Fonctionnaire',
                'department' => 'Ressources Humaines',
                'email_verified_at' => now(),
            ]
        );
        $fonctionnaire->assignRole('fonctionnaire');

        $agent = User::firstOrCreate(
            ['email' => 'agent@ceeac.org'],
            [
                'name' => 'Martin',
                'firstname' => 'Sophie',
                'password' => Hash::make('password'),
                'status' => 'actif',
                'function' => 'Agent du Protocole',
                'department' => 'Protocole',
                'email_verified_at' => now(),
            ]
        );
        $agent->assignRole('agent_protocole');

        $chef = User::firstOrCreate(
            ['email' => 'chef@ceeac.org'],
            [
                'name' => 'Bernard',
                'firstname' => 'Pierre',
                'password' => Hash::make('password'),
                'status' => 'actif',
                'function' => 'Chef de Service Protocole',
                'department' => 'Protocole',
                'email_verified_at' => now(),
            ]
        );
        $chef->assignRole('chef_service');

        $directeur = User::firstOrCreate(
            ['email' => 'directeur@ceeac.org'],
            [
                'name' => 'Dubois',
                'firstname' => 'Marie',
                'password' => Hash::make('password'),
                'status' => 'actif',
                'function' => 'Directeur du Protocole',
                'department' => 'Protocole',
                'email_verified_at' => now(),
            ]
        );
        $directeur->assignRole('directeur_protocole');
    }
}
