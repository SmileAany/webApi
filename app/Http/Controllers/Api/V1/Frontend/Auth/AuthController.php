<?php

namespace App\Http\Controllers\Api\V1\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailNotification;
use App\Channels\EmailChannel;
use App\Facades\Message;

class AuthController extends Controller
{
    use Notifiable;

    /**
     * @Notes:登录
     *
     * @param Request $request
     * @param AuthService $authService
     * @return mixed
     * @throws ValidationException
     * @Author: smile
     * @Date: 2021/7/18
     * @Time: 17:34
     */
    public function login(Request $request,AuthService $authService)
    {
        Validator::make($request->all(),[
            'account'  => 'required|string',
            'password' => 'required|string'
        ])->validate();

        $result = $authService->login($request->only('account','password'));

        if ($result['code'] == 200){
            return $this->success($result['data']);
        }

        return $this->failed($result['message']);
    }

    /**
     * @Notes:安全退出
     *
     * @param AuthService $authService
     * @return mixed
     * @Author: smile
     * @Date: 2021/7/18
     * @Time: 18:21
     */
    public function logout(AuthService $authService)
    {
        $result = $authService->logout();

        if ($result['code'] == 200){
            return $this->message($result['message']);
        }

        return $this->failed($result['message']);
    }

































    public function test()
    {


        $emails = User::where('id',1)->get();

        $emails = User::first();

        $emails = '723891137@qq.com';
//
//        $emails = [
//            '723891137@qq.com',
//            '723891137@qq.com'
//        ];

//        $emails = User::first();


        $p = [
            'templateId' => 1,
            'parameters' => [

            ],
            'subject' => 'test'
        ];


        dd(Message::email($emails,$p));

        $user = \App\Models\User::all();

//        Notification::send($user,new EmailNotification('admin'));

        Notification::route(EmailChannel::class,'admin')
            ->notify(new EmailNotification('admin'));
    }
}
