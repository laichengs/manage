<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/14
 * Time: 11:21
 */

namespace app\api\controller;


use think\Request;

class UploadController
{
    public function upload(){
        $file = \request()->file('image');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'image');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
               // return  $info->getExtension().$info->getSaveName();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $path = $info->getSaveName();
                return str_replace('\\', '/', $path);
                return $path;
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                return $file->getError();
            }
        }
       // return $_POST;
    }
}