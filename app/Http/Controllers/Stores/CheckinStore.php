<?php

namespace App\Http\Controllers\Stores;

use App\Checkin;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CheckinStore extends Controller
{
    public function store($data, $user)
    {
        // Check if record exists in the first place.
        $result = DB::table('checkin')
          ->where('id', '=', $data->recent_checkin_id)
          ->where('user', '=', $user)
          ->exists();

        if (!$result) {
            $checkin = new Checkin;
            $checkin->id = $data->recent_checkin_id;
            $checkin->first_checkin_id = $data->first_checkin_id;

            // Convert date into unix timestamp;
            $checkin->first_checkin_date = $this->convertDate($data->first_created_at);

            $checkin->recent_checkin_id = $data->recent_checkin_id;

            $checkin->recent_checkin_date = $this->convertDate($data->recent_created_at);

            $checkin->my_rating = $data->rating_score;

            $checkin->first_checkin = $this->convertDate($data->first_had);

            $checkin->total = $data->count;

            $checkin->beer_id = $data->beer->bid;

            $checkin->user = $user;

            $checkin->save();
        }
    }

    public function convertDate($date)
    {
        // Example: Sun, 01 Jan 2017 16:04:42 +0000
        $date = DateTime::createFromFormat('D, d M Y H:i:s O', $date);
        return $date->format('U');
    }
}
