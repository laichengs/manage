<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 13:59
 */

namespace app\Exception;


use think\Exception;

class BaseException extends Exception
{
    public $code = 401;
    public $msg = '发生了一个未定义的错误';
    public $error = '99999';
    public function __construct($param = [])
    {
        if(!is_array($param)){
            return ;
        };
        if(array_key_exists('msg', $param)){
            $this->msg = $param['msg'];
        }
        if(array_key_exists('code', $param)){
            $this->code = $param['code'];
        }
        if(array_key_exists('errorCode', $param)){
            $this->errorCode = $param['errorCode'];
        }
    }
}