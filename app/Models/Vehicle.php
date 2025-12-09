<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
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
        return $this->hasMany(VehicleReservation::class);
    }

    /**
     * 有効な車両のみ取得するスコープ
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
