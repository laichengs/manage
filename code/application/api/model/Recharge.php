<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/4
 * Time: 10:38
 */

namespace app\api\model;


class Recharge extends BaseModel
{
    protected $autoWriteTimestamp = true;
    public function referrer(){
        return $this->belongsTo('Referrer', 'referrer_id', 'id');
    }

    public function user(){
        return $this->belongsTo('User', 'user_id', 'id');
    }

    public function lists(){
        return $this->belongsTo('RechargeAmountList', 'recharge_list_id', 'id');
    }
}