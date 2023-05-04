<?php

namespace App\Services\Auth;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Auth\AuthInterfaces;
use App\Interfaces\Auth\RoleInterfaces;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class RoleServices
{
    use ApiResponse;

    protected $roleInterfaces;

    public function __construct(RoleInterfaces $roleInterfaces)
    {
        $this->roleInterfaces = $roleInterfaces;
    }

    public function getAll()
    {
        try {
            $data = $this->roleInterfaces->getAll();

            if (!is_object($data)) {
                return $data;
            }
            return $this->successResponse($data, 200, 'Role List Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function create($request)
    {
        try {
            $data = $this->roleInterfaces->create($request);

            if (!is_object($data)) {
                return $data;
            }
            return $this->successResponse($data, 200, 'Role Created Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function getById($id)
    {
        try {
            $data = $this->roleInterfaces->getById($id);

            if (!is_object($data)) {
                return $data;
            }
            return $this->successResponse($data, 200, 'Role Details Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function update($id, array $data)
    {
        try {
            $data = $this->roleInterfaces->update($id, $data);

            if (!is_object($data)) {
                return $data;
            }
            return $this->successResponse($data, 200, 'Role Updated Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $data = $this->roleInterfaces->delete($id);

            if ($data->getStatusCode() != 200) {
                return $data;
            }
            return $this->successResponse(null, 200, 'Role Deleted Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
}
