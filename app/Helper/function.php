<?php

if (!function_exists('custom_path_file')) {
    /**
     * @Notes:获取到指定目录下特定的文件
     *
     * @param $path
     * @param string $suffix
     * @return array
     * @Author: smile
     * @Date: 2021/7/18
     * @Time: 17:22
     */
    function custom_path_file($path, $suffix = '.php'):array{
        $files = [];
        if (is_dir(base_path().'/'.$path)) {
            foreach (scandir(base_path().'/'.$path) as $dir) {
                if ('.' !== $dir[0]) {
                    if (is_dir(base_path().'/'.$path.'/'.$dir)) {
                        $files = array_merge($files, custom_path_file($path.'/'.$dir));
                    } else {
                        if (substr($dir, -4) == $suffix) {
                            $files[] = $path.'/'.$dir;
                        }
                    }
                }
            }
        }

        return $files;
    }
}

if(!function_exists('custom_account_type')){
    /**
     * @Notes:获取到账号类型
     *
     * @param $data
     * @return string
     * @Author: smile
     * @Date: 2021/7/18
     * @Time: 17:23
     */
    function custom_account_type($data):string{
        if(!empty($data) && (is_string($data) || is_integer($data))){
            if(preg_match("/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/",$data)){
                return 'email';
            }else if(preg_match("/^[1]([3-9])[0-9]{9}$/",$data)){
                return 'phone';
            }else{
                return 'username';
            }
        }

        return 'username';
    }
}

if(!function_exists('customer_return_success')){
    /**
     * @Notes:返回正常数据
     *
     * @param string $message
     * @param array $data
     * @return array
     * @Author: smile
     * @Date: 2021/7/18
     * @Time: 17:49
     */
    function customer_return_success(string $message, array $data = []): array{
        return [
            'status'  => 'success',
            'code'    => 200,
            'message' => $message,
            'data'    => $data
        ];
    }
}

if (!function_exists('customer_return_error')){
    /**
     * @Notes:返回错误数据
     *
     * @param string $message
     * @param array $data
     * @return array
     * @Author: smile
     * @Date: 2021/7/18
     * @Time: 17:49
     */
    function customer_return_error(string $message, array $data = []) : array{
        return [
            'status'  => 'error',
            'code'    => 500,
            'message' => $message,
            'data'    => $data
        ];
    }
}

if(!function_exists('custom_file_size_trans')){
    /**
     * @Notes:将格式转换
     *
     * @param $size
     * @return string
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 10:07
     */
    function custom_file_size_trans($size) : string{
        if(!empty($size)){
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];

            for ($i = 0; $size >= 1024 && $i < 4; $i++) {
                $size /= 1024;
            }

            return round($size, 2).$units[$i];
        }

        return '0KB';
    }
}

if(!function_exists('custom_second_trans')){
    /**
     * @Notes:转换时间
     *
     * @param $seconds
     * @return string
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 10:08
     */
    function custom_second_trans($seconds): string{
        if(!empty($seconds)){
            $d = floor($seconds / (3600*24));
            $h = floor(($seconds % (3600*24)) / 3600);
            $m = floor((($seconds % (3600*24)) % 3600) / 60);

            if($d> 0){
                $times  = $d.'天'.$h.'小时'.$m.'分钟';
            }else{
                if($h!= 0){
                    $times = $h.'小时'.$m.'分钟';
                }else{
                    if($m!=0){
                        $times = $m.'分钟';
                    }else{
                        $times = $seconds.'秒';
                    }
                }
            }

            return $times;
        }
    }
}