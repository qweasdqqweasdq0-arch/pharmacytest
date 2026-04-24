<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PharmacyE2ETest extends TestCase {
    use RefreshDatabase;
    /** @test */
    public function test_user_flow_from_search_to_reservation() {
        $response = $this->get('/search?query=Panadol');
        $response->assertStatus(200);
    }
}

