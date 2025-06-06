<?php

namespace Database\Seeders;

use Althinect\FilamentSpatieRolesPermissions\Commands\Permission;
use App\Constants\PermissionConstants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = PermissionConstants::PERMISSION_ALL_ADMIN;

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::updateOrCreate(['name' => $permission], [
                'name' => $permission,
                'guard_name' => 'admin',
            ]);
        }

        $permissions = PermissionConstants::PERMISSION_BRANCH_FRONT;

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::updateOrCreate(['name' => $permission], [
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
