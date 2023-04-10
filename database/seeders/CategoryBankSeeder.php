<?php

namespace Database\Seeders;

use App\Models\CategoryBank;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryBankSeeder extends Seeder
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
                'category_bank_name' => 'Mandiri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $this->newUniqueId(),
                'category_bank_name' => 'BCA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $this->newUniqueId(),
                'category_bank_name' => 'BNI',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $this->newUniqueId(),
                'category_bank_name' => 'BRI',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        $data->each(function ($item) {
            CategoryBank::create($item);
        });
    }
}
