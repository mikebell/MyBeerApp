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

    public function totalCheckins() {
        return Checkin::where('user', $this->user)->get()->count();
    }
}
