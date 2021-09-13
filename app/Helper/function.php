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

if(!function_exists('custom_array_tree')){
    /**
     * @Notes:将数据转换成tree结构
     *
     * @param array $array
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 17:07
     */
    function custom_array_tree(array $array,string $pk='id',string $pid='p_id',string $child='child',int $root=0):array{
        if(!empty($array) && !empty($pk) && !empty($pid) && !empty($child)){
            $tree = array();
            if(is_array($array)) {
                $refer = array();
                foreach ($array as $key => $data) {
                    $refer[$data[$pk]] =& $array[$key];
                }
                foreach ($array as $key => $data) {
                    $parentId = $data[$pid];
                    if ($root == $parentId) {
                        $tree[$data[$pk]] =& $array[$key];
                    }else{
                        if (isset($refer[$parentId])) {
                            $parent =& $refer[$parentId];
                            $parent[$child][] =& $array[$key];
                        }
                    }
                }
            }
            return $tree;
        }
        return $array;
    }
}

if(!function_exists('custom_array_search_parent')){
    /**
     * @Notes:子找父
     *
     * @param array $array
     * @param array $childArray
     * @param string $pk
     * @param string $pid
     * @return array
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 17:08
     */
    function custom_array_search_parent($array = [],$childArray= [],$pk='id', $pid='p_id'): array{
        static $parentArray = [];

        foreach ($childArray as $childId){
            foreach ($array as $value){
                if ($value[$pk] == $childId){
                    $parentArray[] = $value;

                    if ($value[$pid]){
                        custom_array_search_parent($array,[$value[$pid]],$pk,$pid);
                    }
                }
            }
        }

        return $parentArray;
    }
}

if(!function_exists('custom_random_code')){
    /**
     * @Notes:随机生成验证码
     *
     * @param int $length
     * @return string
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 17:08
     */
    function custom_random_code(int $length = 6):string{
        if(!empty($length)){
            return substr(str_shuffle("012345678901234567890123456789"), 0, $length);
        }
    }
}

if(!function_exists('custom_object_array')){
    /**
     * @Notes:将对象转换成数组
     *
     * @param object $object
     * @return array
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 17:09
     */
    function custom_object_array(object $object):array{
        if(!empty($object)){
            return json_decode(json_encode($object),true);
        }
    }
}

if (!function_exists('customer_array_sort')) {

    /**
     * @Notes:数组排序
     *
     * @param $array
     * @param $field
     * @param string $sort
     * @return mixed
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 17:10
     */
    function customer_array_sort($array, $field, $sort = 'SORT_DESC'){
        $arrSort = array();
        foreach ($array as $uniqueId => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqueId] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);

        return $array;
    }
}

if(!function_exists('custom_array_wechat')){
    /**
     * @Notes:组装wechat数据
     *
     * @param array $data
     * @param string $column1
     * @param string $column2
     * @param string $column3
     * @return array
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 17:11
     */
    function custom_array_wechat(array $data,string $column1,string $column2,string $column3):array{
        if(!empty($data) && !empty($column1) && !empty($column2) && !empty($column3)){
            $array=[];
            $current_val= current($data);
            $end_val    = end($data);
            foreach($data as $val){
                static $count=1;
                if($val===$current_val){
                    $key=$column1;
                }else{
                    if($val===$end_val){
                        $key=$column3;
                    }else{
                        $key=$column2.$count;
                        $count++;
                    }
                }
                $array[$key]=$val;
            }
            return $array;
        }
    }
}

if(!function_exists('custom_get_user_ip')){
    /**
     * @Notes:获取到ip地址
     *
     * @return string
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 17:11
     */
    function custom_get_user_ip(): string{
        if ($_SERVER) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realIp = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realIp = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realIp = getenv("HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $realIp = getenv("HTTP_CLIENT_IP");
            } else {
                $realIp = getenv("REMOTE_ADDR");
            }
        }

        return $realIp ? explode(',', $realIp)[0] : '';
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

            return $parameters[$name] ?? $item;
        },$string);
    }
}

if (!function_exists('customer_one_array')) {
    /**
     * @Notes:多维数组变为一维数组
     *
     * @param array $array
     * @param callable|null $callable
     * @return array
     * @Author: smile
     * @Date: 2021/9/13
     * @Time: 17:41
     */
    function customer_one_array(array $array,callable $callable = null) : array{
        if (empty($array)) {
            return [];
        }

        $result = [];

        array_walk_recursive($array,function ($item) use (&$result,$callable) {
            if ($callable != null) {
                $callable($item,$result);
            } else {
                $result[] = $item;
            }
        });

        return $result;
    }
}

if(!function_exists('custom_curl_get')){
    /**
     * @Notes:curl get 请求
     *
     * @param $url
     * @param $header
     * @return bool|string
     * @throws Exception
     * @Author: smile
     * @Date: 2021/9/13
     * @Time: 17:43
     */
    function custom_curl_get($url,$header){
        $ch = curl_init($url);
        if(substr($url,0,5)=='https'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        if($error=curl_error($ch)){
            throw new \Exception($error);
        }
        curl_close($ch);

        return $result;
    }
}
