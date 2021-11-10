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
     * 获取到机器人的配置
     * @param string $robotName
     * @return array
     */
    protected function getRobotConfig(string $robotName) : array
    {
        return config(self::ROBOT_CONFIG_FILE.$robotName) ?? [];
    }

    /**
     * 发送机器人提醒
     * @param string $robotName
     * @param array $data
     * @return bool
     */
    public function send(string $robotName, array $data) : bool
    {
        $config = $this->getRobotConfig($robotName);

        if (empty($config) || !($config['status'] ?? false) || empty($config['webhook'] ?? ''))  {
            return false;
        }

        $response = Http::post($config['webhook'],[
            'msg_type' => 'text',
            'content'  => [
                'text' => implode("\r\n",$data)
            ]
        ]);

        $result = $response->json();

        if (isset($result['StatusMessage']) && $result['StatusMessage'] == 'success') {
            return true;
        } else {
            Log::channel('robot')
                ->emergency($result['msg'] ?? '');

            return false;
        }
    }
}
