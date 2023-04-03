<?php

namespace App\Interfaces;

interface AccurateDatabaseInterfaces
{
    public function storeDatabase(array $data);
    public function getDatabase();
    public function getDatabaseByCodeDatabase(int $code);
}
