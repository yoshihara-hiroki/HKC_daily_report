<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'vehicle_id',
        'user_id',
        'reservation_date',
        'start_time',
        'end_time',
        'purpose',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    /**
     * 社用車
     */
    public function vehicle()
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

    /**
     * スケジュールへのリレーション
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
