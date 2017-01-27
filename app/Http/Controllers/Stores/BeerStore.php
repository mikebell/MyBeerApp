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
          ->where('id', '=', $data->bid)
          ->exists();

        if (!$result) {
            $beer = new Beer;
            $beer->id = $data->bid;
            $beer->name = $data->beer_name;
            $beer->label = $data->beer_label;
            $beer->abv = $data->beer_abv;

            if (isset($beer->ibu)) {
                $beer->ibu = $data->beer_ibu;
            }
            if (isset($beer->style)) {
                $beer->style = $data->beer_style;
            }
            if (isset($beer->description)) {
                $beer->description = $data->beer_description;
            }
            if (isset($beer->wish_list)) {
                $beer->wish_list = $data->wish_list;
            }
            if (isset($beer->rating_score)) {
                $beer->rating_score = $data->rating_score;
            }
            if (isset($beer->rating_count)) {
                $beer->rating_count = $data->rating_count;
            }

            $beer->save();
        }
    }
}
