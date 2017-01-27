<?php

namespace App\Http\Controllers\Stores;

use App\Brewery;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BreweryStore extends Controller
{
    public function store($data)
    {
        // Check if record exists in the first place.
        $result = DB::table('brewery')
          ->where('id', '=', $data->brewery_id)
          ->exists();

        if (!$result) {
            /**
             * "brewery_id":1348,
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

            $brewery = new Brewery();
            $brewery->id = $data->brewery_id;
            $brewery->name = $data->brewery_name;
            $brewery->logo = $data->brewery_label;
            $brewery->country = $data->country_name;
            $brewery->location_lat = $data->location->lat;
            $brewery->location_lon = $data->location->lng;
            $brewery->active = $data->brewery_active;

            $brewery->save();
        }
    }
}
