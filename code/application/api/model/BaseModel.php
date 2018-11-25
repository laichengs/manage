<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 11:27
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    protected function processUrl($value){
        return config('setting.img_url_prefix').$value;
    }
}