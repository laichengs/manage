<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/29
 * Time: 17:11
 */

namespace app\api\model;


class PackageItem extends BaseModel
{
    public function img(){
        return $this->belongsTo('Image', 'img_id', 'id');
    }

    public function package(){
        return $this->belongsTo('Package', 'package_id', 'id');
    }

    public function item(){
        return $this->belongsTo('Item', 'item_id', 'id');
    }
}