<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapsTable extends Migration
{
    public function up()
    {
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->double('topLeft_x');
            $table->double('topLeft_y');
            $table->double('topRight_x');
            $table->double('topRight_y');
            $table->double('bottomLeft_x');
            $table->double('bottomLeft_y');
            $table->double('bottomRight_x');
            $table->double('bottomRight_y');
            $table->string('text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maps');
    }
}
