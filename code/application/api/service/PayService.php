<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/31
 * Time: 14:23
 */

namespace app\api\service;


use app\api\model\ComboRecharge;
use app\api\model\Order;
use app\api\model\Recharge;
use app\Exception\OrderException;
use think\Loader;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');
class PayService
{
    private $order_id;
    private $order_type;
    public function pay($params){
        $this->order_id = $params['order_id'];
        $result = Order::get($this->order_id);
        if($params['order_type'] == 'order'){
            if($result->status > 1){
                throw new OrderException(['msg'=> '该订单已支付!']);
            }
        }
        $this->order_type = $params['order_type'];
       // return $params['order_no'];
        return $this->makeWxPreOrder($params);
    }
    /*统一下单接口*/
    private function makeWxPreOrder($params){
        $input = new \WxPayUnifiedOrder();
        $input->SetOut_trade_no($params['order_no']);
        $input->SetTrade_type('JSAPI');
        $input->SetTotal_fee($params['order_price']*100);
        $input->SetBody($params['order_name']);
        $input->SetOpenid(TokenService::getTokenVar('openid'));
        $input->SetNotify_url(config('wx.wx_notify_url').$this->order_type);
        return $this->getPaySignature($input);
    }
    /*生成签名*/
    private function getPaySignature($input){
        $config = new \WxPayConfig();
        $wxOrder = \WxPayApi::unifiedOrder($config, $input);
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS'){
            Log::record($wxOrder, 'error');
            log::record('获取预订单失败');
        }

        $this->recordPreOrder($wxOrder);

        return $this->sign($wxOrder);
    }
    /*记录prepay_id*/
    private function recordPreOrder($wxOrder){
        switch ($this->order_type){
            case 'order':
                $order = new Order();
                break;
            case 'recharge':
                $order = new Recharge();
                break;
            case "combo":
                $order = new ComboRecharge();
                break;
        }
        //$order = new Order();
        $result = $order->where('id', '=', $this->order_id)->update(['prepay_id'=>$wxOrder['prepay_id']]);
        if(!$result){
            throw new OrderException(['msg' => '订单修改失败']);
        }
    }
    /*订单签名*/
    private function sign($wxOrder){
        $wxJsApi = new \WxPayJsApiPay();
        $config = new \WxPayConfig();
        $wxJsApi->SetAppid(config('wx.app_id'));
        $wxJsApi->SetTimeStamp((string)time());
        $wxJsApi->SetSignType('md5');
        $wxJsApi->SetNonceStr(md5($wxOrder['nonce_str'].rand(0,9999)));
        $wxJsApi->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $wxJsApi->SetSign($config);
        return $wxJsApi->GetValues();
    }
}