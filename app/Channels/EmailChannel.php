<?php

namespace App\Channels;

use App\Models\Email;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\AnonymousNotifiable;

class EmailChannel
{
    /**
     * @Notes:邮件发送通知
     *
     * @param $notifiable
     * @param Notification $notification
     * @Author: smile
     * @Date: 2021/7/26
     * @Time: 12:03
     */
    public function send($notifiable, Notification $notification)
    {
        if($notifiable instanceof AnonymousNotifiable){
            $class = new \stdClass();
            $class->id = null;
            $class->email  = $notifiable->routes[__CLASS__];

            $notifiable = $class;
        }

        $result = $notification->toEmail($notifiable);

        if (!empty($result) && is_array($result)) {
            $data = [
                'user_id' => $notifiable->id ?: 0,
                'email'   => $notifiable->email,
                'data'    => json_encode($notification->parameters),
                'message' => mb_substr($result['message'],0,300),
                'status'  => $result['code'] == 200 ? 1 : 0
            ];

            Email::create($data);
        }
    }
}