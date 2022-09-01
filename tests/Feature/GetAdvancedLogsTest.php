<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAdvancedLogsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_advanced_logs()
    {
        $user = User::findOrFail(1);

        $response = $this->actingAs($user)->getJson('/api/getAdvancedLogs?limit=5');

        $response->assertStatus(200);

        $this->assertEquals(true, is_array(json_decode($response->getContent())->data), 'attempt checking response on array ');
    }
}
