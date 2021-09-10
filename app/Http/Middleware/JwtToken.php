<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JwtToken extends BaseMiddleware
{
    use ApiResponse;

    /**
     * @Notes:前置操作
     *
     * @param $request
     * @param Closure $next
     * @return JsonResponse|Response|mixed
     * @throws AuthorizationException
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 10:50
     */
    public function handle($request, Closure $next)
    {
        try {
            $this->checkForToken($request);

            if ($this->auth->parseToken()->authenticate()) {
                return $next($request);
            }

            throw new AuthorizationException('token 非法，请重新登录',FoundationResponse::HTTP_FORBIDDEN);
        } catch (\Exception $exception) {
            if ($exception instanceof UnauthorizedHttpException) {
                throw new AuthorizationException('token 未提供',FoundationResponse::HTTP_FORBIDDEN);
            } else if ($exception instanceof TokenInvalidException) {
                throw new AuthorizationException('token 无效',FoundationResponse::HTTP_FORBIDDEN);
            } else if ($exception instanceof TokenBlacklistedException) {
                throw new AuthorizationException('token 已退出，请重新登录',FoundationResponse::HTTP_FORBIDDEN);
            } else if ($exception instanceof TokenExpiredException) {
                try {
                    $token = $this->auth->refresh();

                    return $this->setAuthenticationHeader($next($request), 'Bearer ' . $token);
                } catch (\Exception $exception) {
                    throw new AuthorizationException('token 过期，请重新登录',FoundationResponse::HTTP_FORBIDDEN);
                }
            } else {
                return $this->failed($exception->getMessage(), FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
}
