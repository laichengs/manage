<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/29
 * Time: 10:20
 */

namespace app\api\model;


class ItemImage extends BaseModel
{
    public function img(){
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}