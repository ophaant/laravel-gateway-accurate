<?php

namespace App\Interfaces;

interface AccurateEmployeeInterfaces
{
    public function storeEmployee(array $data, int $database);
    public function getEmpByName(string $name, int $database);
}
