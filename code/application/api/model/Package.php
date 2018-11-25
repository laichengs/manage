<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/29
 * Time: 17:10
 */

namespace app\api\model;


class Package extends BaseModel
{
    public function item(){
        return $this->hasMany('PackageItem', 'package_id', 'id');
    }
}