<?php

namespace App\Interfaces;

interface AccurateTokenInterfaces
{
    public function storeToken(array $data);
    public function getRefreshToken();
    public function getAccessToken();
    public function checkToken();
}
