<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Pet;
use App\Models\PetOwner;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SampleBookingSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all();

        $sampleData = [
            [
                'owner' => ['name' => 'Budi Santoso',  'address' => 'Jl. Merdeka No. 10, Jombang', 'whatsapp' => '081234567890'],
                'pet'   => ['name' => 'Mimi',  'species' => 'Kucing',  'age' => '2 tahun'],
                'date'  => Carbon::today()->toDateString(),
                'time'  => '08:00:00',
                'status' => 'Menunggu Konfirmasi',
            ],
            [
                'owner' => ['name' => 'Siti Rahayu',   'address' => 'Jl. Ahmad Yani No. 25, Mojoagung', 'whatsapp' => '082345678901'],
                'pet'   => ['name' => 'Rex',   'species' => 'Anjing',  'age' => '3 tahun'],
                'date'  => Carbon::today()->toDateString(),
                'time'  => '09:00:00',
                'status' => 'Dikonfirmasi',
            ],
            [
                'owner' => ['name' => 'Ahmad Fauzi',   'address' => 'Jl. Diponegoro No. 5, Jombang', 'whatsapp' => '083456789012'],
                'pet'   => ['name' => 'Putih', 'species' => 'Kelinci', 'age' => '1 tahun'],
                'date'  => Carbon::yesterday()->toDateString(),
                'time'  => '10:00:00',
                'status' => 'Selesai',
            ],
            [
                'owner' => ['name' => 'Dewi Anggraini', 'address' => 'Jl. Sudirman No. 30, Jombang', 'whatsapp' => '084567890123'],
                'pet'   => ['name' => 'Pipit', 'species' => 'Burung',  'age' => '6 bulan'],
                'date'  => Carbon::tomorrow()->toDateString(),
                'time'  => '08:20:00',
                'status' => 'Menunggu Konfirmasi',
            ],
            [
                'owner' => ['name' => 'Hendra Kurniawan', 'address' => 'Jl. Gatot Subroto No. 12, Jombang', 'whatsapp' => '085678901234'],
                'pet'   => ['name' => 'Panda', 'species' => 'Hamster', 'age' => '8 bulan'],
                'date'  => Carbon::today()->toDateString(),
                'time'  => '14:00:00',
                'status' => 'Dikonfirmasi',
            ],
        ];

        foreach ($sampleData as $i => $data) {
            $owner = PetOwner::create($data['owner']);
            $pet   = Pet::create(array_merge($data['pet'], ['owner_id' => $owner->id]));

            Booking::create([
                'booking_code' => Booking::generateCode(),
                'owner_id'     => $owner->id,
                'pet_id'       => $pet->id,
                'service_id'   => $services->random()->id,
                'booking_date' => $data['date'],
                'booking_time' => $data['time'],
                'status'       => $data['status'],
            ]);
        }
    }
}
