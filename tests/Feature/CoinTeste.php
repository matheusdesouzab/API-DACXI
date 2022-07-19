<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Coin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Tests\TestCase;

class CoinTeste extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {

        /* $client = new CoinGeckoClient();

        $available_coins_types = [
            ['id' => 1 , 'type' => 'bitcoin'],
            ['id' => 2 , 'type' => 'cosmos'],
            ['id' => 3 , 'type' => 'ethereum'],
            ['id' => 4 , 'type' => 'dacxi'],
            ['id' => 5 , 'type' => 'moon']
        ];

        $response = $client->simple()->getPrice('bitcoin,cosmos,ethereum,dacxi,moon', 'usd');      

            foreach($available_coins_types as $key => $coin_type){
                if(array_key_exists($coin_type->type, $response)){
                    Coin::create([
                        'coin_type_id' => $coin_type->id,
                        'value' => $response[$coin_type->type]['usd']
                    ]);
                }
            } */
    }
}
