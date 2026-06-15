<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Pet;
use App\Models\PetOwner;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@rshewan.com'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@rshewan.com',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Services
        $services = [
            ['name' => 'Grooming',                     'description' => 'Layanan perawatan bulu, kuku, dan kebersihan hewan peliharaan Anda.', 'estimated_price' => 'Rp 50.000 - Rp 150.000', 'sort_order' => 1],
            ['name' => 'Pemeriksaan Umum',             'description' => 'Pemeriksaan fisik menyeluruh untuk mengetahui kondisi kesehatan hewan.',  'estimated_price' => 'Mulai Rp 75.000',         'sort_order' => 2],
            ['name' => 'Konsultasi Dokter',            'description' => 'Konsultasi langsung dengan dokter hewan berpengalaman.',                  'estimated_price' => 'Mulai Rp 75.000',         'sort_order' => 3],
            ['name' => 'Vaksinasi',                    'description' => 'Layanan vaksinasi untuk mencegah berbagai penyakit pada hewan.',           'estimated_price' => 'Mulai Rp 150.000',        'sort_order' => 4],
            ['name' => 'Sterilisasi',                  'description' => 'Prosedur sterilisasi yang aman dan dilakukan oleh dokter berpengalaman.',  'estimated_price' => 'Mulai Rp 500.000',        'sort_order' => 5],
            ['name' => 'Rawat Luka',                   'description' => 'Penanganan dan perawatan luka pada hewan peliharaan Anda.',                'estimated_price' => 'Mulai Rp 100.000',        'sort_order' => 6],
            ['name' => 'Pemeriksaan Kesehatan Berkala','description' => 'Pemeriksaan rutin untuk memantau kesehatan hewan secara berkala.',         'estimated_price' => 'Mulai Rp 75.000',         'sort_order' => 7],
            ['name' => 'Lainnya',                      'description' => 'Layanan lainnya sesuai kebutuhan hewan Anda.',                             'estimated_price' => 'Sesuai tindakan',          'sort_order' => 8],
        ];

        foreach ($services as $svc) {
            Service::updateOrCreate(['name' => $svc['name']], array_merge($svc, ['is_active' => true]));
        }

        // Sample booking data
        $this->call(SampleBookingSeeder::class);
    }
}
