<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use Illuminate\Http\Request;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

class CoinController extends Controller
{

    /**
     * This method returns the value of a currency on a given date
     *
     * @param  string $id
     * @param  string $date
     * @param  string $conversion_currency
     * 
     * @return \Illuminate\Http\Response
     */
    public function getHistory($id, $date, $conversion_currency = 'usd')
    {

        $client = new CoinGeckoClient();

        $data = $client->coins()->getHistory($id, $date);

        if (!isset($data['market_data'])) {
            return response()->json(['erro' => 'Não existe nenhum registro da criptomoeda ' .$id. ' em ' .$date], 404);
        }

        $current_price = $data['market_data']['current_price'][$conversion_currency];

        return response()->json([
            'sucesso' => 'O valor do '.$id.' em ' .$conversion_currency. ' na data de ' .$date. ', estava em ' .$current_price
        ], 200);   
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @param string $conversion_currency
     * 
     * @return \Illuminate\Http\Response
     */
    public function getPrice($id, $conversion_currency = 'usd')
    {

        $client = new CoinGeckoClient();

        $data = $client->simple()->getPrice($id, $conversion_currency);

        if (!$data) {
            return response()->json([
                'erro' => 'Não existe nenhuma criptomoeda cadastrada com o nome ' . $id . ' na API CoinGecko'
            ], 404);
        }

        return response()->json($data, 200);
    }
}
