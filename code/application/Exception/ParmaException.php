<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 17:41
 */

namespace app\Exception;


class ParmaException extends BaseException
{
    public $code = 403;
    public $msg = '错误格式错误';
    public $error = 10000;
}