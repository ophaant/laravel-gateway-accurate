<?php

namespace App\Repositories\Accurate;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Accurate\AccurateSessionInterfaces;
use App\Models\Database;
use App\Models\Session;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccurateSessionRepository implements AccurateSessionInterfaces
{
    use ApiResponse, HasUuids;
    public function storeSessionAccurate(array $data)
    {
        try {
            DB::beginTransaction();
            $now = Carbon::now();
            $col = collect($data);

            $session = $col->map(function($item,$key) use($now) {
                return [
                    'database_id' => $item['database_id'],
                    'session' =>$item['session'],
                    'expire_in' => $now->addMinute(1440),
                    'id'=>$this->newUniqueId(),
                    'code_array'=>'session-'.$key
                ];
            })
                ->chunk(1000)
                ->each(function (Collection $chunk) {
                    Session::upsert($chunk->all(), 'code_array');
                });
            DB::commit();
            return $this->successResponse($session, 200, 'Session Store Successfully');
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

    public function getSessionAccurate($code_database)
    {
        try {
            $databaseRepo= app(AccurateDatabaseRepository::class);
            $databaseUuid = $databaseRepo->getDatabaseByCodeDatabase($code_database);
            $databases = Database::find($databaseUuid)->session;
            if (!$databases) {
                return $this->errorResponse('Error: Session Accurate Not Found.', 404, errorCodes::ACC_TOKEN_NOT_FOUND);
            }
            return $databases->session;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
}
