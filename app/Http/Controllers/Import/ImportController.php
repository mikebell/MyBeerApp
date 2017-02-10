<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Stores\BreweryStore;
use App\Http\Controllers\Stores\CheckinStore;
use App\Http\Controllers\Stores\BeerStore;
use App\ImportData;
use GuzzleHttp;

class ImportController extends Controller
{
    public $max_id = false;
    public $min_id = false;

    public function __construct()
    {
        $this->client_id = env('UNTAPPD_CLIENT', '');
        $this->secret_id = env('UNTAPPD_SECRET', '');
    }

    public function getData($user)
    {
        // Get last checkin id if possible.
        $last_updated_id = ImportData::where('user', $user)->first();

        // @TODO rewrite this entire pile of crap.

        if (isset($last_updated_id->last_updated_id)) {
            // This is an update run.
            do {
                $data = $this->getCheckinUpdateData($user, $this->min_id);
                $this->processData($data, $user);
                if ($this->min_id !== false) {
                    ImportData::where('user', $user)
                      ->update(['last_updated_id' => $this->min_id]);
                }
            } while ($this->min_id != false);
        } else {
            // This is a new user run.
            do {
                $data = $this->getCheckinData($user, $this->max_id);
                $this->processData($data, $user);
                ImportData::firstOrCreate([
                    'user' => $user,
                    'last' => $last_updated_id->last_updated_id,
                ]);
            } while ($this->max_id != false);
        }
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

    public function getCheckinUpdateData($user, $min_id)
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'https://api.untappd.com/v4/']);

        $res = $client->request('GET', 'user/checkins/' . $user, [
          'query' => [
            'client_id' => $this->client_id,
            'client_secret' => $this->secret_id,
            'limit' => 50,
            'min_id' => $min_id
          ],
        ]);

        if ($res->getStatusCode() == 200) {
            $data = (string) $res->getBody();
            $data_decode = json_decode($data);
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            if (isset($data_decode->response->pagination->min_id)) {
                $this->min_id = $data_decode->response->pagination->min_id;
            } else {
//                return $this->min_id = false;
            }
            return $data_decode;
        } else {
            return false;
        }
    }
}
