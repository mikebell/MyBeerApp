<?php
/**
 * Created by PhpStorm.
 * User: digital
 * Date: 24/01/2017
 * Time: 13:29
 */

namespace App\Http\Controllers\UserData;

use App\Checkin;

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
}
