<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public static function permissions(): array
    {
        return [
            // Demandes
            'demandes.create',
            'demandes.submit',
            'demandes.view_own',
            'demandes.view_all',
            'demandes.edit',
            'demandes.delete',
            'demandes.validate_level_1',
            'demandes.validate_level_2',
            'demandes.validate_level_3',

            // Documents
            'documents.upload',
            'documents.view',
            'documents.view_sensitive',
            'documents.download',
            'documents.delete',

            // Fonctionnaires
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.manage',

            // Ayants droit
            'ayants_droit.view',
            'ayants_droit.create',
            'ayants_droit.edit',
            'ayants_droit.delete',

            // Workflow
            'workflow.view',
            'workflow.manage',

            // Rapports
            'rapports.view',
            'rapports.export',

            // Audit
            'audit.view',
            'audit.export',

            // Roles
            'roles.manage',
            'roles.view',

            // Admin
            'admin.access',
        ];
    }

    public static function roles(): array
    {
        return [
            'fonctionnaire' => [
                'demandes.create',
                'demandes.submit',
                'demandes.view_own',
                'demandes.delete',
                'documents.upload',
                'documents.view',
                'ayants_droit.view',
                'ayants_droit.create',
                'ayants_droit.edit',
                'ayants_droit.delete',
            ],
            'agent_protocole' => [
                'demandes.view_all',
                'demandes.edit',
                'documents.upload',
                'documents.view',
                'users.view',
                'ayants_droit.view',
                'workflow.view',
            ],
            'chef_service_protocole' => [
                'demandes.view_all',
                'demandes.validate_level_1',
                'documents.view',
                'users.view',
                'ayants_droit.view',
                'workflow.view',
                'rapports.view',
            ],
            'chef_service' => [
                'demandes.view_all',
                'demandes.validate_level_1',
                'documents.view',
                'users.view',
                'ayants_droit.view',
                'workflow.view',
                'rapports.view',
            ],
            'directeur_protocole' => [
                'demandes.view_all',
                'demandes.validate_level_2',
                'documents.view',
                'documents.view_sensitive',
                'users.view',
                'ayants_droit.view',
                'workflow.view',
                'rapports.view',
                'rapports.export',
            ],
            'secretaire_general' => [
                'demandes.view_all',
                'demandes.validate_level_3',
                'documents.view',
                'documents.view_sensitive',
                'users.view',
                'workflow.view',
                'rapports.view',
                'rapports.export',
            ],
            'directeur_SI' => [
                'users.manage',
                'roles.manage',
                'roles.view',
                'audit.view',
                'audit.export',
                'workflow.manage',
            ],
            'admin' => self::permissions(),
        ];
    }

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (self::permissions() as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach (self::roles() as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
