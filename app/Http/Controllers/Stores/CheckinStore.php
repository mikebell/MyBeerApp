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
          ->where('id', '=', $data->checkin_id)
          ->where('user', '=', $user)
          ->exists();

        if (!$result) {
            $checkin = new Checkin;
            $checkin->id = $data->checkin_id;

            // Convert date into unix timestamp;
            $checkin->created_at = $this->convertDate($data->created_at);

            $checkin->rating_score = $data->rating_score;

            $checkin->bid = $data->beer->bid;

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
