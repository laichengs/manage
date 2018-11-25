<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 11:28
 */

namespace app\api\model;


class Image extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time','create_time', 'id'];
    protected function getUrlAttr($value){
        return $this->processUrl($value);
    }
}