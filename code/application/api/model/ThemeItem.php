<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/29
 * Time: 9:01
 */

namespace app\api\model;


class ThemeItem extends BaseModel
{
    public function img(){
        return $this->belongsTo('image', 'img_id', 'id');
    }

    public function item(){
        return $this->belongsTo('Item', 'item_id', 'id');
    }

    public function theme(){
        return $this->belongsTo('Theme', 'theme_id', 'id');
    }

    public function combo(){
        return $this->belongsTo('Combo', 'combo_id', 'id');
    }
}