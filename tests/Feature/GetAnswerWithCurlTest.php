<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAnswerWithCurlTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_crawler_is_forbidden()
    {
        $response = $this->get('/api/get-answer');

        $response->assertStatus(200)
        ->assertExactJson([
            'data' => 'Forbidden'
        ]);
    }
}
