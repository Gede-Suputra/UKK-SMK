<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123#'),
                'role' => 'admin',
                'phone' => null,
                'address' => null,
                'profile_photo_path' => null,
                'email_verified_at' => now(),
            ]
        );

       User::updateOrCreate(
            ['email' => 'petugas@example.com'],
            [
                'name' => 'Petugas',
                'email' => 'petugas@example.com',
                'password' => Hash::make('petugas123#'),
                'role' => 'petugas',
                'phone' => null,
                'address' => null,
                'profile_photo_path' => null,
                'email_verified_at' => now(),
            ]
        );

       User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => Hash::make('user123#'),
                'role' => 'user',
                'phone' => null,
                'address' => null,
                'profile_photo_path' => null,
                'email_verified_at' => now(),
            ]
        );
    }
}
