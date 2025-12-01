<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// ↑は protected $table = 'daily_reports'; と同義。自動で小文字+複数形のテーブルに紐づけてくれる

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_date',
        'business_content',
        'work_plan',
        'memo',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
        ];
    }

    /**
     * 作成者を取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * コメント
     */
    public function comments()
    {
        return $this->hasMany(DailyReportComment::class);
    }
}
