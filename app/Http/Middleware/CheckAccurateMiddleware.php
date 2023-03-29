<?php

namespace App\Http\Middleware;

use App\Helpers\errorCodes;
use App\Interfaces\AccurateTokenInterfaces;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccurateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    use ApiResponse;
    protected $accurateTokenInterfaces;
    public function __construct(AccurateTokenInterfaces $accurateAuthInterfaces)
    {
        $this->accurateTokenInterfaces = $accurateAuthInterfaces;
    }
    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->accurateTokenInterfaces->checkToken();
        if ($token === 0) {
            return $this->errorResponse('Need Authenticate Your Accurate First', 401, errorCodes::ACC_AUTH_INVALID);
        }
        return $next($request);
    }
}
