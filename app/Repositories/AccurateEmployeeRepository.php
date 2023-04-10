<?php

namespace App\Repositories;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\AccurateEmployeeInterfaces;
use App\Models\Database;
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

            $databaseRepo= app(AccurateDatabaseRepository::class);
            $databaseUuid = $databaseRepo->getDatabaseByCodeDatabase($database);
            $existingEmployees = Database::with('customers')->find($databaseUuid)->employees;

// loop through existing customers and delete if not in $col
            foreach ($existingEmployees as $existingEmployee) {
                $found = $col->first(function($item) use($existingEmployee) {
                    return $item['id'] === $existingEmployee->customer_id;
                });
                if (!$found) {
                    $existingEmployee->delete();
                }
            }
            $customer = $col->map(function($item,$key) use($database,$databaseUuid) {
                return [
                    'employee_id' => $item['id'],
                    'database_id' => $databaseUuid,
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

    public function getEmpByName(string $name, int $code_database)
    {
        try {
            $databaseRepo= app(AccurateDatabaseRepository::class);
            $databaseUuid = $databaseRepo->getDatabaseByCodeDatabase($code_database);
            $employeeNo = Employee::where('employee_name',$name)->with(['database' => function ($query) use ($code_database) {
                $query->where('code_database', $code_database);
            }])->first();
//            $employeeNo = Database::with('employees')->find($databaseUuid)->employees->where('employee_name', $name)->first();
            if (!$employeeNo) {
                return $this->errorResponse('Error: Employee No Accurate Not Found.', 404, errorCodes::DB_EMP_NOT_FOUND);
            }
            return $employeeNo->employee_no;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }


}
