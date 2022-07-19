<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoinTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['type' => 'bitcoin'], 
            ['type' => 'cosmos'],
            ['type' => 'ethereum'],
            ['type' => 'dacxi'],
            ['type' => 'moon']
        ];

        DB::table('coin_type')->insert($data);
    }
}
