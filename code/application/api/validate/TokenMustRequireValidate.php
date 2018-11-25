<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/31
 * Time: 8:36
 */

namespace app\api\validate;


use app\Exception\BaseException;

class TokenMustRequireValidate extends BaseException
{
    protected $rule = [
        'id' => 'require'
    ];

    protected $message = [
        'id' => 'code不存在'
    ];
}