<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/12
 * Time: 9:09
 */

namespace app\Exception;


class AccountException  extends BaseException
{
    public $code = 403;
    public $msg = '账户余额不足';
    public $error =  70000;
}