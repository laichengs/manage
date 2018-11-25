<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 17:55
 */

namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden = ['update_time', 'create_time', 'delete_time', 'img_id'];
    public function img(){
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}