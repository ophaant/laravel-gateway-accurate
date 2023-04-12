<?php

namespace App\Interfaces;

interface CategoryBankInterfaces
{
    public function getAll();
    public function store(array $data);
    public function update(array $data, $id);
    public function delete($id);
    public function findById($id);
}
