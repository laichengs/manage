<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 11:27
 */

namespace app\api\model;


class Banner  extends BaseModel
{
    public function item(){
        return $this->hasMany('BannerItem','banner_id', 'id');
    }
}