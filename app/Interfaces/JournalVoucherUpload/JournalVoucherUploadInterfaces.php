<?php

namespace App\Interfaces\JournalVoucherUpload;

interface JournalVoucherUploadInterfaces
{
    public function getAll();
    public function create(array $data);
    public function delete($id);
}
