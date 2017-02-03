<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Stores\BreweryStore;
use App\Http\Controllers\Stores\CheckinStore;
use App\Http\Controllers\Stores\BeerStore;
use GuzzleHttp;

class ImportController extends Controller
{
    public $max_id = false;

    public function __construct()
    {
        $this->client_id = env('UNTAPPD_CLIENT', '');
        $this->secret_id = env('UNTAPPD_SECRET', '');
    }

    public function getData($user)
    {
        do {
            $data = $this->getCheckinData($user, $this->max_id);
            $this->processData($data, $user);
        } while ($this->max_id != false);
    }

    public function processData($data, $user)
    {
        foreach ($data->response->checkins->items as $beer) {
            $checkin = new CheckinStore();
            $checkin->store($beer, $user);

            $beer_data = new BeerStore();
            $beer_data->store($beer->beer);

            $brewery_data = new BreweryStore();
            $brewery_data->store($beer->brewery);
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
//            file_put_contents('temp/' . $user . '-user-' . time() . '.json', (string) $res->getBody());
            return (string) $res->getBody();
        } else {
            return false;
        }
    }

//    public function getCheckinData($user, $offset)
//    {
//        $client = new GuzzleHttp\Client(['base_uri' => 'https://api.untappd.com/v4/']);
//        $res = $client->request('GET', 'user/beers/' . $user, [
//            'query' => [
//                'client_id' => $this->client_id,
//                'client_secret' => $this->secret_id,
//                'limit' => 50,
//                'offset' => $offset
//            ],
//        ]);
//
//        if ($res->getStatusCode() == 200) {
//            return (string) $res->getBody();
//        } else {
//            return false;
//        }
//    }

    public function getCheckinData($user, $max_id)
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'https://api.untappd.com/v4/']);

        if (isset($max_id)) {
            $res = $client->request('GET', 'user/checkins/' . $user, [
              'query' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->secret_id,
                'limit' => 50,
                'max_id' => $max_id
              ],
            ]);
        } else {
            $res = $client->request('GET', 'user/checkins/' . $user, [
              'query' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->secret_id,
                'limit' => 50,
              ],
            ]);
        }

        if ($res->getStatusCode() == 200) {
            // @TODO remove this or wrap in debug env var.
            if ($this->max_id == false) {
                $chekin_id = 'start';
            } else {
                $chekin_id = $this->max_id;
            }

//            file_put_contents('temp/' . $user  . '-' . $chekin_id . '-checkins-' . time() . '.json', (string) $res->getBody());

            $data = (string) $res->getBody();
            $data_decode = json_decode($data);
            if ($this->max_id == $data_decode->response->pagination->max_id) {
                $this->max_id = false;
            } else {
                $this->max_id = $data_decode->response->pagination->max_id;
            }
            return $data_decode;
        } else {
            return false;
        }
    }
}
