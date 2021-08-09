<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public array $parameters;

    /**
     * Create a new message instance.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @Notes:根据主题id获取到对应的视图数据
     *
     * @return string
     * @Author: smile
     * @Date: 2021/7/31
     * @Time: 18:05
     */
    public function getView(): string
    {
        switch ($this->parameters['templateId']) {
            case 1:
                $view = 'welcome';
                break;
            default:
                $view = 'register';
        }

        return 'emails.'.$view;
    }

    /**
     * @Notes:
     *
     * @return CustomEmail
     * @Author: smile
     * @Date: 2021/7/31
     * @Time: 18:14
     */
    public function build(): CustomEmail
    {
        $email = $this->view($this->getView())
            ->subject($this->parameters['subject'])
            ->with($this->parameters['data']);

        //判断是否存在附件
        if (isset($this->parameters['cc']) && !empty($this->parameters['cc'])) {
            foreach ($this->parameters['cc'] as $value) {
                $email->attach($value['path'],[
                    'as'   => $value['name'],
                    'mime' => $value['mime']
                ]);
            }
        }

        return $email;
    }
}
