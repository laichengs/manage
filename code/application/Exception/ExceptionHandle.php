<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 14:13
 */

namespace app\Exception;


use Exception;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandle extends Handle
{
    private $code;
    private $msg;
    private $error;
    public function render(\Exception $e)
    {
        if($e instanceof BaseException){
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->error = $e->error;
        }else{
            if(config('app_debug')){
               return parent::render($e);
            }else{
                $this->code = 500;
                $this->msg = '服务器内部错误';
                $this->error = 88888;
                $this->recordLogException($e);
            }
        }
        $request = Request::instance();
        $url = $request->url();
        $data = [
            'msg' => $this->msg,
            'error' => $this->error,
            'url' => $url
        ];
        return json($data, $this->code);
    }

    private function recordLogException(Exception $e){
        Log::init([
            'type' => 'file',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(), 'error');
    }
}