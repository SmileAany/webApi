<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Services\File\FileService;
use Illuminate\Foundation\Testing\WithFaker;

class FileTest extends TestCase
{
    use WithFaker;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_file_upload()
    {
        $file = $this->faker->file('/tmp');

        dd($file);

        $request = $this->post('api/file/upload');

        dd($request);

        $request = Request::create();




        FileService::upload($request);

        dd(111);

        $this->assertTrue(true);
    }
}
