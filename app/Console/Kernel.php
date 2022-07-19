<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use App\Models\Coin;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->call(fn() => DB::table('coins')->delete())->daily()->timezone('America/Sao_Paulo');

        $schedule->call(function() {

            $client = new CoinGeckoClient();
            $available_coins_types = DB::table('coin_type')->select(['id','type'])->get();
            $response = $client->simple()->getPrice('bitcoin,cosmos,ethereum,dacxi,moon', 'usd');

            foreach($available_coins_types as $key => $coin_type){
                if(array_key_exists($coin_type->type, $response)){
                    Coin::create([
                        'coin_type_id' => $coin_type->id,
                        'value' => $response[$coin_type->type]['usd']
                    ]);
                }
            }
        })->everyTenMinutes();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
