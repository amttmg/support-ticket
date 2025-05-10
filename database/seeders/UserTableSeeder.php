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

        $user = User::updateOrCreate(['email' => 'admin@mail.com'], [
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password')
        ]);

        $user->assignRole($roleObj);
    }
}
