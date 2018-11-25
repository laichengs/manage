<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/11/24
 * Time: 上午12:58
 */

namespace app\api\validate;


class CardValidate extends BaseValidate
{
    protected $rule = [
      'key' => 'require|integer|min:6'
    ];

    protected $message = [
        'key.min' => '密匙的长度值不得小于6位'
    ];
}