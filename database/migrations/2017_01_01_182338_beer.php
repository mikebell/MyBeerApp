<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Beer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      /**
      "beer":{
      "bid":6172,
      "beer_name":"Courage Directors",
      "beer_label":"https:\/\/untappd.akamaized.net\/site\/beer_logos\/beer-6172_2230c_sm.jpeg",
      "beer_abv":4.8,
      "beer_ibu":35,
      "beer_slug":"charles-wells-brewery-courage-directors",
      "beer_style":"English Bitter",
      "beer_description":"",
      "created_at":"Tue, 05 Oct 2010 10:44:22 +0000",
      "auth_rating":0,
      "wish_list":false,
      "rating_score":3.332,
      "rating_count":6846
      },
       */
        Schema::create('beer', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name');
            $table->string('label');
            $table->float('abv', 3, 1);
            $table->float('ibu', 4, 1)->nullable();
            $table->string('style')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('wish_list')->nullable();
            $table->double('rating_score', 5, 4)->nullable();
            $table->integer('rating_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beer');
    }
}
