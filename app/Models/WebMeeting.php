<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meeting_date',
        'start_time',
        'end_time',
        'meeting_type',
        'title',
        'meeting_url',
        'participants_memo',
    ];

    protected function casts(): array
    {
        return [
            'meeting_date' => 'date',            
        ];
    }

    /**
     * ユーザー取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
