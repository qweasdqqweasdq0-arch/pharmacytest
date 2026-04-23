<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. إنشاء مستخدم بدور صيدلي
        $user = \App\Models\User::create([
            'name' => 'الصيدلي علي',
            'email' => 'ali@test.com',
            'password' => bcrypt('12345678'),
            'role' => 'pharmacist',
        ]);
    
        // 2. إنشاء صيدلية تابعة لهذا المستخدم
        \App\Models\Pharmacy::create([
            'name' => 'صيدلية الشفاء المركزية',
            'address' => 'الشارع الرئيسي - حي الزهور',
            'phone' => '0123456789',
            'user_id' => $user->id,
        ]);
    }
}
