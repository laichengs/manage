<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/4
 * Time: 14:59
 */

namespace app\api\service;


use app\api\model\Order;
use app\api\model\OrderTimeRecord;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');
class WxOrderNotify extends \WxPayNotify
{
    public function NotifyProcess($objData, $config, &$msg)
    {
        $data = $objData->GetValues();
        if($data['return_code'] == 'SUCCESS'){
            $serial_no = $data['out_trade_no'];
            Db::startTrans();
            try{
                $this->changeOrderStatus($serial_no);
                $this->sendOrderMessage($serial_no);
                $this->recordOrderTime($serial_no);
                $this->sendSms($serial_no);
                Db::commit();
            }catch (Exception $e){
                Db::rollback();
                Log::error($e);
                return false;
            }
        }
        return true;

    }

    private function sendSms($serial_no){
        $data = [];
        $result  = Order::where('order_no', '=', $serial_no)->find();
        $data['phone'] = trim($result->phone);
        $data['sms_id'] = 'SMS_148083145';
        $data['params'] = [
            'data' => $result->order_data,
            'time' => $result->order_time,
            'item' => $result->snap_name
        ];
        $sms = new SmsService();
        $sms->sendSms($data);
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

    private function sendOrderMessage($serial_no){
        $data = [];
        $result  = Order::with(['item','user'])->where('order_no', '=', $serial_no)->find();
        $data['openid'] = $result->user->openid;
        $data['title'] = $result->item->name;
        $data['prepay_id'] = $result['prepay_id'];
        $data['order_time'] = $result['order_data'].' '.$result['order_time'];
        $data['serial_no'] = $serial_no;
        $data['address'] = $result->address->address;
        $data['total_price'] = $result->snap_price * $result->count.'元';;
        $message = new OrderMessage();
        $message->sendMessage($data);
    }

    private function changeOrderStatus($serial_no){
        Order::where('order_no', '=', $serial_no)->update(['status'=>'2']);
    }

}