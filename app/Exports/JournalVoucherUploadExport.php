<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class JournalVoucherUploadExport implements FromArray, WithCustomCsvSettings
{

    protected $data;
    protected $delimiter;

    public function __construct(array $data, String $delimiter)
    {
        $this->data = $data;
        $this->delimiter = $delimiter;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' =>  $this->delimiter
        ];
    }
}
