<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Services\Sms\SmsService;

class SmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $queue;

    public $timeout;

    public $tries;

    public $sleep;

    public array $parameters;

    public object $smsService;

    /**
     * Create a new notification instance.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;

        $this->smsService = new SmsService();

        $this->queue = config('message.email.queue');

        $this->tries = config('message.email.tries');

        $this->sleep = config('message.email.sleep');

        $this->timeout = config('message.email.timeout');

        $this->connection = config('message.email.connection');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [SmsChannel::class];
    }

    /**
     * @Notes:发送邮件
     *
     * @param $notifiable
     * @return array
     * @Author: smile
     * @Date: 2021/8/9
     * @Time: 17:18
     */
    public function toSms($notifiable) : array
    {
        if (isset($notifiable->phone) && !empty($notifiable->phone)) {

            if (config('message.sms.status') == true) {
                if (customer_check_phone($notifiable->phone)) {
                    try{
                        $result = $this->smsService->send($notifiable->phone,$this->parameters);

                        if (!empty($result) && $result['qcloud']['status'] == 'success') {
                            return customer_return_success('success');
                        }

                        return customer_return_error($result['qcloud']['result']['errmsg']);
                    }catch (\Exception $exception){
                        $message = $exception->getExceptions()['qcloud']->getMessage() ?? $exception->getMessage();

                        return customer_return_error($message);
                    }
                } else {
                    return customer_return_error('phone 格式异常');
                }
            }

            return customer_return_error('phone 停止发送');
        }
    }
}