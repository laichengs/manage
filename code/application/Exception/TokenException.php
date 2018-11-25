<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/31
 * Time: 10:03
 */

namespace app\Exception;


class TokenException extends BaseException
{
    public $code = 403;
    public $msg = 'token不存在或已过期';
    public $error = 40000;
}