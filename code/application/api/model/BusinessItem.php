<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/11/20
 * Time: 15:38
 */

namespace app\api\model;


class BusinessItem extends BaseModel
{
    public function img(){
        return $this->belongsTo('Image', 'img_id', 'id');
    }

    public function business(){
        return $this->belongsTo('Business', 'business_id', 'id');
    }
}