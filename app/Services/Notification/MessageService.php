<?php

namespace App\Services\Notification;

use App\Models\User;
use App\Enums\MessageEnums;
use App\Channels\SmsChannel;
use App\Channels\EmailChannel;
use App\Notifications\SmsNotification;
use App\Notifications\EmailNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;

class MessageService
{
    /**
     * @Notes:
     *
     * @param $users string|array|object|Collection
     * @param $parameters
     * @return bool
     * @throws \Exception
     * @Author: smile
     * @Date: 2021/7/26
     * @Time: 21:58
     */
    public function email($users,array $parameters) : bool
    {
        if (!$this->checkFiled('templateId,data,subject',$parameters)) {
            throw new \Exception('参数异常');
        }

        if (is_object($users)  && ($users instanceof User || $users instanceof Collection)){
            Notification::send($users,new EmailNotification($parameters));
        }

        if (is_array($users) && $emails = $users ){
            foreach($emails as $value){
                Notification::route(EmailChannel::class, $value)->notify(new EmailNotification($parameters));
            }
        }

        if (is_string($users) && $email = $users){
            Notification::route(EmailChannel::class, $email)->notify(new EmailNotification($parameters));
        }

        return true;
    }

    /**
     * @Notes:
     *
     * @param $users string|array|object|Collection
     * @param $parameters
     * @return bool
     * @throws \Exception
     * @Author: smile
     * @Date: 2021/7/26
     * @Time: 21:58
     */
    public function sms($users,array $parameters) : bool
    {
        if (!$this->checkFiled('data',$parameters)) {
            throw new \Exception('参数异常');
        }

        if (is_object($users)  && ($users instanceof User || $users instanceof Collection)){
            Notification::send($users,new SmsNotification($parameters));
        }

        if (is_array($users) && $emails = $users ){
            foreach($emails as $value){
                Notification::route(SmsChannel::class, $value)->notify(new SmsNotification($parameters));
            }
        }

        if (is_string($users) && $email = $users){
            Notification::route(SmsChannel::class, $email)->notify(new SmsNotification($parameters));
        }

        return true;
    }

    /**
     * @Notes:验证的数组中是否存在指定的字段
     *
     * @param string $attribute
     * @param array $parameters
     * @return bool
     * @Author: smile
     * @Date: 2021/7/26
     * @Time: 21:55
     */
    protected function checkFiled(string $attribute,array $parameters): bool
    {
        $attributes = explode(',',trim($attribute));

        foreach ($attributes as $field) {
            if (!isset($parameters[$field])) {
                return false;
            }
        }

        return true;
    }
}