<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Barber',
            'email' => 'admin@barber.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone_number' => '081234567890',
        ]);

        // 10 Users
        $users = [
            ['name' => 'Budi Santoso',    'email' => 'budi@example.com',    'phone_number' => '081111111111'],
            ['name' => 'Andi Wijaya',     'email' => 'andi@example.com',    'phone_number' => '081222222222'],
            ['name' => 'Reza Pratama',    'email' => 'reza@example.com',    'phone_number' => '081333333333'],
            ['name' => 'Doni Kusuma',     'email' => 'doni@example.com',    'phone_number' => '081444444444'],
            ['name' => 'Fajar Nugroho',   'email' => 'fajar@example.com',   'phone_number' => '081555555555'],
            ['name' => 'Hendra Saputra',  'email' => 'hendra@example.com',  'phone_number' => '081666666666'],
            ['name' => 'Irfan Maulana',   'email' => 'irfan@example.com',   'phone_number' => '081777777777'],
            ['name' => 'Johan Setiawan',  'email' => 'johan@example.com',   'phone_number' => '081888888888'],
            ['name' => 'Kevin Halim',     'email' => 'kevin@example.com',   'phone_number' => '081999999999'],
            ['name' => 'Lukman Hakim',    'email' => 'lukman@example.com',  'phone_number' => '082111111111'],
        ];

        foreach ($users as $user) {
            User::create([
                'name'         => $user['name'],
                'email'        => $user['email'],
                'password'     => Hash::make('password123'),
                'role'         => 'user',
                'phone_number' => $user['phone_number'],
            ]);
        }
    }
}