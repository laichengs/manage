<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/4
 * Time: 15:08
 */

namespace app\api\model;


class Account extends BaseModel
{
    public function user(){
        return $this->belongsTo('User', 'user_id', 'id');
    }
}