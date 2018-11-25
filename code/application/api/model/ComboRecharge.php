<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/6
 * Time: 9:33
 */

namespace app\api\model;


class ComboRecharge extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function combo(){
        return $this->belongsTo('Combo', 'combo_id', 'id');
    }

    public function user(){
        return $this->belongsTo('User', 'user_id', 'id');
    }

    public function referrer(){
        return $this->belongsTo('Referrer', 'referrer_id', 'id');
    }
}