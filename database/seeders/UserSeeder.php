<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail'],
            [
                'name'             => 'Admin DIKSERA',
                'password'         => Hash::make('password'),
                'role'             => 'admin',
                'telegram_chat_id' => null,
            ]
        );

        // Contoh Perawat
        User::updateOrCreate(
            ['email' => 'perawat@gmail'],
            [
                'name'             => 'Perawat Contoh',
                'password'         => Hash::make('password'),
                'role'             => 'perawat',
                'telegram_chat_id' => null,
            ]
        );

        // Contoh Pewawancara
        User::updateOrCreate(
            ['email' => 'pewawancara@gmail'],
            [
                'name'             => 'Pewawancara Contoh',
                'password'         => Hash::make('password'),
                'role'             => 'pewawancara',
                'telegram_chat_id' => null,
            ]
        );
    }
}
