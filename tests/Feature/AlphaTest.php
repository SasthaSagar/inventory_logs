<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class AlphaTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_list_inventory()
    {
        $response = $this->get('/api/list');

        $response->assertStatus(200);

        $response->assertSee('sku','qty');
        // $response->assertDontSee('Beta');
    }

    public function test_can_create_inventory()
    {
        $data = [
            'sku' => Str::random(3),
            'product_id' => rand(0001,9999),
            'qty' => '48',
        ];

        $this->post('/api/create', $data)
            ->assertStatus(201)
            ->assertJson($data);
    }
}
