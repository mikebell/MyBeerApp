<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Stores\BreweryStore;
use App\Http\Controllers\Stores\CheckinStore;
use App\Http\Controllers\Stores\BeerStore;
use GuzzleHttp;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->client_id = env('UNTAPPD_CLIENT', '');
        $this->secret_id = env('UNTAPPD_SECRET', '');
    }

    public function getData($user)
    {
        // @TODO write using the correct api endpoint.

        // $data->meta->code = 200; all good.

        // $data->response->total_count / 50 - gets number of time need to loop through offset

//        $client = new GuzzleHttp\Client();

        $user = $this->getMockUserData();
        $total_checkins = $user->response->user->stats->total_checkins;

        $offset = 0;

        while ($offset <= $total_checkins) {
            $data = $this->getCheckinData($user, $offset);
            if ($data !== false) {
                $data = json_decode($data);
                $this->processData($data, $user);
            }
            $offset = $offset + 50;
        }
//        return $this->getMockData();
    }

    public function getMockCheckinData()
    {
        $file = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/temp/data.json');
        $file = json_decode($file);

        return $file;
    }

    public function getMockUserData()
    {
        $file = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/temp/userdata.json');
        $file = json_decode($file);

        return $file;
    }

    public function processData($data, $user)
    {
        foreach ($data->response->beers->items as $beer) {
            $checkin = new CheckinStore();
            $checkin->store($beer, $user);

            $beer_data = new BeerStore();
            $beer_data->store($beer);

            $brewery_data = new BreweryStore();
            $brewery_data->store($beer);
        }

        return true;
    }
    
    public function getUserData($user)
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'https://api.untappd.com/v4/']);
        $res = $client->request('GET', 'user/info/' . $user, [
            'query' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->secret_id,
                'compact' => true
            ],
        ]);

        if ($res->getStatusCode() == 200) {
            return (string) $res->getBody();
        } else {
            return false;
        }
    }

    public function getCheckinData($user, $offset)
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'https://api.untappd.com/v4/']);
        $res = $client->request('GET', 'user/beers/' . $user, [
            'query' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->secret_id,
                'limit' => 50,
                'offset' => $offset
            ],
        ]);

        if ($res->getStatusCode() == 200) {
            return (string) $res->getBody();
        } else {
            return false;
        }
    }
}
