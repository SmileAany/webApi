<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class robotService
{
    /**
     * 机器人的配置路由
     */
    const ROBOT_CONFIG_FILE = 'message.robot.';

    /**
     * @var 时间戳
     */
    public $timestamp;

    /**
     * @var array 配置文件
     */
    public $config;

    public function __construct()
    {
        $this->timestamp = time();
    }

    /**
     * 获取到机器人的配置
     * @param string $robotName
     * @return array
     */
    protected function getRobotConfig(string $robotName) : array
    {
        return config(self::ROBOT_CONFIG_FILE.$robotName) ?? [];
    }

    /**
     * 创建signature
     * @param string $signature
     * @param int $timestamp
     * @return string
     */
    public function signature(string $signature,int $timestamp) : string
    {
        $signature = $timestamp . "\n" . $signature;
        $signature = hash_hmac('sha256',"",$signature,true);

        return base64_encode($signature);
    }

    /**
     * 标题信息
     * @return array
     */
    public function getHeader() : array
    {
        return [
            'header' => [
                'title' => [
                    'tag'     => 'plain_text',
                    'content' => $this->config['title'] ?? (config('app.name') . '系统系统汇报')
                ],
                'template' => 'red'
            ]
        ];
    }

    /**
     * 生成bug的描述
     * @param string $message
     * @return array
     */
    public function getBugContent(string $message = '') : array
    {
        return [
            [
                'tag'  => 'div',
                'text' => [
                    'tag'     => 'lark_md',
                    'content' => $message
                ]
            ],
            [
                'tag' => 'hr'
            ]
        ];
    }

    /**
     * 获取到请求信息
     * @return array
     */
    public function getRequestData() : array
    {
        if (auth('api') && method_exists(auth('api'),'user')) {
            $user_id = auth('api')->user()->id ?? 0;
        }

        return [
            'ip'         => request()->ip(),
            'url'        => request()->path(),
            'parameters' => json_encode(request()->all()),
            'user_id'    => $user_id ?? 0
        ];
    }

    /**
     * 生成副标题
     * @param int $status
     * @return array
     */
    public function genSubtitle(int $status) : array
    {
        $request = $this->getRequestData();

        return [
            [
                'tag' => 'div',
                'text' => [
                    'tag'     => 'lark_md',
                    'content' => $this->config['subtitle'] ?? config('app.name') . '系统异常，请相关开发人员及时处理bug'
                ]
            ],
            [
                'tag' => 'hr'
            ],
            [
                'tag' => 'div',
                'text' => [
                    'tag'     => 'lark_md',
                    'content' => '错误状态码：' . $status .'   ' . ' 当前请求的ip：'.$request['ip']
                ]
            ],
            [
                'tag' => 'hr'
            ],
            [
                'tag' => 'div',
                'text' => [
                    'tag' => 'lark_md',
                    'content' => '请求的URI：' . $request['url'] . '   ' . '请求的参数：' . $request['parameters'],
                ]
            ],
            [
                'tag' => 'hr'
            ],
            [
                'tag' => 'div',
                'text' => [
                    'tag' => 'lark_md',
                    'content' => '当前登录用户的id: '.$request['user_id'] . '   '. '请求发生时间：'.date('Y-m-d H:i:s')
                ]
            ],
            [
                'tag' => 'hr'
            ]
        ];
    }

    /**
     * 发送机器人提醒
     * @param string $robotName
     * @param array $exception
     * @return bool
     */
    public function send(string $robotName, array $exception) : bool
    {
        try {
            $this->config = $this->getRobotConfig($robotName);

            if (empty($this->config) || !($this->config['status'] ?? false) || empty($this->config['webhook'] ?? '') || empty($this->config['signature'] ?? ''))  {
                return false;
            }

            $card = array_merge($this->getHeader(),[
                'elements' => array_merge($this->genSubtitle($exception['status']),$this->getBugContent($exception['message']))
            ]);

            $sendParameters = [
                'msg_type'  => 'interactive',
                'card'      => json_encode($card),
                'timestamp' => $this->timestamp,
                'sign'      => $this->signature($this->config['signature'],$this->timestamp)
            ];

            $response = Http::timeout(10)
                ->post($this->config['webhook'],$sendParameters);

            $result = $response->json();

            if (isset($result['StatusMessage']) && $result['StatusMessage'] == 'success') {
                return true;
            } else {
                Log::channel('robot')
                    ->emergency($result['msg'] ?? '');

                return false;
            }
        }catch (\Exception|\Throwable|\Error $exception){
            Log::channel('robot')
                ->emergency($exception->getMessage());

            return false;
        }
    }
}
