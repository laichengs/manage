<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 11:28
 */

namespace app\api\model;


class BannerItem extends BaseModel
{
    public function img(){
        return $this->belongsTo('Image','img_id', 'id');
    }

    public function item(){
        return $this->belongsTo('Item', 'item_id','id');
    }

    public function banner(){
        return $this->belongsTo('Banner', 'banner_id', 'id');
    }
}