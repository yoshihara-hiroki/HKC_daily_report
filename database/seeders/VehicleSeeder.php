<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::create([
            'name' => 'NX',
            'is_active' => true,
        ]);

        Vehicle::create([
            'name' => 'CT',
            'is_active' => true,
        ]);
    }
}
