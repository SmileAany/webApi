<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\File\FileService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FileController extends Controller
{
    /**
     * @Notes:
     *
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     * @throws \Exception
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 17:38
     */
    public function upload(Request $request)
    {
        Validator::make($request->all(),[
            'file'  => 'required|file',
            'limit' => 'nullable|numeric',
            'ext'   => 'nullable|array'
        ])->validate();

        $file = FileService::upload($request);

        return $this->successful($file,'上传成功');
    }
}
