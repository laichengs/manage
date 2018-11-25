<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 17:56
 */

namespace app\Exception;


class CategoryException extends BaseException
{
    public $code = 401;
    public $msg = '当前分类不存在';
    public $error = 20000;
}