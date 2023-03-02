<?php
namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse{

    protected function successResponse($data=null, int $code = 200, String $message = null, array $meta = [])
    {
        $response = [
            'status'=> 'success',
            'message' => $message,
            'data' => $data,
        ];
        if (!empty($meta)) {
            $response['meta'] = $meta;
        }
        return response()->json($response, $code);
    }

    protected function errorResponse(String $message,int $code=500, String $customCode= null,$error=null)
    {
        $response = [
            'status'=>'error',
            'message' => $message,
            'errors' => $error,
            'code' => $customCode
        ];
        return response()->json($response, $code);
    }

}
