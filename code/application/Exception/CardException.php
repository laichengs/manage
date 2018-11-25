<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/11/23
 * Time: 15:33
 */

namespace app\Exception;


class CardException extends BaseException
{
    public $code = 200;
    public $msg = '密匙错误';
    public $error = 90000;
}