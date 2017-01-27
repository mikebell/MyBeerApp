<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Brewery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      /**
      "brewery":{
      "brewery_id":1348,
      "brewery_name":"Charles Wells Brewery",
      "brewery_slug":"charles-wells-brewery",
      "brewery_label":"https:\/\/untappd.akamaized.net\/site\/brewery_logos\/brewery-1348_d2dfe.jpeg",
      "country_name":"England",
      "contact":{
        "twitter":"WellsBrewery",
        "facebook":"https:\/\/www.facebook.com\/WellsBrewery",
        "instagram":"WellsBrewery",
        "url":"http:\/\/www.charleswells.co.uk"
      },
      "location":{
        "brewery_city":"Bedford",
        "brewery_state":"Bedfordshire",
        "lat":52.1328,
        "lng":-0.48235
      },
      "brewery_active":1
       */
        Schema::create('brewery', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name');
            $table->string('logo');
            $table->string('country');
            $table->string('location_lat');
            $table->string('location_lon');
            $table->boolean('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brewery');
    }
}
