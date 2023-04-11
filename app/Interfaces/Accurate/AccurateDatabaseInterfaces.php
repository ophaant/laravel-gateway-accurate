<?php

namespace App\Interfaces\Accurate;

interface AccurateDatabaseInterfaces
{
    public function storeDatabase(array $data);
    public function getDatabase();
    public function getDatabaseByCodeDatabase(int $code);
}
