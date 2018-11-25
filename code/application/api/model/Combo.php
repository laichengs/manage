<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/5
 * Time: 14:08
 */

namespace app\api\model;


class Combo extends BaseModel
{
    public function img(){
        return $this->belongsTo('Image', 'img_id', 'id');
    }

    public function item(){
        return $this->belongsTo('Item', 'item_id', 'id');
    }

    public function getTagsAttr($value){
        return json_decode($value);
    }
}