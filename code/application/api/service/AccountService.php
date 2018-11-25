<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/12
 * Time: 8:52
 */

namespace app\api\service;


use app\api\model\Account;
use app\api\model\AccountDetail;
use app\api\model\Order;
use app\api\model\OrderTimeRecord;
use app\Exception\AccountException;
use app\Exception\OrderException;
use think\Db;
use think\Exception;

class AccountService
{
    private $account_id;
    private $create_time;
    public function reduceAccountBalance($count, $order_id, $prepay_id){
        /*检查余额是否充足*/
        if(!$this->checkBalance($count)){
            throw new AccountException();
        }
        /*检查订单是否支付*/
        $result = Order::get($order_id);
        if($result->status > 1){
            throw new OrderException(['msg'=> '套餐已支付']);
        }
        try{
            Db::startTrans();
            /*消减账户余额*/
            $this->reduceAccount($count);
            /*记录账户消减*/
            $this->recordAccount($count, $order_id);
            /*更改订单状态和订单支付方式,记录prepay_id*/
            $this->changeOrderStatus($order_id, $prepay_id);
            /*记录预约城市和时间信息*/
            $this->recordOrderTime($result->order_no);
            /*发送模板消息*/
            $this->sendMessage($count, $order_id);
            Db::commit();
        }catch(Exception $e){
            throw new AccountException(['msg'=> '发生未知错误']);
            Db::rollback();
        }
        return "123";
    }

    public function checkBalance($count){
        $uid = TokenService::getTokenVar('id');
        $result = Account::where('user_id', '=', $uid)->find();
        if(!$result){
            return false;
        }
        if($count > ($result->balance)){
            return false;
        }
        $this->account_id = $result->id;
        return true;
    }

    private function reduceAccount($count){
        $uid = TokenService::getTokenVar('id');
        $result = Account::where('user_id', '=', $uid)->setDec('balance', $count);
        if(!$result){
            throw new AccountException(['msg'=> '修改余额失败']);
        }
    }

    private function recordAccount($count, $order_id){
        $data = [];
        $data['user_id'] = TokenService::getTokenVar('id');
        $data['account_id'] = $this->account_id;
        $data['order_id'] = $order_id;
        $data['amount'] = $count;
        $record = new AccountDetail();
        $result = $record->save($data);
        $this->create_time = $record->create_time;
        if(!$result){
            throw new AccountException(['msg'=> '新增订单明细记录失败']);
        }
    }

    private function changeOrderStatus($order_id, $prepay_id){
        $map = [
            'status' => 2,
            'pay_type' => 2,
            'prepay_id' => $prepay_id
        ];
        $result = Order::where('id', '=', $order_id)->update($map);
        if(!$result){
            throw new OrderException(['msg' => '修改订单状态和付款方式失败']);
        }
    }

    private function sendMessage($count, $order_id){
        $uid = TokenService::getTokenVar('id');
        $account = Account::where('user_id', '=', $uid)->find();
        $result = Order::get($order_id);
        $params = [];
        $params['openid'] = TokenService::getTokenVar('openid');
        $params['form_id'] = $result->prepay_id;
        $params['serial_no'] = $result->order_no;
        $params['count'] = $count.'元';
        $params['create_time'] = $this->create_time;
        $params['balance'] = $account->balance.'元';
        $params['title'] = $result->snap_name;
        $message = new AccountRecordMessage();
        $message->sendMessage($params);
    }

    private function recordOrderTime($serial_no){
        $data = [];
        $result = Order::where('order_no', '=', $serial_no)->find();
        $data['order_data'] = $result->order_data;
        $data['order_time'] = $result->order_time;
        $data['city_id'] = $result->city_id;
        $data['item_id'] = $result->item_id;
        $record = new OrderTimeRecord();
        $record->save($data);
    }

}