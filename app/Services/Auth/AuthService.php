<?php

namespace App\Services\Auth;

use App\Enums\ApiEnums;
use Illuminate\Support\Facades\Log;

class AuthService
{
    /**
     * @Notes:用户登录
     *
     * @param array $parameters
     * @return array
     * @Author: smile
     * @Date: 2021/7/18
     * @Time: 17:51
     */
    public function login(array $parameters): array
    {
        try{
            $account = custom_account_type($parameters['account']);

            $credentials = [
                $account   => $parameters['account'],
                'password' => $parameters['password']
            ];

            if (! $token = auth('api')->attempt($credentials)) {
                return customer_return_error('账号或密码异常');
            }

            return customer_return_success('登录成功',[
                'token' => ApiEnums::TOKEN_TYPE.$token
            ]);
        }catch (\Exception $exception){
            report($exception);

            Log::channel('api')
                ->info(__FUNCTION__.' 用户登录异常：'.$exception->getMessage());

            return customer_return_error(trans('api.busy'));
        }
    }

    /**
     * @Notes:安全退出
     *
     * @return array
     * @Author: smile
     * @Date: 2021/7/18
     * @Time: 18:24
     */
    public function logout(): array
    {
        try{
            auth('api')->logout();

            return customer_return_success('安全退出');
        }catch (\Exception $exception){
            report($exception);

            Log::channel('api')
                ->info(__FUNCTION__.' 用户退出异常：'.$exception->getMessage());

            return customer_return_error(trans('api.busy'));
        }
    }
}