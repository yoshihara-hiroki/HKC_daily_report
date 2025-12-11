<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * 予約
     */
    public function reservations()
    {
        return $this->hasMany(MeetingRoomReservation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
