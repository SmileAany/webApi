<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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

    protected function context(): array
    {
        return array_merge(parent::context(),[
            'request' => request()->all(),
            'header'  => request()->header()
        ]);
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->failed('请求方式异常 '.$e->getMessage(),$e->getStatusCode());
        } else if ($e instanceof NotFoundHttpException) {
            return $this->notFound('路由异常');
        } else if ($e instanceof ValidationException) {
            foreach ($e->errors() as $key => $value){
                return $this->failed($value[0],422);
            }
        } else if ($e instanceof ThrottleRequestsException){
            $headers  = $e->getHeaders();
            return $this->failed('请求太过频繁，请'.custom_second_trans($headers['Retry-After']).'后再试！');
        } else if ($e instanceof AuthorizationException) {
            return $this->failed($e->getMessage(),401);
        } else if ($e instanceof AuthenticationException){
            return $this->failed($e->getMessage(),401);
        } else {
            return $this->internalError();
        }
    }

    public function report(Throwable $e)
    {
        if ($e instanceof \ErrorException || $e instanceof \ParseError || $e instanceof \TypeError || $e instanceof \CompileErro) {
            if (app()->bound('sentry') && $this->shouldReport($e)) {
                app('sentry')->captureException($e);
            }
        }

        parent::report($e);
    }
}
