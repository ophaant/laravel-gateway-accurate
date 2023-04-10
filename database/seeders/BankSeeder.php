<?php

namespace Database\Seeders;

use App\Models\AccountBankType;
use App\Models\Bank;
use App\Models\CategoryBank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use HasUuids;
    public function run(): void
    {
        $accountType = AccountBankType::where('account_type_name','Debit')->value('id');
        $data = collect([
            [
                'id' => $this->newUniqueId(),
                'account_id'=>11010214,
                'account_name'=>'Bank BRI JIR',
                'account_number' => 148401000020306,
                'account_type_id'=> $accountType,
                'category_bank_id'=> CategoryBank::findCategoryByName('BRI')->value('id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $this->newUniqueId(),
                'account_id'=>11010215,
                'account_name'=>'Bank BRI JIR NEW',
                'account_number' => 113101000292562,
                'account_type_id'=> $accountType,
                'category_bank_id'=> CategoryBank::findCategoryByName('BRI')->value('id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $this->newUniqueId(),
                'account_id'=>11010202,
                'account_name'=>'Bank BCA OUT JIR',
                'account_number' => 5221771177,
                'account_type_id'=> $accountType,
                'category_bank_id'=> CategoryBank::findCategoryByName('BCA')->value('id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $this->newUniqueId(),
                'account_id'=>11010216,
                'account_name'=>'Bank BCA JIR NEW',
                'account_number' => 5221612231,
                'account_type_id'=> $accountType,
                'category_bank_id'=> CategoryBank::findCategoryByName('BCA')->value('id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $this->newUniqueId(),
                'account_id'=>11010212,
                'account_name'=>'Bank BNI JIR',
                'account_number' => 2020060619,
                'account_type_id'=> $accountType,
                'category_bank_id'=> CategoryBank::findCategoryByName('BNI')->value('id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $this->newUniqueId(),
                'account_id'=>11010217,
                'account_name'=>'Bank MANDIRI JIR NEW',
                'account_number' => 1560016142905,
                'account_type_id'=> $accountType,
                'category_bank_id'=> CategoryBank::findCategoryByName('Mandiri')->value('id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $this->newUniqueId(),
                'account_id'=>11010207,
                'account_name'=>'Bank MANDIRI JIR',
                'account_number' => 1560016036354,
                'account_type_id'=> $accountType,
                'category_bank_id'=> CategoryBank::findCategoryByName('Mandiri')->value('id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
        $data->each(function ($item) {
            Bank::create($item);
        });
    }
}
