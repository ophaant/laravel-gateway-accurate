<?php

namespace App\Repositories\Accurate;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Accurate\AccurateTokenInterfaces;
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
            $token = Token::updateOrcreate(
                ['token_type' => $data['token_type'],
                ],
                ['access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'expires_in' => $data['expires_in'],
                    'scope' => $data['scope']
                ]
            );
            DB::commit();
            return $token;
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

    public function getRefreshToken()
    {
        try {
            $token = Token::select('refresh_token')->first();
            if (!$token) {
                return $this->errorResponse('Error: token not found.', 404, errorCodes::ACC_TOKEN_NOT_FOUND);
            }
            return $token->refresh_token;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
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

        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
    public function checkToken()
    {
        try {
            return Token::count();
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
}
