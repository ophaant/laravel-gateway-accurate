<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

if (!function_exists('sendReq')) {
    function sendReq($method = null, $url = null, $params = null, $form = false, $basicAuth = false, $token = null)
    {
        try {

            $response = Http::timeout(env('TIMEOUT_HTTP_REQUEST'));
            if ($form) {
                $response = $response->asForm();
            }
            if ($basicAuth) {
                $response = $response->withBasicAuth(config('accurate.client_id'), config('accurate.client_secret'));
            }
            if ($token) {
                $response = $response->withToken($token);
            }
            $response = $response->{$method}($url, $params);
            $data = $response->json();
            $data['http_code'] = $response->getStatusCode();
            return $data;
        } catch (Throwable $th) {
            return [
                'status' => 'error',
                'http_code' => 500,
                'message' => 'Server Unavailable'
            ];
        }
    }
}
