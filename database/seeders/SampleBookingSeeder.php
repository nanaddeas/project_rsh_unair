<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Pet;
use App\Models\PetOwner;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SampleBookingSeeder extends Seeder
{
    public function run(): void
    {
        $sampleData = [
            [
                'owner' => ['nik' => '3517010101900001', 'name' => 'Budi Santoso', 'address' => 'Jl. Merdeka No. 10, Jombang', 'whatsapp' => '081234567890'],
                'pet' => ['name' => 'Mimi', 'species' => 'Kucing', 'breed' => 'Domestik', 'gender' => 'Betina', 'age' => '2 tahun', 'fur_color' => 'Putih abu-abu'],
                'date'  => Carbon::today()->toDateString(),
                'time'  => '08:00:00',
                'status' => 'Menunggu Konfirmasi',
            ],
            [
                'owner' => ['nik' => '3517020202880002', 'name' => 'Siti Rahayu', 'address' => 'Jl. Ahmad Yani No. 25, Mojoagung', 'whatsapp' => '082345678901'],
                'pet' => ['name' => 'Rex', 'species' => 'Anjing', 'breed' => 'Golden Retriever', 'gender' => 'Jantan', 'age' => '3 tahun', 'fur_color' => 'Coklat emas'],
                'date'  => Carbon::today()->toDateString(),
                'time'  => '09:00:00',
                'status' => 'Dikonfirmasi',
            ],
            [
                'owner' => ['nik' => '3517030303850003', 'name' => 'Ahmad Fauzi', 'address' => 'Jl. Diponegoro No. 5, Jombang', 'whatsapp' => '083456789012'],
                'pet' => ['name' => 'Putih', 'species' => 'Kelinci', 'breed' => 'Lokal', 'gender' => 'Betina', 'age' => '1 tahun', 'fur_color' => 'Putih'],
                'date'  => Carbon::yesterday()->toDateString(),
                'time'  => '10:00:00',
                'status' => 'Selesai',
            ],
            [
                'owner' => ['nik' => '3517040404920004', 'name' => 'Dewi Anggraini', 'address' => 'Jl. Sudirman No. 30, Jombang', 'whatsapp' => '084567890123'],
                'pet' => ['name' => 'Pipit', 'species' => 'Burung', 'breed' => 'Lovebird', 'gender' => 'Jantan', 'age' => '6 bulan', 'fur_color' => 'Hijau kuning'],
                'date'  => Carbon::tomorrow()->toDateString(),
                'time'  => '08:20:00',
                'status' => 'Menunggu Konfirmasi',
            ],
            [
                'owner' => ['nik' => '3517050505800005', 'name' => 'Hendra Kurniawan', 'address' => 'Jl. Gatot Subroto No. 12, Jombang', 'whatsapp' => '085678901234'],
                'pet' => ['name' => 'Panda', 'species' => 'Hamster', 'breed' => 'Syrian', 'gender' => 'Jantan', 'age' => '8 bulan', 'fur_color' => 'Coklat putih'],
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
                'booking_date' => $data['date'],
                'booking_time' => $data['time'],
                'status'       => $data['status'],
            ]);
        }
    }
}
