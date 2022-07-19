<?php

namespace Tests\Unit;

use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoinTest extends TestCase
{
    /**
     * 
     *
     * @return void
     */
    public function test_get_current_value_of_currency()
    {

        $response = $this->getJson('/api/coins/bitcoin/usd');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure()
            ->assertSee('bitcoin');

        $response->dump();
    }


    /**
     * A basic unit test example.
     *
     * @return void
     */
     public function test_the_method_that_returns_the_currency_value_by_date()
    {

        $response = $this->getJson('/api/coins/history/bitcoin/22-06-2022/usd');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure()
            ->assertSee('bitcoin');

        $response->dump();

    } 
}
