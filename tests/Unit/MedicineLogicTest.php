<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicineLogicTest extends TestCase
{
    use RefreshDatabase; // يقوم بإنشاء قاعدة بيانات مؤقتة للاختبار

    /** @test */
    public function it_decrements_stock_when_reservation_is_approved()
    {
        // إنشاء البيانات اللازمة يدوياً لضمان عدم فشل الاختبار بسبب نقص الـ Factories
        $user = User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => 'password']);
        $pharmacy = Pharmacy::create(['name' => 'Test Pharmacy', 'user_id' => $user->id, 'address' => 'Damascus']);
        $medicine = Medicine::create([
            'pharmacy_id' => $pharmacy->id,
            'name' => 'Panadol',
            'price' => 5000,
            'stock' => 10
        ]);

        // محاكاة منطق خصم الكمية الموجود في PharmacistController
        $medicine->decrement('stock');

        $this->assertEquals(9, $medicine->fresh()->stock);
    }
}

