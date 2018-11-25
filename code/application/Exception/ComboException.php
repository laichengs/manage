<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/12
 * Time: 15:13
 */

namespace app\Exception;


class ComboException extends BaseException
{
    public $code = 403;
    public $msg = '套餐剩余数量不足';
    public $error = 70003;
}