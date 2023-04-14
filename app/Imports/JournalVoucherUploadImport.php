<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class JournalVoucherUploadImport implements WithCustomCsvSettings
{
    private $delimiter;

    public function __construct($delimiter){
        $this->delimiter = $delimiter;
    }

    /**
     * @param Collection $collection
     */
    // public function collection(Collection $collection)
    // {
    // }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => $this->delimiter,
        ];
    }

}
