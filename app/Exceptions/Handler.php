<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * @Notes:改写日志类型
     *
     * @return array
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 10:41
     */
    protected function context(): array
    {
        return array_merge(parent::context(),[
            'request' => request()->all(),
            'header'  => request()->header()
        ]);
    }

    /**
     * @Notes:输出错误
     *
     * @param $request
     * @param Throwable $e
     * @return JsonResponse|Response|mixed|FoundationResponse
     * @Author: smile
     * @Date: 2021/9/9
     * @Time: 18:01
     */
    public function render($request, Throwable $e)
    {
        $message = $e->getMessage();

        if (method_exists($e,'getStatusCode') && $e->getStatusCode()) {
            $statusCode = $e->getStatusCode();
        } else if (method_exists($e,'getCode') && $e->getCode()) {
            $statusCode = $e->getCode();
        } else if (property_exists($e,'status') && $e->status){
            $statusCode = $e->status;
        }else {
            $statusCode = FoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
        }

        $statusCode = $statusCode ?: FoundationResponse::HTTP_INTERNAL_SERVER_ERROR;

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->failed('请求方式异常 '.$message,$statusCode);
        } else if ($e instanceof NotFoundHttpException) {
            return $this->notFound('路由异常');
        } else if ($e instanceof ValidationException) {
            foreach ($e->errors() as $key => $value){
                return $this->failed($value[0],$statusCode,$e->errors());
            }
        } else if ($e instanceof ThrottleRequestsException){
            $headers  = $e->getHeaders();

            return $this->failed('请求太过频繁，请'.custom_second_trans($headers['Retry-After']).'后再试！',$statusCode);
        } else if ($e instanceof AuthorizationException) {
            return $this->failed($message,$statusCode);
        } else if ($e instanceof AuthenticationException){
            return $this->failed($message,$statusCode);
        } else {
            return $this->internalError();
        }
    }

    /**
     * @Notes:
     * 将日志记录到sentry
     * @param Throwable $e
     * @throws Throwable
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 10:41
     */
    public function report(Throwable $e)
    {
//        if ($e instanceof \ErrorException || $e instanceof \ParseError || $e instanceof \TypeError || $e instanceof \CompileErro) {
//            if ($this->shouldReport($e)) {
//                if (app()->bound('sentry')) {
//                    app('sentry')->captureException($e);
//                }
//
//                if (config('robot.status')) {
//                    $url = config('robot.robot_webhook_url');
//
//                    if (!empty($url) && is_string($url)) {
//                        Http::post($url,[
//                            'msgtype'  => 'markdown',
//                            'markdown' => [
//                                'content' => "<font color=\"red\">异常提醒，请相关同事核查</font> \n异常概要:".$e->getMessage()." \n异常文件: ".$e->getFile()."\n异常行列:".$e->getLine()." \n异常堆栈:[点击查看](https://sentry.io/organizations/fscom/issues/2638660684/)"
//                            ]
//                        ]);
//                    }
//                }
//            }
//        }

        parent::report($e);
    }
}
