<?php

namespace App\Traits;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Models\Database;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDOException;

trait checkUrlAccurate
{

    function checkDatabaseAccurate($db = '')
    {
        try {
            $database = Database::where('code_database', $db)->whereIn('name', ['PT. JITU INDO RITNAS ', 'PT. WINIT INDO WISESA'])->get();
            if (count($database) != 0) {
                return config('accurate.public_url');
            } else {
                return config('accurate.zeus_url');
            }
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


}
