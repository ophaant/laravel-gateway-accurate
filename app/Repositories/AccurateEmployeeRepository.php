<?php

namespace App\Repositories;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\AccurateEmployeeInterfaces;
use App\Models\Employee;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccurateEmployeeRepository implements AccurateEmployeeInterfaces
{
    use ApiResponse, HasUuids;
    public function storeEmployee(array $data, int $database)
    {
        try {
            DB::beginTransaction();
            $col = collect($data);

            $existingEmployees = Employee::where('code_database', $database)->get();

// loop through existing customers and delete if not in $col
            foreach ($existingEmployees as $existingEmployee) {
                $found = $col->first(function($item) use($existingEmployee) {
                    return $item['id'] === $existingEmployee->customer_id;
                });
                if (!$found) {
                    $existingEmployee->delete();
                }
            }
            $customer = $col->map(function($item,$key) use($database) {
                return [
                    'employee_id' => $item['id'],
                    'code_database' => $database,
                    'employee_no' => $item['number'],
                    'employee_name' => $item['name'],
                    'id'=>$this->newUniqueId(),
                    'code_array'=>$database.'-'.$key
                ];
            })
                ->chunk(1000)
                ->each(function (Collection $chunk) {
                    Employee::upsert($chunk->all(), 'code_array');
                });
            DB::commit();
            return $this->successResponse($customer, 200, 'Employee Store Successfully');
        }catch (\PDOException $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }


}
