<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('prepared_by')->nullable();
            $table->string('checked_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('delivered_by')->nullable();
            $table->string('received_by')->nullable();
            $table->string('department');
            $table->string('status');
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
