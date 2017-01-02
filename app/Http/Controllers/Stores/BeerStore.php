<?php

namespace App\Http\Controllers\Stores;

use App\Beer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BeerStore extends Controller
{
    public function store($data)
    {
        // Check if record exists in the first place.
        $result = DB::table('beer')
          ->where('id', '=', $data->beer->bid)
          ->exists();

        if (!$result) {
            $beer_data = $data->beer;

            $beer = new Beer;
            $beer->id = $beer_data->bid;
            $beer->name = $beer_data->beer_name;
            $beer->label = $beer_data->beer_label;
            $beer->abv = $beer_data->beer_abv;
            $beer->ibu = $beer_data->beer_ibu;
            $beer->style = $beer_data->beer_style;
            $beer->description = $beer_data->beer_description;
            $beer->wish_list = $beer_data->wish_list;
            $beer->rating_score = $beer_data->rating_score;
            $beer->rating_count = $beer_data->rating_count;

            $beer->save();
        }
    }
}
