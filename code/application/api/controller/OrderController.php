<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/31
 * Time: 11:26
 */

namespace app\api\controller;


use app\api\model\Order;
use app\api\service\OrderService;
use app\api\service\TokenService;
use app\Exception\OrderException;
use think\Request;

class OrderController
{
    public function createOrder(){
        $service = new OrderService();
        return $service->createOrder();
    }

    public function getOrderData($id){
        $model = new Order();
        $result = $model::get($id);
        if(!$result){
            throw new OrderException(['msg' =>'未找到此订单数据']);
        }
        return $result;
    }

    public function getOrderListByStatus($status, $page=1, $count=15){
        $model = new Order();
        $uid = TokenService::getTokenVar('id');
        if($status == 0){
            $result = $model->order('create_time desc')
                ->where('user_id', '=', $uid)->select();
        }else{
            $result = $model->where('status', '=', $status)
                ->order('create_time desc')
                ->where('user_id', '=', $uid)->select();
        }

        if(!$result){
            throw new OrderException();
        }
        return $result;
    }

    public function getOrderByManage($start, $number, $orderno=null , $status=null, $phone=null, $user=null){
        $model = new Order();
        $map = [];
        if(!empty($orderno)){
            $map['order_no'] = $orderno;
        }
        if(!empty($status) || $status != 0){
            $map['status'] = $status;
        }
        if(!empty($phone)){
            $map['phone'] = $phone;
        }
        if(!empty($user)){
            $map['order_name'] = $user;
        }
        $data['total'] = $model->where($map)->limit($start, $number)->count();
        $data['rows'] = $model->with(['item'])->where($map)->limit($start, $number)->order('create_time desc')->select();
        return $data;

    }
}