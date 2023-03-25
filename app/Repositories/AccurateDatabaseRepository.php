<?php

namespace App\Repositories;

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
        DB::beginTransaction();
        try {

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
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            if ($e->errorInfo[0] == '23502') {
                Log::error($e->getMessage());
                return $this->errorResponse('Error: a not null violation occurred.', 500, errorCodes::DATABASE_QUERY_FAILED);
            } elseif ($e->errorInfo[0] == '08006') {
                Log::error($e->getMessage());
                return $this->errorResponse('Unable to connect to the database', 500, errorCodes::DATABASE_CONNECTION_FAILED);
            } else {
                Log::error($e->getMessage());
                return $this->errorResponse('Error: '.$e->getMessage(), 500, errorCodes::DATABASE_UNKNOWN_ERROR);
            }
        }
    }

    public function getDatabase()
    {
        $databases = Database::get();
        if (!$databases) {
            return $this->errorResponse('Error: Database Accurate Not Found.', 404, errorCodes::ACC_TOKEN_NOT_FOUND);
        }
        return $databases;
    }
}
