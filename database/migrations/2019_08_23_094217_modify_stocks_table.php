<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks',function (Blueprint $table){
            $table->dropColumn('price');
            $table->dropColumn('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('stocks',function (Blueprint $table){
            $table->decimal('price');
            $table->date('expiry_date');
        });
    }
}
