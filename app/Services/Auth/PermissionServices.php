<?php

namespace App\Services\Auth;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Auth\PermissionInterfaces;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class PermissionServices
{
    use ApiResponse;

    protected $permissionInterfaces;

    public function __construct(PermissionInterfaces $permissionInterfaces)
    {
        $this->permissionInterfaces = $permissionInterfaces;
    }

    public function getAll()
    {
        try {
            $data = $this->permissionInterfaces->getAll();

            if (!is_object($data)) {
                return $data;
            }
            return $this->successResponse($data, 200, 'Permission List Successfully');
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
            $data = $this->permissionInterfaces->create($request);

            if (!is_object($data)) {
                return $data;
            }
            return $this->successResponse($data, 200, 'Permission Created Successfully');
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
            $data = $this->permissionInterfaces->getById($id);

            if (!is_object($data)) {
                return $data;
            }
            return $this->successResponse($data, 200, 'Permission Details Successfully');
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
            $data = $this->permissionInterfaces->update($id, $data);

            if (!is_object($data)) {
                return $data;
            }
            return $this->successResponse($data, 200, 'Permission Updated Successfully');
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
            $data = $this->permissionInterfaces->delete($id);

            if ($data->getStatusCode() != 200) {
                return $data;
            }
            return $this->successResponse(null, 200, 'Permission Deleted Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
}
