<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QueueTest extends TestCase
{
    use RefreshDatabase;


    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_generate_and_send_email()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($user)
    //         ->post('/api/user/generate-pdf');

    //     $response->assertStatus(200);
    // }

    // public function test_generate_and_send_email_without_session()
    // {
    //     $response = $this->withHeaders([
    //         "Accept" => "application/json",
    //         "Content-Type" => "application/json",
    //     ])
    //         ->post('/api/user/generate-pdf');

    //     $response->assertStatus(401);
    // }

    // public function test_generate_and_send_email_without_session_and_headers()
    // {
    //     $response = $this
    //         ->post('/api/user/generate-pdf');

    //     $response->ddHeaders();

    //     $response->assertStatus(500);
    // }
}
