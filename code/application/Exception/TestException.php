<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 17:36
 */

namespace app\Exception;


use app\api\validate\BaseValidate;

class TestException extends BaseException
{
    public $code = 300;
    public $msg = '输几局错误';
    public $error = 999;
}