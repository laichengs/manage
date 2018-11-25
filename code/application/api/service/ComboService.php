<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/6
 * Time: 8:44
 */

namespace app\api\service;


use app\api\model\Combo;
use app\api\model\ComboList;
use app\api\model\ComboRecharge;
use app\api\model\ComboRecord;
use app\api\model\Order;
use app\api\model\OrderTimeRecord;
use app\api\model\User;
use app\Exception\ComboException;
use app\Exception\OrderException;

class ComboService
{
    private $create_time;
    public function createOrder($params){
        $data = [];
        $data['serial_no'] = $this->makeNo();
        $data['user_id'] = TokenService::getTokenVar('id');
        $data['combo_id'] = $params['combo_id'];
        $data['price'] = $params['price'];
        $data['discount_price'] = $params['discount_price'];
        $data['count'] = $params['count'];
        $data['item_id'] = $params['item_id'];
        if(isset($params['referrer'])){
            $data['referrer_id'] = $params['referrer'];
        }
        $combo = new ComboRecharge();
        $combo->save($data);
        $result['order_id'] = $combo->id;
        $result['order_type'] = 'combo';
        $result['order_no'] = $data['serial_no'];
        $result['order_price'] = $params['discount_price'];
        $result['order_name'] = '美今管家-'.$params['title'].'套餐';
        return $result;
    }

    private function makeNo(){
        $year = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        $no = $year[date('Y',time())/2018].strtoupper(dechex(time('m',time()))).date('d')
            .substr(time(),-5).substr(microtime(),2,5).rand(1000,9999);
        return $no;
    }

    public function reduceComboList($combo_list_id, $order_id, $prepay_id){
        $list = ComboList::get($combo_list_id);
        $order = Order::get($order_id);
        /*判断订单是否支付*/
        if($order->status>1){
            throw new OrderException(['msg'=> '该订单已支付，请勿重复操作']);
        }
        /*检查套餐数量是否充足*/
        if($list->count < $order->count){
            throw new ComboException();
        }
        /*消减套餐清单库存*/
        $this->reduceListStock($combo_list_id, $order->count);
        /*更改订单状态、付款方式并记录prepay_id*/
        $this->changeOrderStatus($order_id, $prepay_id);
        /*增加消费记录*/
        $this->recordCombo($combo_list_id, $order_id, $order->item_id, $order->count);
        /*记录预约城市及时间信息*/
        $this->recordOrderTime($order->order_no);
        /*发送模板消息*/
        $this->sendMessage($combo_list_id, $order_id, $prepay_id);

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

    private function reduceListStock($combo_list_id, $count){
        ComboList::where('id', '=', $combo_list_id)->setDec('count', $count);
    }

    private function changeOrderStatus($order_id, $prepay_id){
        $map = [
            'status' => 2,
            'pay_type' => 3,
            'prepay_id' => $prepay_id
        ];
        Order::where('id', '=', $order_id)->update($map);
    }

    private function recordCombo($combo_list_id, $order_id, $item_id, $order_count){
        $data = [];
        $data['user_id'] = TokenService::getTokenVar('id');
        $data['count'] = $order_count;
        $data['combo_list_id'] = $combo_list_id;
        $data['order_id'] = $order_id;
        $data['item_id'] = $item_id;
        $model = new ComboRecord();
        $model->save($data);
        $this->create_time = $model->create_time;
    }

    private function sendMessage($combo_list_id, $order_id, $prepay_id){
        $params = [];
        $comboList = ComboList::with(['title'])->where('id', '=', $combo_list_id)->find();
        $params['openid'] = TokenService::getTokenVar('openid');
        $params['prepay_id'] = $prepay_id;
        $params['create_time'] = $this->create_time;
        $params['title'] = $comboList->title->title;
        $params['name'] = Order::where('id', '=', $order_id)->find()->snap_name;
        $message = new ComboListMessage();
        $message->sendMessage($params);
    }
}