<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Pharmacy;
use App\Models\Medicine;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicineLogicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_verifies_medicine_stock_logic()
    {
        // 1. إنشاء مستخدم وصيدلية بناءً على هيكلية مشروعك
        $user = User::create(['name' => 'Ibrahim', 'email' => 'ibrahim@test.com', 'password' => 'password']);
        $pharmacy = Pharmacy::create(['user_id' => $user->id, 'name' => 'SPU Pharmacy', 'address' => 'Syria']);
        
        // 2. إنشاء دواء مرتبط بالصيدلية
        $medicine = Medicine::create([
            'pharmacy_id' => $pharmacy->id,
            'name' => 'Panadol',
            'price' => 5000,
            'stock' => 10
        ]);

        // 3. اختبار عملية الخصم (المنطق المطلوب في الوظيفة)
        $medicine->decrement('stock');

        $this->assertEquals(9, $medicine->fresh()->stock);
    }
}

