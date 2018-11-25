<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/31
 * Time: 14:22
 */

namespace app\api\controller;


use app\api\model\User;
use app\api\service\PayService;
use app\api\service\WxComboNotify;
use app\api\service\WxOrderNotify;
use app\api\service\WxRechargeNotify;
use think\Loader;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class PayController
{
    public function wxPay($params){
        $service = new PayService();
        return $service->pay($params);
    }

    public function reviceRechargeNotify(){
        User::where('id', '=', 9)->update(['txt'=>3]);
        $wxNotify = new WxRechargeNotify();
        $config = new \WxPayConfig();
        $wxNotify->handle($config);
    }


    public function reviceOrderNotify(){
        $wxNotify = new WxOrderNotify();
        $config = new \WxPayConfig();
        $wxNotify->handle($config);
    }

    public function reviceComboNotify(){
        User::where('id', '=', 4)->update(['vip'=>3]);
        $wxNotify = new WxComboNotify();
        $config = new \WxPayConfig();
        $wxNotify->handle($config);
    }
}