<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReportComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_report_id',
        'user_id',
        'comment',
    ];

    /**
     * 日報を取得
     */
    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }

    /**
     * コメント投稿者
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
