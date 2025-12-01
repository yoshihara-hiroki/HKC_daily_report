<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 管理者ユーザー
        User::create([
            'name' => '管理者',
            'email' => 'admin@hyoubo.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // テスト用社員ユーザー
        User::create([
            'name' => '吉原 弘喜',
            'email' => 'yoshihara@hyoubo.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        User::create([
            'name' => '田中 太郎',
            'email' => 'tanaka@hyoubo.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        User::create([
            'name' => '佐藤 花子',
            'email' => 'sato@hyoubo.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);
    }
}
