<?php

namespace App\Channels;

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

        dd($result);

        if (!empty($result) && is_array($result)) {
            //记录日志
        }
    }
}