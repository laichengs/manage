<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/4
 * Time: 10:09
 */

namespace app\api\controller;


use app\api\model\DiscountItem;
use app\api\model\Recharge;
use app\api\model\RechargeAmountList;
use app\api\service\RechargeService;

class RechargeController
{
    public function accountRecharge($params){
        $service = new RechargeService();
        $result = $service->recharge($params);
        return $result;
    }

    public function getRechargeAmountById($id){
        $model = new RechargeAmountList();
        $result = $model::get($id);
        return $result;
    }

    public function getDiscountItem(){
        if(cache('discount_item')){
            return unserialize(cache('discount_item'));
        }else{
            $model = new DiscountItem();
            $result = $model->select();
            cache('discount_item', seralize($result));
            return $result;
        }
    }

    public function getRechargeByManage($start, $number, $status=null, $referrer=null, $orderno=null){
        $model = new Recharge();
        $map = [];
        if(!empty($orderno)){
            $map['serial_no'] = $orderno;
        }
        if(!empty($status) && $status != 3){
            $map['status'] = $status;
        }
        if(!empty($referrer)){
            $map['referrer_id'] = $referrer;
        }
        $data['total'] = $model->where($map)->limit($start, $number)->count();
        $data['rows'] = $model->with(['referrer', 'user'])->where($map)->limit($start, $number)->select();
        return $data;

    }

    public function getAmounts(){
        $model = new RechargeAmountList();
        $result = $model->select();
        return $result;
    }
}