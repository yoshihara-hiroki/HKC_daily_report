<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::create([
            'name' => '開発部',
            'description' => '学費チーム・Newtionチーム',
        ]);

        Group::create([
            'name' => '営業部',
            'description' => '営業部',
        ]);

        Group::create([
            'name' => 'EduCoreOps事業部',
            'description' => 'EducoreOps事業部',
        ]);
    }
}
