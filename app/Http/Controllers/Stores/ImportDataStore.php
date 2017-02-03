<?php

namespace App\Http\Controllers\Stores;

use App\ImportData;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ImportDataStore extends Controller
{
    public function store($data)
    {
        $importdata = new ImportData;
        $importdata->last_updated_id = $data->last_updated_id;
        $importdata->user = $data->user;
        $importdata->save();
    }
}
