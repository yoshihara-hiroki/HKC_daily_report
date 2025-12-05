<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// ↑内部的にModelを継承してるのでusersテーブルと紐づく
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * 一括代入可能なカラム
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * モデルを配列やJSONに変換した際、見せたくないカラムを隠す
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 指定したカラムのレコードをCastする
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 管理者かどうかを判定
     */
    Public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * 社員かどうかの判定
     */
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    /**
     * 所属グループを取得
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * 日報データを取得
     */
    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    /**
     * 日報コメントを取得
     */
    public function dailyReportComments()
    {
        return $this->hasMany(DailyReportComment::class);
    }

    /**
     * 行先予定を取得
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * 社用車予約状況を取得
     */
    public function vehicleReservations()
    {
        return $this->hasMany(VehicleReservation::class);
    }

    /**
     * 会議室予約状況を取得
     */
    public function meetingRoomReservations()
    {
        return $this->hasMany(MeetingRoomReservation::class);
    }
}
