<?php

namespace App\Interfaces\Accurate;

interface AccurateSessionInterfaces
{
    public function storeSessionAccurate(array $data);
    public function getSessionAccurate(int $code_database);
}
