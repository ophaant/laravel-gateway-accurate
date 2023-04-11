<?php

namespace App\Repositories\Accurate;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Accurate\AccurateCustomerInterfaces;
use App\Models\Customer;
use App\Models\Database;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccurateCustomerRepository implements AccurateCustomerInterfaces
{
    use ApiResponse, HasUuids;
    public function storeCustomer(array $data, int $database)
    {
        try {
            DB::beginTransaction();
            $col = collect($data);
            $databaseRepo= app(AccurateDatabaseRepository::class);
            $databaseUuid = $databaseRepo->getDatabaseByCodeDatabase($database);
            $existingCustomers = Database::with('customers')->find($databaseUuid)->customers;

//            $existingCustomers = Customer::where('database_id', $databaseId)->get();

// loop through existing customers and delete if not in $col
            foreach ($existingCustomers as $existingCustomer) {
                $found = $col->first(function($item) use($existingCustomer) {
                    return $item['id'] === $existingCustomer->customer_id;
                });
                if (!$found) {
                    $existingCustomer->delete();
                }
            }
            $customer = $col->map(function($item,$key) use($database,$databaseUuid) {
                return [
                    'customer_id' => $item['id'],
                    'database_id' => $databaseUuid,
                    'customer_no' => $item['customerNo'],
                    'customer_name' => $item['name'],
                    'id'=>$this->newUniqueId(),
                    'code_array'=>$database.'-'.$key
                ];
            })
                ->chunk(1000)
                ->each(function (Collection $chunk) {
                    Customer::upsert($chunk->all(), 'code_array');
                });
            DB::commit();
            return $this->successResponse($customer, 200, 'Customer Store Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function getCustByName(string $name, int $code_database)
    {
        try {
            $databaseRepo= app(AccurateDatabaseRepository::class);
            $databaseUuid = $databaseRepo->getDatabaseByCodeDatabase($code_database);
            $customerNo = Customer::where('customer_name',$name)->with(['database' => function ($query) use ($code_database) {
                $query->where('code_database', $code_database);
            }])->first();
//            $customerNo = Database::with('customers')->find($databaseUuid)->customers->where('customer_name', $name)->first();
            if (!$customerNo) {
                return $this->errorResponse('Error: Customer No Accurate Not Found.', 404, errorCodes::ACC_TOKEN_NOT_FOUND);
            }
            return $customerNo->customer_no;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

}
