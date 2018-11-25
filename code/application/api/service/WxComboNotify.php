<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/4
 * Time: 14:59
 */

namespace app\api\service;


use app\api\model\Account;
use app\api\model\ComboList;
use app\api\model\ComboRecharge;
use app\api\model\Recharge;
use app\api\model\User;
use app\Exception\DataException;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');
class WxComboNotify extends \WxPayNotify
{
    public function NotifyProcess($objData, $config, &$msg)
    {
        $data = $objData->GetValues();
        if($data['return_code'] == 'SUCCESS'){
            $serial_no = $data['out_trade_no'];
            Db::startTrans();
            try{
                $this->changeComboRechargeStatus($serial_no);
                $this->writeComboList($serial_no);
                $this->sendSms($serial_no);
                Db::commit();
            }catch (Exception $e){
                Db::rollback();
                Log::error($e);
                return false;
            }
            $this->sendNotifyMessage($serial_no);
        }
        return true;

    }


    private function sendSms($serial_no){
        $result = ComboRecharge::with(['combo', 'user'])->where('serial_no','=', $serial_no)->find();
        $data = [];
        $data['phone'] = $result->user->phone;
        $data['sms_id'] = 'SMS_148525045';
        $data['params'] = [
            'title' => $result->combo->title
        ];
        $sms = new SmsService();
        $sms->sendSms($data);
    }

    private function changeComboRechargeStatus($serial_no){
        ComboRecharge::where('serial_no','=',$serial_no)->update(['status'=>1]);
    }

    private function writeComboList($serial_no){
        $recharge = new ComboRecharge();
        $result = $recharge->where('serial_no', '=', $serial_no)->find();
        $data = [];
        $data['user_id'] = $result->user_id;
        $data['referrer_id'] = $result->referrer_id;
        $data['price'] = $result->discount_price;
        $data['count'] = $result->count;
        $data['item_id'] = $result->item_id;
        $data['combo_id'] = $result->combo_id;
        $comboList = new ComboList();
        $comboList->save($data);
    }

    private function sendNotifyMessage($serial_no){
        $data = [];
        $recharge = new ComboRecharge();
        $result = $recharge->with(['combo', 'user'])->where('serial_no', '=', $serial_no)->find();
        $data['openid'] = $result->user->openid;
        $data['prepay_id'] = $result->prepay_id;
        $data['name'] = $result->combo->title.'套餐';
        $data['price'] = $result->discount_price.'元';
        $data['serial_no'] = $serial_no;
        $message = new ComboMessage();
        $message->sendMessage($data);
    }



}