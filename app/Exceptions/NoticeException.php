<?php

namespace App\Exceptions;

class NoticeException extends \Exception
{
    public static function handle($exception)
    {

        if (is_object($exception)) {

        }

        if (is_array($exception)) {

        }

        if (!is_string($exception)) {

        }

        $content = [
            'request' => request()->all(),
            'user'    =>  1,
            'path'    => route()->path()
        ];

        dd($content);
    }
}
