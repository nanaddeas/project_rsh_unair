<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Pet;
use App\Models\PetOwner;
use App\Models\User;
use App\Services\BookingService;
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

        $this->get('/booking/langkah-2')
            ->assertOk()
            ->assertSee('Klien Baru')
            ->assertSee('Pasien Lama');

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
            ->where('client_type', 'Baru')
            ->where('patient_type', 'Baru')
            ->where('notes', 'Pasien tampak lemas.')
            ->exists());
    }

    public function test_admin_can_create_manual_walkin_booking(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $date = Carbon::now()->next(Carbon::TUESDAY)->toDateString();
        PetOwner::create([
            'nik' => '3517020202880002',
            'name' => 'Siti Rahayu',
            'whatsapp' => '081234567890',
            'address' => 'Alamat lama',
        ]);

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
                'client_type' => 'Lama',
                'pet_name' => 'Rex',
                'pet_species' => 'Anjing',
                'pet_gender' => 'Jantan',
            ])
        ));

        $response->assertRedirect();

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertSame('Dikonfirmasi', $booking->status);
        $this->assertSame('Lama', $booking->client_type);
        $this->assertSame('Baru', $booking->patient_type);
        $this->assertSame('Walk-in pasien.', $booking->notes);
        $this->assertSame(1, PetOwner::where('nik', '3517020202880002')->count());

        $this->actingAs($admin)->get('/admin/bookings')
            ->assertOk()
            ->assertSee('Export Excel')
            ->assertSee('Klien Lama')
            ->assertSee('Pasien Baru');
    }

    public function test_nik_lookup_returns_registered_owner_and_pets(): void
    {
        $booking = $this->createBooking();

        $this->postJson(route('booking.owner.lookup'), [
            'nik' => $booking->owner->nik,
        ])->assertOk()
            ->assertJsonPath('found', true)
            ->assertJsonPath('owner.name', $booking->owner->name)
            ->assertJsonPath('pets.0.id', $booking->pet->id);

        $this->postJson(route('booking.owner.lookup'), [
            'nik' => '3517999999999999',
        ])->assertOk()->assertJson([
            'found' => false,
            'pets' => [],
        ]);
    }

    public function test_registered_pet_is_reused_and_marked_as_old_patient(): void
    {
        $existingBooking = $this->createBooking();
        $date = Carbon::now()->next(Carbon::WEDNESDAY)->toDateString();

        $this->post('/booking/langkah-1', [
            'booking_date' => $date,
            'booking_time' => '08:40',
        ])->assertRedirect(route('booking.step2', absolute: false));

        $payload = $this->patientPayload([
            'owner_nik' => $existingBooking->owner->nik,
            'existing_pet_id' => $existingBooking->pet->id,
        ]);
        foreach (['pet_name', 'pet_species', 'pet_breed', 'pet_gender', 'pet_age', 'pet_fur_color'] as $field) {
            unset($payload[$field]);
        }

        $this->post('/booking/langkah-2', $payload)
            ->assertRedirect(route('booking.confirm', absolute: false));

        $this->post('/booking/simpan')->assertRedirect();

        $newBooking = Booking::whereKeyNot($existingBooking->id)
            ->whereDate('booking_date', $date)
            ->firstOrFail();
        $this->assertSame($existingBooking->owner_id, $newBooking->owner_id);
        $this->assertSame($existingBooking->pet_id, $newBooking->pet_id);
        $this->assertSame('Lama', $newBooking->client_type);
        $this->assertSame('Lama', $newBooking->patient_type);
        $this->assertSame(1, PetOwner::where('nik', $existingBooking->owner->nik)->count());
        $this->assertSame(1, Pet::where('owner_id', $existingBooking->owner_id)->count());
    }

    public function test_expired_session_is_automatically_marked_as_no_show(): void
    {
        $booking = $this->createBooking([
            'booking_date' => '2026-06-23',
            'booking_time' => '08:00:00',
            'status' => 'Dikonfirmasi',
        ]);

        $updated = BookingService::markExpiredBookingsAsNoShow(
            Carbon::parse('2026-06-23 08:20:00')
        );

        $this->assertSame(1, $updated);
        $this->assertSame('Tidak Hadir', $booking->fresh()->status);
    }

    public function test_session_is_not_marked_absent_before_it_ends(): void
    {
        $booking = $this->createBooking([
            'booking_date' => '2026-06-23',
            'booking_time' => '08:00:00',
            'status' => 'Dikonfirmasi',
        ]);

        BookingService::markExpiredBookingsAsNoShow(
            Carbon::parse('2026-06-23 08:19:59')
        );

        $this->assertSame('Dikonfirmasi', $booking->fresh()->status);
    }

    public function test_admin_can_export_filtered_bookings_for_excel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->createBooking([
            'booking_code' => 'RSH-EXPORT-260623',
            'client_type' => 'Lama',
            'patient_type' => 'Baru',
            'status' => 'Selesai',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.bookings.export', [
            'status' => 'Selesai',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $content = $response->streamedContent();
        $this->assertStringContainsString($booking->booking_code, $content);
        $this->assertStringContainsString('Status Klien', $content);
    }

    private function patientPayload(array $overrides = []): array
    {
        return array_merge([
            'owner_nik' => '3517010101900001',
            'client_type' => 'Baru',
            'owner_name' => 'Budi Santoso',
            'owner_whatsapp' => '081234567890',
            'owner_address' => 'Jl. Merdeka No. 10, Jombang',
            'pet_name' => 'Mimi',
            'patient_type' => 'Baru',
            'pet_species' => 'Kucing',
            'pet_breed' => 'Domestik',
            'pet_gender' => 'Betina',
            'pet_age' => '2 tahun',
            'pet_fur_color' => 'Putih abu-abu',
        ], $overrides);
    }

    private function createBooking(array $overrides = []): Booking
    {
        $owner = PetOwner::create([
            'nik' => '3517010101900001',
            'name' => 'Budi Santoso',
            'whatsapp' => '081234567890',
            'address' => 'Jl. Merdeka No. 10',
        ]);
        $pet = Pet::create([
            'owner_id' => $owner->id,
            'name' => 'Mimi',
            'species' => 'Kucing',
            'breed' => 'Domestik',
            'gender' => 'Betina',
            'age' => '2 tahun',
            'fur_color' => 'Putih',
        ]);

        return Booking::create(array_merge([
            'booking_code' => Booking::generateCode(),
            'owner_id' => $owner->id,
            'pet_id' => $pet->id,
            'client_type' => 'Baru',
            'patient_type' => 'Baru',
            'booking_date' => '2026-06-24',
            'booking_time' => '08:00:00',
            'status' => 'Dikonfirmasi',
        ], $overrides));
    }
}
