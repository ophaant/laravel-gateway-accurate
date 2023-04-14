<?php

namespace App\Interfaces\Bank;

interface BankInterfaces
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
}