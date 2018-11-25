<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/30
 * Time: 11:26
 */

namespace app\api\model;


class Notice extends BaseModel
{
    public function img(){
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}