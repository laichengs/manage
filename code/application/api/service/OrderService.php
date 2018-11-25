<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/31
 * Time: 11:26
 */

namespace app\api\service;


use app\api\model\Order;
use think\Request;

class OrderService
{
    public function createOrder(){
        $results = Request::instance()->param();
        //return $results;
        $order = new Order();
        $order_no = $this->makeNo();
        $order->order_no = $order_no;
        $order->user_id = TokenService::getTokenVar('id');
        $order->phone = $results['phone'];
        $order->item_id = $results['item_id'];
        $order->count = $results['count'];
        $order->total_price = $results['total_price'];
        $order->card_amount = $results['card_amount'];
        $order->card_id = $results['card_id'];
        $order->address = json_encode($results['address']);
        $order->snap_name = $results['snap_name'];
        $order->snap_img = $results['snap_img'];
        $order->snap_price = $results['snap_price'];
        $order->city_id = $results['city_id'];
        $order->order_name = $results['order_name'];
        $order->order_data = $results['order_data'];
        $order->order_time = $results['order_time'];
        if($results['remark']){
            $order->remark = $results['remark'];
        }
        $order->save();
        $data = $order->id;
//        $data['order_no'] = $order_no.'AAA';
//        $data['order_type'] = 'order';
//        $data['order_name'] = '美今管家 -- '.$results['snap_name'];
//        $data['order_id'] = $order->id;
//        $data['order_price'] = $results['snap_price'] * $results['count'];
        return $data;

    }

    private function makeNo(){
        $year = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        $no = $year[date('Y',time())/2018].strtoupper(dechex(time('m',time()))).date('d')
        .substr(time(),-5).substr(microtime(),2,5).rand(1000,9999);
        return $no;
    }
}