<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('shield:generate', [
            '--all'            => true,
            '--option'         => 'policies_and_permissions',
            '--panel'          => 'admin',
            '--no-interaction' => true,
        ]);

        // Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);


        // Permissions 
        $adminPermissions = Permission::whereNotLike('name', '%:Role')->get();

        $userPermissions = [
            // 'ViewAny:User',
            // 'View:User',
            // 'Create:User',
            // 'Update:User',
            // 'Delete:User',
            // 'Restore:User',
            // 'ForceDelete:User',
            // 'ForceDeleteAny:User',
            // 'RestoreAny:User',
            // 'Replicate:User',
            // 'Reorder:User',
        ];

        $adminRole->givePermissionTo($adminPermissions);
        $userRole->givePermissionTo($userPermissions);

        // Assign roles to user
        $superAdminUser = User::where('username', 'superadmin')->first();
        $adminUser      = User::where('username', 'admin')->first();
        $normalUser     = User::where('username', 'user')->first();

        if ($superAdminUser) $superAdminUser->assignRole('super_admin');
        if ($adminUser)      $adminUser->assignRole('admin');
        if ($normalUser)     $normalUser->assignRole('user');
    }
}
