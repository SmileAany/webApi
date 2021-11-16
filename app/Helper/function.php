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
    function custom_path_file($path, string $suffix = '.php'):array{
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

if (!function_exists('customer_check_phone')) {
    /**
     * @Notes:正则验证手机号
     *
     * @param string $value
     * @return bool
     * @Author: smile
     * @Date: 2021/8/9
     * @Time: 16:48
     */
    function customer_check_phone(string $value) : bool {
        return (bool) preg_match("/^1[3-9]{1}[0-9]{9}$|^([6|9])\d{7}$|^[0][9]\d{8}$|^[6]([8|6])\d{5}$/", $value);
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

if (!function_exists('customer_analysis_string')) {
    /**
     * @Notes:解析指定的字符串
     *
     * @param string $startString
     * @param string $endString
     * @param string $string
     * @param array $parameters
     * @return string
     * @Author: smile
     * @Date: 2021/9/13
     * @Time: 16:49
     */
    function customer_analysis_string(string $startString,string $endString,string $string,array $parameters) : string{
        if (empty($startString) || empty($endString) || empty($parameters)) {
            return $string;
        }

        $pattern = '/';

        for ($i = 0; $i < strlen($startString) ; $i++) {
            $pattern .= '\\'.$startString[$i];
        }

        $pattern = $pattern.'[^}]+';

        for ($i = 0; $i < strlen($endString) ; $i++) {
            $pattern .= '\\'.$endString[$i];
        }

        $pattern = $pattern.'/';

        return preg_replace_callback($pattern,function ($item) use ($parameters,$startString,$endString){
            $item = current($item);

            $name = ltrim($item,$startString);
            $name = rtrim($name,$endString);

            return $parameters[$name] ?? '';
        },$string);
    }
}
