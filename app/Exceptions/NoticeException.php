<?php

namespace App\Exceptions;

class NoticeException extends \Exception
{
    public static function handle($exception)
    {


        $content = [
            'request' => request()->all(),
            'user'    =>  1,
        ];

        dd($content);


        try {
            if (is_object($exception)) {

            }

            if (is_array($exception)) {

            }

            if (!is_string($exception)) {

            }



            dd($content);
        }catch (\Exception $exception) {
            dd($exception->getMessage());
        }


    }
}
