<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $user = User::updateOrCreate(
            ['email' => 'admin@hrm.local'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('Super Admin');
    }
}