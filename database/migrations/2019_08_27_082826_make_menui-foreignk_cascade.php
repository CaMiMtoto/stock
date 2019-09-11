<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeMenuiForeignkCascade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);

            $table->foreign('menu_id')
                ->references('id')
                ->on('menus')->onDelete('cascade');
        });
    }

    public function down()
    {
        //
    }
}
