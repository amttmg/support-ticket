<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = ['name' => 'Super Admin'];
        $roleObj = Role::updateOrCreate(['name' => $role['name']], $role);

        $user = User::updateOrCreate(['email' => 'super-admin@rbb.com.np'], [
            'name' => 'Admin',
            'email' => 'super-admin@rbb.com.np',
            'password' => bcrypt('password'),
            'user_type' => 'back',
        ]);

        $user->assignRole($roleObj);
    }
}
