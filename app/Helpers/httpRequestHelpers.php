<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

if (!function_exists('sendReq')) {
    function sendReq($method = null, $url = null, $params = null, $form = false, $basicAuth = false, $token = false)
    {
        try {

            $response = Http::timeout(env('TIMEOUT_HTTP_REQUEST'));
            if ($form) {
                $response = $response->asForm();
            }
            if ($basicAuth) {
                $response = $response->withBasicAuth(env('ACCURATE_CLIENT_ID'), env('ACCURATE_CLIENT_SECRET'));
            }
            if ($token) {
                $response = $response->withToken(session('data.access_token'));
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
