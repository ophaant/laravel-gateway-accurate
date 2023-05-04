<?php

namespace App\Interfaces\Auth;

interface PermissionInterfaces
{
    public function getAll();
    public function getById($id);
    public function update($id, array $data);
    public function create(array $data);
    public function delete($id);
}
