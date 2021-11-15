<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponse;
use App\Services\robotService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
        } else {
            $this->report($e);

            $message = mb_substr($e->__toString(),0,500);

            //发送异常提醒
            $robotService = new robotService();
            $robotService->send('exception_robot',[
                'message' => $message,
                'status'  => $statusCode
            ]);

            return $this->internalError('系统异常，请稍后再试！');
        }

    }
}
