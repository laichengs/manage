<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 11:43
 */

namespace app\api\validate;


use think\Validate;

class IDValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|integer'
    ];

    protected $message = [
        'id' => '参数传递不合法'
    ];

}