<?php

namespace App\Notifications;

use App\Mail\CustomEmail;
use Illuminate\Bus\Queueable;
use App\Channels\EmailChannel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $queue;

    public $timeout;

    public $tries;

    public $sleep;

    public array $parameters;

    /**
     * Create a new notification instance.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;

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
        return [EmailChannel::class];
    }

    /**
     * @Notes:发送邮件
     *
     * @param $notifiable
     * @return array
     * @Author: smile
     * @Date: 2021/8/9
     * @Time: 15:47
     */
    public function toEmail($notifiable) : array
    {
        if (isset($notifiable->email) && !empty($notifiable->email)) {

            if (config('message.email.status') == true) {
                if (filter_var($notifiable->email,FILTER_VALIDATE_EMAIL)) {
                    try{
                        Mail::to($notifiable->email)
                            ->send(new CustomEmail($this->parameters));

                        return customer_return_success('success');
                    }catch (\Exception $exception){
                        return customer_return_error($exception->getMessage());
                    }
                } else {
                    return customer_return_error('email 格式异常');
                }
            }

            return customer_return_error('email 停止发送');
        }
    }
}