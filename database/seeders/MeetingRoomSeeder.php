<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MeetingRoom;

class MeetingRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MeetingRoom::create([
            'name' => '小会議室',
            'is_active' => true,
        ]);
        MeetingRoom::create([
            'name' => '大会議室',
            'is_active' => true,
        ]);
    }
}
