<?php

namespace App\Interfaces\Whitelist;

interface WhitelistInterfaces
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
    public function getByStatusEnable($ip);
    public function getByIp($ip);
}
