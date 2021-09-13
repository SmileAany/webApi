<?php

namespace App\Services\Sms;

use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class SmsService
{
    public object $easySms;

    public function __construct()
    {
        $this->easySms = new EasySms(config('sms'));
    }

    /**
     * @Notes: 发送短信消息
     *
     * @param int $phone
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     * @throws NoGatewayAvailableException
     * @Author: smile
     * @Date: 2021/8/9
     * @Time: 17:01
     */
    public function send(int $phone,array $data): array
    {
        return $this->easySms->send($phone,[
            'template' => config('sms.gateways.qcloud.template_id'),
            'content'  => '您正在{1}验证，验证码{2}，请在15分钟内按页面提示提交验证码，切勿将验证码泄露于他人。',
            'data'     => $data['data']
        ]);
    }
}