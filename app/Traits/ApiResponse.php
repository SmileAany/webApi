<?php

namespace App\Traits;

use Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

trait ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param $data
     * @param array $header
     * @return JsonResponse
     */
    public function respond($data, array $header = []): JsonResponse
    {
        return Response::json($data,$this->getStatusCode(),$header);
    }

    /**
     * @param $status
     * @param array $data
     * @param null $code
     * @return JsonResponse
     */
    public function status($status, array $data, $code = null): JsonResponse
    {

        if ($code){
            $this->setStatusCode($code);
        }

        $status = [
            'status' => $status,
            'code'   => $this->statusCode
        ];

        $data = array_merge($status,$data);

        return $this->respond($data);
    }

    /**
     * @param $message
     * @param int $code
     * @param string $status
     * @return JsonResponse
     */
    public function failed($message, int $code = FoundationResponse::HTTP_BAD_REQUEST, string $status = 'error'): JsonResponse
    {

        return $this->setStatusCode($code)->message($message,$status);
    }


    /**
     * @param $message
     * @param string $status
     * @return JsonResponse
     */
    public function message($message, string $status = "success"): JsonResponse
    {
        return $this->status($status,[
            'message' => $message
        ]);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function internalError(string $message = "Internal Error!"){

        return $this->failed($message,FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function created(string $message = "created"): JsonResponse
    {
        return $this->setStatusCode(FoundationResponse::HTTP_CREATED)
            ->message($message);

    }

    /**
     * @param $data
     * @param string $status
     * @return JsonResponse
     */
    public function success($data, string $status = "success"): JsonResponse
    {

        return $this->status($status,compact('data'));
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFound(string $message = 'Not Found!')
    {
        return $this->failed($message,Foundationresponse::HTTP_NOT_FOUND);
    }

    /**
     * @param $message
     * @param array $errors
     * @param int $code
     * @param string $status
     * @return JsonResponse
     */
    public function errors($message, array $errors = [], int $code = FoundationResponse::HTTP_BAD_REQUEST, string $status = 'error'): JsonResponse
    {
        return  $this->status($status,[
            'message' => $message,
            'error'    => $errors,
        ],$code);
    }

    /**
     * @param  $data
     * @param  $message
     * @param string $status
     * @return JsonResponse
     */
    public function successful($data, $message, string $status = "success"): JsonResponse
    {
        return $this->status($status,[
            'message' => $message,
            'data'    => $data
        ]);
    }
}
