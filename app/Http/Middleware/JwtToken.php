<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JwtToken extends BaseMiddleware
{
    use ApiResponse;

    public function handle($request, Closure $next)
    {
        try {
            $this->checkForToken($request);

            if ($this->auth->parseToken()->authenticate()) {
                return $next($request);
            }

            return $this->failed('token 非法，请重新登录', 403);
        } catch (\Exception $exception) {
            if ($exception instanceof UnauthorizedHttpException) {
                return $this->failed('token 未提供', 403);
            } else if ($exception instanceof TokenInvalidException) {
                return $this->failed('token 无效', 403);
            } else if ($exception instanceof TokenBlacklistedException) {
                return $this->failed('token 已退出，请重新登录', 403);
            } else if ($exception instanceof TokenExpiredException) {
                try {
                    $token = $this->auth->refresh();

                    return $this->setAuthenticationHeader($next($request), 'Bearer ' . $token);
                } catch (\Exception $exception) {
                    return $this->failed('token 过期，请重新登录', 403);
                }
            } else {
                return $this->failed($exception->getMessage(), 500);
            }
        }
    }
}
