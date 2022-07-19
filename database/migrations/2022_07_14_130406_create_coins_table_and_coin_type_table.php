<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinsTableAndCoinTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_type', function (Blueprint $table) {
            $table->id();
            $table->string('type', '20');
            $table->timestamps();
        });

        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coin_type_id');
            $table->double('value');
            $table->timestamps();

            $table->foreign('coin_type_id')->references('id')->on('coin_type')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('coins', function(Blueprint $table){
            $table->dropForeign('coins_coin_type_id_foreign');
        });

        Schema::dropIfExists('coins');
        Schema::dropIfExists('coin_type');
    }
}
