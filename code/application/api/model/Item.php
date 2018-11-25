<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/29
 * Time: 10:19
 */

namespace app\api\model;


class Item extends BaseModel
{
    public function titleImg(){
        return $this->belongsTo('Image', 'title_img_id', 'id');
    }

    public function mainImg(){
        return $this->belongsTo('Image', 'main_img_id', 'id');
    }

    public function thumbImg(){
        return $this->belongsTo('Image', 'thumb_img_id', 'id');
    }

    public function detail(){
        return $this->hasMany('ItemImage', 'item_id', 'id');
    }
}