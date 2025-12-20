<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenanggungJawabUjian;
use App\Models\User; // Import Model User
use Illuminate\Support\Facades\Hash; // Import Hash
use Faker\Factory as Faker;

class PenanggungJawabUjianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        $jabatans = [
            'Kepala Bidang Keperawatan',
            'Ketua Komite Keperawatan',
            'Koordinator Diklat',
            'Kepala Ruangan ICU',
            'Kepala Ruangan IGD',
            'Manajer SDM & Diklat',
            'Direktur Pelayanan Medis'
        ];

        for ($i = 0; $i < 10; $i++) { // Saya ubah jadi 10 biar datanya agak banyak

            // Generate data awal dulu agar Nama di User & PenanggungJawab SAMA
            $namaLengkap = $faker->title . ' ' . $faker->name;
            $email = $faker->unique()->safeEmail; // Email unik

            // Random Type (Pewawancara / Ujian)
            $type = $faker->randomElement(['pewawancara', 'ujian']);

            // 1. Buat Akun User (Login)
            $user = User::create([
                'name' => $namaLengkap,
                'email' => $email,
                'password' => Hash::make('password'), // Password default: 'password'
                'role' => 'pewawancara', // Role diset pewawancara
            ]);

            // 2. Buat Data Profil Penanggung Jawab (Linked)
            PenanggungJawabUjian::create([
                'user_id' => $user->id, // Link ke User ID yang baru dibuat
                'nama' => $namaLengkap,
                'no_hp' => $faker->phoneNumber,
                'jabatan' => $faker->randomElement($jabatans),
                'type' => $type,
            ]);
        }
    }
}
