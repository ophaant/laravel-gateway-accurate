<?php

namespace App\Interfaces\Auth;

interface AuthInterfaces
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function delete($id);
}
