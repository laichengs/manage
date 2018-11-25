<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/31
 * Time: 11:50
 */

namespace app\api\model;


class Order extends BaseModel
{
      protected $autoWriteTimestamp = true;
    protected $hidden = ['delete_time'];
    public function getAddressAttr($value){
       return json_decode($value);
    }
  
      public function item(){
        return $this->belongsTo('Item', 'item_id', 'id');
    }

    public function user(){
        return $this->belongsTo('User', 'user_id', 'id');
    }
}