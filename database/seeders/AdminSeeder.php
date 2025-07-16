<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'ccsfp.library0@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123') // Or use env('DEFAULT_ADMIN_PASSWORD')
            ]
        );
        $admin->assignRole('admin');

        $user = User::updateOrCreate(
            ['email' => 'rulostbabygirl00@gmail.com'],
            [
                'name' => 'User 1',
                'password' => Hash::make('password123') // Or use env('DEFAULT_ADMIN_PASSWORD')
            ]
        );
        $user->assignRole('user');
    }
}