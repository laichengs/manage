<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/30
 * Time: 11:26
 */

namespace app\api\controller;


use app\api\model\Notice;
use app\api\validate\IDValidate;
use app\Exception\DataException;

class NoticeController
{
    public function getNotice($id){
        (new IDValidate())->goCheck();
        $model = new Notice();
        $result = $model->with('img')->where('id', '=', $id)->find();
        if(!$result){
            throw new DataException();
        }
        return $result;
    }
}