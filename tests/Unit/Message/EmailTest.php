<?php

namespace Tests\Unit\Message;

use Tests\TestCase;
use App\Facades\Message;

class EmailTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_email()
    {
        $email = 'ywjmylove@163.com';

        Message::email($email,[
            'templateId' => 1,
            'subject'    => '测试',
            'data'       => []
        ]);
    }
}
