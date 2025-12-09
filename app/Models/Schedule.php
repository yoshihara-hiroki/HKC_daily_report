<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_date',
        'start_time',
        'end_time',
        'destination',
        // 以下Web会議の内容
        'is_web_meeting',
        'meeting_type',
        'meeting_url',
        'participants_memo',
    ];

    protected function casts(): array
    {
        return [
            'schedule_date' => 'date',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'is_web_meeting' => 'boolean',
        ];
    }

    /**
     * ユーザー取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 紐づいている社用車
     */
    public function vehicleReservation()
    {
        return $this->hasOne(VehicleReservation::class);
    }
}
