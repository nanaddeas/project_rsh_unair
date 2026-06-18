<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_book_without_service_or_price(): void
    {
        $date = Carbon::now()->next(Carbon::MONDAY)->toDateString();

        $this->post('/booking/langkah-1', [
            'booking_date' => $date,
            'booking_time' => '08:00',
        ])->assertRedirect(route('booking.step2', absolute: false));

        $this->post('/booking/langkah-2', $this->patientPayload())
            ->assertRedirect(route('booking.confirm', absolute: false));

        $this->post('/booking/simpan', [
            'notes' => 'Pasien tampak lemas.',
        ])->assertRedirect();

        $this->assertDatabaseHas('pet_owners', [
            'nik' => '3517010101900001',
            'name' => 'Budi Santoso',
        ]);

        $this->assertDatabaseHas('pets', [
            'name' => 'Mimi',
            'species' => 'Kucing',
            'breed' => 'Domestik',
            'gender' => 'Betina',
            'fur_color' => 'Putih abu-abu',
        ]);

        $this->assertTrue(Booking::whereDate('booking_date', $date)
            ->where('booking_time', '08:00:00')
            ->where('notes', 'Pasien tampak lemas.')
            ->exists());
    }

    public function test_admin_can_create_manual_walkin_booking(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $date = Carbon::now()->next(Carbon::TUESDAY)->toDateString();

        $response = $this->actingAs($admin)->post('/admin/bookings', array_merge(
            [
                'booking_date' => $date,
                'booking_time' => '08:20',
                'status' => 'Dikonfirmasi',
                'notes' => 'Walk-in pasien.',
            ],
            $this->patientPayload([
                'owner_nik' => '3517020202880002',
                'owner_name' => 'Siti Rahayu',
                'pet_name' => 'Rex',
                'pet_species' => 'Anjing',
                'pet_gender' => 'Jantan',
            ])
        ));

        $response->assertRedirect();

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertSame('Dikonfirmasi', $booking->status);
        $this->assertSame('Walk-in pasien.', $booking->notes);
    }

    private function patientPayload(array $overrides = []): array
    {
        return array_merge([
            'owner_nik' => '3517010101900001',
            'owner_name' => 'Budi Santoso',
            'owner_whatsapp' => '081234567890',
            'owner_address' => 'Jl. Merdeka No. 10, Jombang',
            'pet_name' => 'Mimi',
            'pet_species' => 'Kucing',
            'pet_breed' => 'Domestik',
            'pet_gender' => 'Betina',
            'pet_age' => '2 tahun',
            'pet_fur_color' => 'Putih abu-abu',
        ], $overrides);
    }
}
