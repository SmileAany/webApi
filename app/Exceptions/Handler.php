<?php

namespace App\Exceptions;


use App\Traits\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * 获取到返回异常的code值
     * @param Throwable $e
     * @return int
     */
    public function getExceptionStatusCode(Throwable $e) : int
    {
        $statusCode = FoundationResponse::HTTP_INTERNAL_SERVER_ERROR;

        if (isset($e->status) && $e->status) {
            $statusCode = $e->status;
        }

        if (method_exists($e,'getStatusCode')) {
            $statusCode = $e->getStatusCode();
        }

        return intval($statusCode);
    }

    public function render($request, Throwable $e)
    {
        $statusCode = $this->getExceptionStatusCode($e);

        if ($e instanceof NotFoundHttpException) {
            return $this->notFound();
        } else if ($e instanceof ValidationException) {
            return $this->errors('接口验证异常',$e->errors(),$statusCode);
        } else if ($e instanceof MethodNotAllowedHttpException) {
            if (app()->isLocal()) {
                return $this->failed($e->getMessage());
            }

            return $this->notFound();
        } else if ($e instanceof AuthenticationException) {

        } else if ($e instanceof ThrottleRequestsException){
            $headers = $e->getHeaders();
            $seconds = $headers['Retry-After'] ?? 0;

            return $this->failed('请求频率太高，请'.$seconds.'秒后，重新访问',$statusCode);
        }

        dd($e);
    }
}
