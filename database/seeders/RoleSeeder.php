<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (RolePermissionSeeder::roles() as $roleName => $permissions) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName]);
        }
    }
}
