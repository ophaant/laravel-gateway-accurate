<?php

namespace App\Repositories;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\AccurateCustomerInterfaces;
use App\Models\Customer;
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

            $existingCustomers = Customer::where('code_database', $database)->get();

// loop through existing customers and delete if not in $col
            foreach ($existingCustomers as $existingCustomer) {
                $found = $col->first(function($item) use($existingCustomer) {
                    return $item['id'] === $existingCustomer->customer_id;
                });
                if (!$found) {
                    $existingCustomer->delete();
                }
            }
            $customer = $col->map(function($item,$key) use($database) {
                return [
                    'customer_id' => $item['id'],
                    'code_database' => $database,
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
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }
    }


}
