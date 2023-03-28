<?php

namespace App\Repositories;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\AccurateSessionInterfaces;
use App\Models\Session;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccurateSessionRepository implements AccurateSessionInterfaces
{
    use ApiResponse;
    public function storeSessionAccurate(array $data)
    {
        try {
            DB::beginTransaction();
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
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }
    }

    public function getSessionAccurate($code_database)
    {
        try {
            $databases = Session::where('code_database', $code_database)->first(['session']);
            if (!$databases) {
                return $this->errorResponse('Error: Session Accurate Not Found.', 404, errorCodes::ACC_TOKEN_NOT_FOUND);
            }
            return $databases->session;
        }catch (\Exception $e) {
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }
    }
}
