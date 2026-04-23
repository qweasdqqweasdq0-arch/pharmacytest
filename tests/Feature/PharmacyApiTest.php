public function test_patient_can_search_for_medicine()
{
    // إنشاء دواء في قاعدة البيانات
    $medicine = \App\Models\Medicine::factory()->create(['name' => 'Panadol']);

    // إرسال طلب بحث (Route البحث في مشروعك)
    $response = $this->get('/search?query=Panadol');

    // التأكد من نجاح الطلب ورؤية اسم الدواء في النتائج
    $response->assertStatus(200);
    $response->assertSee('Panadol');
}

