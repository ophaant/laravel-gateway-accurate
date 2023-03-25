<?php

namespace App\Repositories;

use App\Helpers\errorCodes;
use App\Interfaces\AccurateSessionInterfaces;
use App\Models\Session;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccurateSessionRepository implements AccurateSessionInterfaces
{
    use ApiResponse;
    public function storeSessionAccurate(array $data)
    {
        DB::beginTransaction();
        try {
            $now = Carbon::now();
            $col = collect($data);
            $col->each(function ($item, $key) use($now) {

                Session::updateOrCreate([
                    'code_database' => $item['code_database']
                ], [
                    'session' =>$item['session'],
                    'expire_in' => $now->addMinute(1440)
                ]);
            });
            DB::commit();
            return $this->successResponse(null, 200, 'Session Store Successfully');
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

    public function getSessionAccurate()
    {
        $databases = Session::all();
        if (!$databases) {
            return $this->errorResponse('Error: Session Accurate Not Found.', 404, errorCodes::ACC_TOKEN_NOT_FOUND);
        }
        return $databases;
    }
}
