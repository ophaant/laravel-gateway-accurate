<?php

namespace App\Repositories;

use App\Helpers\errorCodes;
use App\Interfaces\AccurateAuthInterfaces;
use App\Models\Token;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccurateAuthRepository implements AccurateAuthInterfaces
{
    use ApiResponse;
    public function storeToken(array $data)
    {
        DB::beginTransaction();
        try {
            $saveToken = Token::updateOrcreate(
                ['token_type' => $data['token_type'],
                ],
                ['access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'expires_in' => $data['expires_in'],
                    'scope' => $data['scope']
                ]
            );
            DB::commit();
            return $this->successResponse($saveToken, 200, 'success');
        } catch (Exception $e) {
            DB::rollBack();
            if ($e->errorInfo[0] == '23502') {
                Log::error($e->getMessage());
                return $this->errorResponse('Error: a not null violation occurred.', 500, errorCodes::DATABASE_QUERY_FAILED);
            } elseif ($e->errorInfo[0] == '08006') {
                Log::error($e->getMessage());
                return $this->errorResponse('Unable to connect to the database', 500, errorCodes::DATABASE_CONNECTION_FAILED);
            } else {
                Log::error($e->getMessage());
                return $this->errorResponse('Error: an unexpected error occurred.', 500, errorCodes::DATABASE_UNKNOWN_ERROR);
            }
        }
    }
}
