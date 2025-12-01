<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'user_id',
        'reserved_date',
        'start_time',
        'end_time',
        'purpose',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
        ];
    }

    /**
     * 社用車
     */
    public function veicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * 予約者
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
