<?php

namespace App\Notifications;

use App\Mail\CustomEmail;
use Illuminate\Bus\Queueable;
use App\Channels\EmailChannel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailNotification extends Notification
{
    use Queueable;

    public $queue;

    public $timeout;

    public $tries;

    public $sleep;

    public int $templateId;

    public array $parameters;

    /**
     * Create a new notification instance.
     *
     * @param int $templateId
     * @param array $parameters
     */
    public function __construct(int $templateId,array $parameters)
    {
        $this->parameters = $parameters;

        $this->templateId = $templateId;

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
    public function via($notifiable)
    {
        return [EmailChannel::class];
    }

    public function toEmail($notifiable) : ? array
    {
        if (isset($notifiable->email) && !empty($notifiable->email)) {
            try{
                Mail::to($notifiable->email)
                    ->send(new CustomEmail($this->templateId,$this->parameters));
            }catch (\Exception $exception){

            }
        }
    }
}
