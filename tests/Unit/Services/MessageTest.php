<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Facades\Message;

class MessageTest extends TestCase
{
    protected string $phone = '15102750714';

    protected string $email = '723891137@qq.com';

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_message()
    {

        $this->assertTrue(true);
    }
}