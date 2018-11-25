<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/29
 * Time: 8:36
 */

namespace app\Exception;


class DataException extends BaseException
{
    public $code = 401;
    public $msg = '数据丢失或不存在';
    public $error = 80000;
}