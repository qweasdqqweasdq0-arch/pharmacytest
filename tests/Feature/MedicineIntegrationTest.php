<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Pharmacy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

class MedicineIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // ضبط مفتاح التطبيق لبيئة الاختبار
        Config::set('app.key', 'base64:u68Y796S06S1p8vS9V8vS7V6S5V4S3V2S1V0S9V8vS7=');
    }

    /** @test */
    public function it_can_add_medicine_and_persist_in_database()
    {
        // 1. التجهيز
        $user = User::create([
            'name' => 'Ibrahim',
            'email' => 'ibrahim' . time() . '@example.com',
            'password' => bcrypt('password')
        ]);

        $pharmacy = Pharmacy::create([
            'user_id' => $user->id,
            'name'    => 'Ibrahim Pharmacy',
            'address' => 'Damascus, Syria'
        ]);

        // 2. التنفيذ
        $response = $this->actingAs($user)->post('pharmacist/medicines/store', [
            'name' => 'Panadol Extra',
            'price' => 7000,
            'stock' => 20,
            'pharmacy_id' => $pharmacy->id
        ]);

        // 3. التحقق
        // بما أن الـ Controller يوجه المستخدم لصفحة أخرى بعد الحفظ، نتحقق من الـ Redirect
        $response->assertStatus(302); 

        // التأكد من أن الدواء أصبح موجوداً فعلياً في قاعدة البيانات
        $this->assertDatabaseHas('medicines', [
            'name' => 'Panadol Extra',
            'stock' => 20
        ]);
    }
}

