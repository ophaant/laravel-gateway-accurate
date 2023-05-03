<?php

namespace App\Http\Middleware;

use App\Interfaces\Whitelist\WhitelistInterfaces;
use App\Models\BlockIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class IpWhiteListMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected $whitelistInterfaces;
    public function __construct(WhitelistInterfaces $whitelistInterfaces)
    {
        $this->whitelistInterfaces = $whitelistInterfaces;
    }
    public function handle(Request $request, Closure $next): Response
    {
//        $ipWhitelist = ['127.0.0.1']; // Replace with your own whitelist
        $clientIp = $request->getClientIp();
        $ipWhitelist = $this->whitelistInterfaces->getByStatusEnable($clientIp);
        if (!is_array($ipWhitelist) && count($ipWhitelist) == 0) {
            return $ipWhitelist;
        }

        if (in_array($clientIp, $ipWhitelist)) {
            // If the client IP is in the whitelist, allow access
            auth()->attempt(['email' => 'admin1@mail.com', 'password' => '12345678']);
            return $next($request);
        }

        // Otherwise, check if the user is authenticated via the `auth:api` middleware
        if (auth()->guard('api')->check()) {
            return $next($request);
        }

       throw new UnauthorizedHttpException('Unauthorized', 'Unauthorized');
    }
}
