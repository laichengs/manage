<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/4
 * Time: 14:59
 */

namespace app\api\service;


use app\api\model\Account;
use app\api\model\Recharge;
use app\api\model\RechargeAmountList;
use app\api\model\Sms;
use app\api\model\User;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');
class WxRechargeNotify extends \WxPayNotify
{
    public function NotifyProcess($objData, $config, &$msg)
    {
        $data = $objData->GetValues();
        if($data['return_code'] == 'SUCCESS'){
            $serial_no = $data['out_trade_no'];
            Db::startTrans();
            try{
                $recharge = new Recharge();
                $result = $recharge->where('serial_no', '=', $serial_no)->find();
                $uid = $result->user_id;
                $amount = $result->amount;
                $this->changeAccount($uid, $amount);
                $this->changeUserStatus($uid, $result->recharge_list_id);
                $this->changeRechargeStatus($serial_no);
                $this->sendRechargeMessage($uid, $amount, $serial_no);
                $this->sendSms($uid, $amount);
                Db::commit();
            }catch (Exception $e){
                Db::rollback();
                Log::error($e);
                return false;
            }
        }
        return true;

    }

    private function sendSms($uid, $amount){
        $result = User::get($uid);
        $data = [];
        $data['phone'] = $result->phone;
        $data['sms_id'] = 'SMS_148520062';
        $data['params'] = [
            'amount' => $amount
        ];
        $sms = new SmsService();
        $sms->sendSms($data);
    }

    private function sendRechargeMessage($uid, $amount, $serial_no){
        $data = [];
        $result  = Recharge::where('serial_no', '=', $serial_no)->find();
        $data['prepay_id'] = $result['prepay_id'];
        $data['create_time'] = $result['create_time'];
        $data['serial_no'] = $serial_no;
        $data['amount'] = $amount;
        $account = Account::where('user_id', '=', $uid)->find();
        $user = User::where('id', '=', $uid)->find();
        $data['openid'] = $user->openid;
        $data['balance'] = round($account->balance, 2).'元';
        User::where('id', '=', 9)->update(['txt'=>json_encode($data)]);
        $message = new RechargesMessage();
        $message->sendMessage($data);
    }

    private function changeRechargeStatus($serial_no){
        Recharge::where('serial_no','=',$serial_no)->update(['status'=>1]);
    }

    private function changeAccount($uid, $amount){
        $account = new Account();
        $account->where('user_id', '=', $uid)->setInc('balance', $amount);
    }

    private function changeUserStatus($uid, $list_id){
        $result = RechargeAmountList::get($list_id);
        $level = $result->level;
        User::where('id', '=', $uid)->update(['vip'=>$level]);
    }

}