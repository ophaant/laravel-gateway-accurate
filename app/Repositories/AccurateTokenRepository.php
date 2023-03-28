<?php

namespace App\Repositories;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\AccurateTokenInterfaces;
use App\Models\Token;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccurateTokenRepository implements AccurateTokenInterfaces
{
    use ApiResponse;
    public function storeToken(array $data)
    {
        try {
            DB::beginTransaction();
            Token::updateOrcreate(
                ['token_type' => $data['token_type'],
                ],
                ['access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'expires_in' => $data['expires_in'],
                    'scope' => $data['scope']
                ]
            );
            DB::commit();
            return $this->successResponse(null, 200, 'Token Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }
    }

    public function getRefreshToken()
    {
        try {
            $token = Token::select('refresh_token')->first();
            if (!$token) {
                return $this->errorResponse('Error: token not found.', 404, errorCodes::ACC_TOKEN_NOT_FOUND);
            }
            return $token->refresh_token;
        }catch (\Exception $e) {
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }
    }

    public function getAccessToken()
    {
        try {
            $token = Token::select('access_token')->first();
            if (!$token) {
                return $this->errorResponse('Error: token not found.', 404, errorCodes::ACC_TOKEN_NOT_FOUND);
            }
            return $token->access_token;

        }catch (\Exception $e){
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }
    }
}
