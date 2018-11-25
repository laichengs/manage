<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/12
 * Time: 14:55
 */

namespace app\api\model;


class ComboRecord extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function item(){
        return $this->belongsTo('item', 'item_id', 'id');
    }

}