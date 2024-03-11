<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('place_id');
            $table->foreign('place_id')->references('id')->on('Places');
            $table->dateTime('visited_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visits');
    }
};
