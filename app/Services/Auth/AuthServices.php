<?php

namespace App\Services\Auth;

use App\Exceptions\handleDatabaseException;
use App\Exports\JournalVoucherUploadExport;
use App\Helpers\errorCodes;
use App\Http\Requests\RegisterRequest;
use App\Imports\JournalVoucherUploadImport;
use App\Interfaces\Auth\AuthInterfaces;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PDOException;

class AuthServices
{
    use ApiResponse;

    protected $authInterfaces;

    public function __construct(AuthInterfaces $authInterfaces)
    {
        $this->authInterfaces = $authInterfaces;
    }

    public function register($request)
    {
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = $this->authInterfaces->create($input);
            $user->givePermissions(explode(',', $request->permissions));
            $data['token'] =  $user->createToken('MyApp')->accessToken;
            $data['name'] =  $user->name;
            return $this->successResponse($data, 200, 'User Register Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function login($request)
    {
        try {
            if (!auth()->attempt($request->only('email','password'))) {
                return $this->errorResponse('Invalid Credentials', 401, errorCodes::CODE_WRONG_ERROR);
            }
            $token = auth()->user()->createToken('Laravel8PassportAuth')->accessToken;
            return $this->successResponse(['token' => $token], 200, 'User Login Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function logout($request)
    {
        try {
            $user = $request->user()->token()->revoke();
            return $this->successResponse([], 200, 'User Logout Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
}
