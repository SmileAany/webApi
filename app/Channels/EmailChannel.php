<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

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
        $message = $notification->toEmail($notifiable);
    }
}