<?php
/**
 * Created by PhpStorm.
 * User: digital
 * Date: 03/02/2017
 * Time: 09:59
 */

namespace app\Http\Controllers\ImportMock;


class ImportMock
{
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
}
