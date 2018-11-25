<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/31
 * Time: 15:17
 */

namespace app\Exception;


class OrderException extends BaseException
{
    public $code = 401;
    public $msg = '获取订单失败';
    public $error = 50000;
}