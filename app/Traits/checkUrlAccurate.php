<?php
namespace App\Traits;

use App\Models\Database;

trait checkUrlAccurate{

    function checkDatabaseAccurate($db = '')
    {
        $database = Database::where('code_database', $db)->whereIn('name', ['PT. JITU INDO RITNAS ','PT. WINIT INDO WISESA'])->get();
        if (count($database) != 0) {
            return config('accurate.public_url');
        } else {
            return config('accurate.zeus_url');
        }
    }

}
