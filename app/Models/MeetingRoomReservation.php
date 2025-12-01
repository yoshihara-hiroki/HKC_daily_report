<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRoomReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_room_id',
        'user_id',
        'reservation_date',
        'start_time',
        'end_time',
        'title',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
        ];
    }

    /**
     * 会議室取得
     */
    public function meetingRoom()
    {
        return $this->belongsTo(MeetingRoom::class);
    }

    /**
     * 予約者取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
