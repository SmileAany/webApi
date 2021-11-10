<?php

namespace App\Channels;

use App\Models\Sms;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\AnonymousNotifiable;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        if($notifiable instanceof AnonymousNotifiable){

            $class = new \stdClass();
            $class->id = null;
            $class->phone  = $notifiable->routes[__CLASS__];

            $notifiable = $class;
        }

        $result = $notification->toSms($notifiable);

        if (!empty($result) && is_array($result)) {
            $data = [
                'user_id' => $notifiable->id ?: 0,
                'phone'   => $notifiable->phone,
                'data'    => json_encode($notification->parameters),
                'message' => mb_substr($result['message'],0,300),
                'status'  => $result['code'] == 200 ? 1 : 0
            ];

            Sms::create($data);
        }
    }
}
