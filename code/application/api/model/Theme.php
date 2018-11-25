<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/29
 * Time: 9:01
 */

namespace app\api\model;


class Theme extends BaseModel
{
    public function item(){
        return $this->hasMany('theme_item','theme_id', 'id');
    }
}