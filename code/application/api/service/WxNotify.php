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
use app\api\model\User;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');
class WxNotify extends \WxPayNotify
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
                $this->changeUserStatus($uid);
                $this->changeRechargeStatus($serial_no);
                Db::commit();
            }catch (Exception $e){
                Db::rollback();
                Log::error($e);
                return false;
            }
        }
        return true;

    }
  
  
    private function changeRechargeStatus($serial_no){
        Recharge::where('serial_no','=',$serial_no)->update(['status'=>1]);
    }

    private function changeAccount($uid, $amount){
        $account = new Account();
        $account->where('user_id', '=', $uid)->setInc('blance', $amount);
    }

    private function changeUserStatus($uid){
        User::where('id', '=', $uid)->update(['vip'=>1]);
    }

}