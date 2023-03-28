<?php

namespace App\Exceptions;

use App\Helpers\errorCodes;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class handleDatabaseException extends Exception
{
    use ApiResponse;
    protected $customCode;
    public function __construct($errorInfo, $message = null, $code = 0, $customCode = null,Exception $previous = null)
    {
        switch ($errorInfo[0]) {
            case '23502':
                $message = 'Error: a not null violation occurred.';
                $customCode = errorCodes::DATABASE_QUERY_FAILED;
                $code = 400;
                break;
            case '08006':
                $message = 'Unable to connect to the database';
                $customCode = errorCodes::DATABASE_CONNECTION_FAILED;
                $code = 500;
                break;
            default:
                $message = 'Error: '.$message;
                $customCode = errorCodes::DATABASE_UNKNOWN_ERROR;
                $code = 500;
        }

        parent::__construct($message, $code, $previous);
        $this->customCode = $customCode;
    }
    public function getCustomCode()
    {
        return $this->customCode;
    }
}
