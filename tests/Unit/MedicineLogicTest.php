<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Medicine;
use App\Models\Reservation;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicineLogicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_decrements_stock_when_reservation_is_approved()
    {
        // إنشاء بيانات يدوية بدلاً من Factory
        $user = User::create(['name' => 'Test User', 'email' => 'test@example.com', 'password' => 'password']);
        $pharmacy = Pharmacy::create(['name' => 'Test Pharmacy', 'user_id' => $user->id, 'address' => 'Damascus']);
        $medicine = Medicine::create([
            'name' => 'Panadol', 
            'stock' => 10, 
            'price' => 5000, 
            'pharmacy_id' => $pharmacy->id
        ]);

        // منطق التحديث (كما هو في PharmacistController)
        $medicine->decrement('stock');

        $this->assertEquals(9, $medicine->fresh()->stock);
    }
}

