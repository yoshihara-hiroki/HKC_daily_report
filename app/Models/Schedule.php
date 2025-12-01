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
    ];

    protected function casts(): array
    {
        return [
            'schedule_date' => 'date',
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
