<?php
/**
 * Created by PhpStorm.
 * User: digital
 * Date: 24/01/2017
 * Time: 13:29
 */

namespace App\Http\Controllers\UserData;

use App\Checkin;
use App\Http\Controllers\Stores\BeerStore;
use App\Http\Controllers\Stores\CheckinStore;
use Illuminate\Support\Facades\DB;
use App\Beer;

class UserDataGenerator
{
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get total number of checkins.
     *
     * @return mixed
     */
    public function totalCheckins()
    {
        return Checkin::where('user', $this->user)->get()->count();
    }

    /**
     * Get the years a user has been active.
     *
     * @return array
     */
    public function getYearsActive()
    {
        $years = [];

        $first = new \DateTime();
        $first->setTimestamp(Checkin::where('user', $this->user)->min('created_at'));
        $first = $first->format('Y');

        $last = new \DateTime();
        $last->setTimestamp(Checkin::where('user', $this->user)->max('created_at'));
        $last = $last->format('Y');


        foreach (range($first, $last) as $number) {
            $years[] = $number;
        }

        return $years;
    }

    public function getCheckinsByYear($user, $year)
    {
        // get first and last second of year.
        $first = strtotime('01/01/' . $year);
        $next_year = $year + 1;
        $last = strtotime('01/01/' . $next_year) - 1;

        $checkins = DB::table('checkin')
          ->whereBetween('created_at', [$first, $last])
          ->orderBy('created_at', 'desc')
          ->get();


        foreach ($checkins as $key => $checkin) {
            $beer = Beer::where('id', $checkin->bid)->get();

            foreach ($beer as $item) {
                $checkins[$key]->beer = new \stdClass();
                $checkins[$key]->beer->name = $item->name;
                $checkins[$key]->beer->abv = $item->abv;
                $checkins[$key]->beer->label = $item->label;

            }
            $checkins[$key]->date = date('d/m/Y', $checkins[$key]->created_at);
        }

        $checkins->year = $year;

        return $checkins;
    }
}
