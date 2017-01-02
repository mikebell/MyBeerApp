<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Checkin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
        "first_checkin_id":401776370,
        "first_created_at":"Sun, 01 Jan 2017 16:04:42 +0000",
        "recent_checkin_id":401776370,
        "recent_created_at":"Sun, 01 Jan 2017 16:04:42 +0000",
        "recent_created_at_timezone":"0",
        "rating_score":2.75,
        "first_had":"Sun, 01 Jan 2017 16:04:42 +0000",
        "count":1,
         */
        Schema::create('checkin', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('first_checkin_id');
            $table->integer('first_checkin_date');
            $table->integer('recent_checkin_id');
            $table->integer('recent_checkin_date');
            $table->float('my_rating', 4, 3);
            $table->integer('first_checkin');
            $table->integer('total');
            $table->integer('beer_id');
            $table->string('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkin');
    }
}
