<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/4
 * Time: 10:11
 */

namespace app\api\service;


use app\api\model\Recharge;

class RechargeService
{
    public function recharge($params){
        $recharge = new Recharge();
        $recharge->user_id = TokenService::getTokenVar('id');
        $recharge->serial_no = $this->makeNo();
        $recharge->amount = $params['amount'];
        $recharge->discount_amount = $params['discount_amount'];
        $recharge->referrer_id = $params['referrer'];
        $recharge->recharge_list_id = $params['recharge_list_id'];
        $recharge->save();
        $data = [];
        $data['order_id'] = $recharge->id;
        $data['order_no'] = $recharge->serial_no;
        $data['order_name'] = $params['amount'].'元会员充值';
        $data['order_type'] = 'recharge';
        $data['order_price'] = $params['discount_amount'];
        return $data;
    }

    private function makeNo(){
        $year = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        $no = $year[date('Y',time())/2018].strtoupper(dechex(time('m',time()))).date('d')
            .substr(time(),-5).substr(microtime(),2,5).rand(1000,9999);
        return $no;
    }
}