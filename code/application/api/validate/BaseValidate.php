<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 13:56
 */

namespace app\api\validate;


use app\Exception\ParmaException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        $params = Request::instance()->param();
        if(!$params){
            throw new Exception();
        }
        if(!$this->check($params)){
            $msg = $this->batch()->getError();
            throw new ParmaException(['msg'=>$msg]);
        }
    }
}