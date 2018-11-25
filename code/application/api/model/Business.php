<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/11/8
 * Time: 16:02
 */

namespace app\api\model;


class Business extends BaseModel
{
    public function city(){
        return $this->belongsTo('City', 'city_id', 'id');
    }

    public function img(){
        return $this->belongsTo('Image', 'thumb_img_id', 'id');
    }
}