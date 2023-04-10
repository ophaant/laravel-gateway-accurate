<?php

namespace Database\Seeders;

use App\Models\AccountBankType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Seeder;

class AccountBankTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use HasUuids;
    public function run(): void
    {
        $data = collect([
            [
                'id' => $this->newUniqueId(),
                'account_type_name' => 'Debit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $this->newUniqueId(),
                'account_type_name' => 'Kredit',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        $data->each(function ($item) {
            AccountBankType::create($item);
        });
    }
}
