<?php

namespace App\Repositories;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\AccurateDatabaseInterfaces;
use App\Models\Database;
use App\Models\Token;
use App\Traits\ApiResponse;
use Dflydev\DotAccessData\Data;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccurateDatabaseRepository implements AccurateDatabaseInterfaces
{
    use ApiResponse;
    public function storeDatabase(array $data)
    {
        try {
            DB::beginTransaction();

            $col = collect($data);
            $arrayCount = $col->count();
            $databaseCount = Database::count();
            if ($databaseCount > $arrayCount) {
                Database::truncate();
            }
            $col->each(function ($item, $key) {

                Database::updateOrCreate([
                    'code_database' => $item['id']
                ], [
                    'name' => $item['alias']
                ]);
            });
            DB::commit();
            return $this->successResponse(null, 200, 'Database Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }catch (\PDOException $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }
    }

    public function getDatabase()
    {
        try {
            $databases = Database::all();
            if (!$databases) {
                return $this->errorResponse('Error: Database Accurate Not Found.', 404, errorCodes::ACC_TOKEN_NOT_FOUND);
            }
            return $databases;
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }
    }
}
