<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'owner_id',
        'pet_id',
        'booking_date',
        'booking_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    const STATUSES = [
        'Menunggu Konfirmasi',
        'Dikonfirmasi',
        'Selesai',
        'Dibatalkan',
        'Tidak Hadir',
    ];

    const STATUS_COLORS = [
        'Menunggu Konfirmasi' => 'warning',
        'Dikonfirmasi'        => 'info',
        'Selesai'             => 'success',
        'Dibatalkan'          => 'danger',
        'Tidak Hadir'         => 'secondary',
    ];

    const STATUS_ICONS = [
        'Menunggu Konfirmasi' => 'clock',
        'Dikonfirmasi'        => 'check-circle',
        'Selesai'             => 'check-double',
        'Dibatalkan'          => 'x-circle',
        'Tidak Hadir'         => 'person-x',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(PetOwner::class, 'owner_id');
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public function getStatusIconAttribute(): string
    {
        return self::STATUS_ICONS[$this->status] ?? 'question-circle';
    }

    public function getFormattedTimeAttribute(): string
    {
        return \Carbon\Carbon::createFromTimeString($this->booking_time)->format('H:i');
    }

    public function scopeSearch($query, string $search)
    {
        return $query->whereHas('owner', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })->orWhereHas('pet', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })->orWhere('booking_code', 'like', "%{$search}%");
    }

    public static function generateCode(): string
    {
        do {
            $code = 'RSH-' . strtoupper(substr(uniqid(), -6)) . '-' . date('ymd');
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }
}
