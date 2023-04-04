<?php

namespace App\Interfaces;

interface AccurateCustomerInterfaces
{
    public function storeCustomer(array $data, int $database);
    public function getCustByName(string $name, int $database);
}
