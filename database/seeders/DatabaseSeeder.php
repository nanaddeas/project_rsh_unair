<?php

namespace Database\Seeders;

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

        // Sample booking data
        $this->call(SampleBookingSeeder::class);
    }
}
