<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Analytics permissions
            'view analytics',
            'export analytics',
            
            // Report permissions
            'view reports',
            'create reports',
            'edit reports',
            'delete reports',
            
            // Settings permissions
            'view settings',
            'edit settings',
            
            // Call permissions
            'view calls',
            'create calls',
            'edit calls',
            'delete calls',
            
            // Ticket permissions
            'view tickets',
            'create tickets',
            'edit tickets',
            'delete tickets',
            
            // Contact permissions
            'view contacts',
            'create contacts',
            'edit contacts',
            'delete contacts',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Supervisor role
        $supervisorRole = Role::create(['name' => 'supervisor']);
        $supervisorRole->givePermissionTo([
            'view analytics',
            'view reports',
            'create reports',
            'edit reports',
            'view settings',
            'view calls',
            'create calls',
            'edit calls',
            'view tickets',
            'create tickets',
            'edit tickets',
            'view contacts',
            'create contacts',
            'edit contacts',
        ]);

        // Agent role
        $agentRole = Role::create(['name' => 'agent']);
        $agentRole->givePermissionTo([
            'view calls',
            'create calls',
            'edit calls',
            'view tickets',
            'create tickets',
            'edit tickets',
            'view contacts',
            'create contacts',
            'edit contacts',
        ]);
    }
}
