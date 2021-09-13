<?php

namespace App\Services\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * @Notes:附件上传
     *
     * @param $request
     * @return array
     * @Author: smile
     * @Date: 2021/9/10
     * @Time: 17:21
     * @throws \Exception
     */
    public static function upload($request): array
    {
        if (!$request instanceof Request){
            throw new \InvalidArgumentException('参数必须为 Request的实例');
        }

        if(!$request->file('file')->isValid()){
            throw new \Exception('附件无效');
        }

        $ext = $request->file('file')->getClientOriginalExtension();
        $allowExtensions = $request->input('ext',config('filesystems.ext'));

        if(!in_array($ext, $allowExtensions)){
            throw new \Exception('附件类型错误,只能上传 '.implode(",", $allowExtensions).' 格式附件');
        }

        $allowSize = $request->input('size',config('filesystems.size'));
        $size = $request->file('file')->getSize();

        if($size > $allowSize*1024*1024){
            throw new \Exception('附件大小超出最大限制 '.custom_file_size_trans($allowSize*1024*1024));
        }

        if($size > $request->file('file')->getMaxFilesize()){
            throw new \Exception('附件大小超出最大限制 '.custom_file_size_trans($request->file('file')->getMaxFilesize()));
        }

        $path = Storage::disk(config('filesystems.disk'))->putFile(date('Ym'),$request->file('file'));
        $filename = $request->file('file')->getClientOriginalName();

        return [
            'filename'   => $filename,
            'original'   => substr($path, strpos($path, '/') + 1),
            'ext'        => $ext,
            'path'       => $path,
            'size'       => custom_file_size_trans($size)
        ];
    }

    /**
     * @describe
     * @param array $params
     * @return array
     * @throws CustomException
     * @author smile
     * @emial ywjmylove@163.com
     * @Time 2021/1/29 下午 3:12
     */
    public function save(array $params) : array
    {
        if ( !custom_array_key($params,'filename,original,ext,path,size') ){
            throw new CustomException('参数错误');
        }

        $file = Files::create([
            'user_id'    => auth('api')->user()->id ?? null,
            'filename'   => $params['filename'],
            'original'   => $params['original'],
            'ext'        => $params['ext'],
            'path'       => $params['path'],
            'size'       => $params['size'],
        ]);

        return [
            'id'         => $file->id,
            'filename'   => substr($file->filename,0,strpos($file->filename,'.')),
            'ext'        => $file->ext,
            'url'        => system_get_file_url($file->path)
        ];
    }

    /**
     * @describe
     * @param int $id
     * @throws CustomException
     * @author smile
     * @emial ywjmylove@163.com
     * @Time 2021/1/29 上午 11:37
     */
    public function remove(int $id)
    {
        $file = Files::findOrFail($id);

        if(Storage::disk(config('file.disk'))->exists($file->path)){
            Storage::disk(config('file.disk'))->delete($file->path);
        }

        $file->delete();
    }

    /**
     * @describe
     * @param array $params
     * @throws CustomException
     * @author smile
     * @emial ywjmylove@163.com
     * @Time 2021/2/1 上午 11:09
     */
    public function rename(array $params)
    {
        if ( !custom_array_key($params,'file_id,filename') ){
            throw new CustomException('参数缺失');
        }

        $file = Files::findOrFail($params['file_id']);

        $ext =substr($file->filename,strrpos($file->filename,'.'));
        $file->filename = $params['filename'].$ext;

        $file->save();
    }

    /**
     * @describe
     * @param int $id
     * @param object $request
     * @throws CustomException
     * @author smile
     * @emial ywjmylove@163.com
     * @Time 2021/2/1 上午 11:18
     */
    public function cover(int $id,object $request)
    {
        if (!is_subclass_of($request,Request::class)){
            throw new CustomException('参数必须为 Request的实例');
        }

        $res = $this->upload($request);

        $file = Files::findOrFail($id);

        if(Storage::disk(config('file.disk'))->exists($file->path)){
            Storage::disk(config('file.disk'))->delete($file->path);
        }

        $file->filename = $res['filename'];
        $file->original = $res['original'];
        $file->ext      = $res['ext'];
        $file->size     = $res['size'];
        $file->path     = $res['path'];

        $file->save();
    }

    /**
     * @describe
     * @param array $params
     * @throws CustomException
     * @author smile
     * @emial ywjmylove@163.com
     * @Time 2021/1/29 下午 2:39
     */
    public function update(array $params)
    {
        if (!custom_array_key($params,'file_id,model_id') ){
            throw new CustomException('参数缺失');
        }

        $file = Files::findOrFail($params['file_id']);

        $file->model_id    = $params['model_id'];
        $file->model_type  = $params['model_type'];
        $file->type        = $params['type'] ?? null;

        $file->save();
    }

    /**
     * @describe
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws CustomException
     * @author smile
     * @emial ywjmylove@163.com
     * @Time 2021/2/1 上午 11:32
     */
    public function download(int $id): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $file = Files::findOrFail($id);

        if(Storage::disk(config('file.disk'))->exists($file->path)){
            return Storage::disk(config('file.disk'))->download($file->path,$file->filename);
        }

        throw new CustomException('资源不存在');
    }
}
