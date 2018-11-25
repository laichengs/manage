<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 11:03
 */

namespace app\api\controller;


use app\api\model\ComboRecharge;
use app\api\model\Recharge;
use app\api\model\Referrer;
use app\api\model\User;
use app\api\service\CodeService;
use app\api\service\TokenService;
use app\api\validate\TokenMustRequireValidate;
use app\Exception\TokenException;

class UserController
{
    public function getUser(){
        $model = new User();
        return $model->with(['item'])->select();
    }

    public function getCode($phone){
        $service = new CodeService();
        return $service->getCode($phone);
    }

    public function checkCode($code, $phone){
        $service = new CodeService();
        $result = $service->checkCode($code, $phone);
        if($result){
            $this->recordPhone($phone);
        }
        return $result;
    }

    private function recordPhone($phone){
        $uid = TokenService::getTokenVar('id');
        User::where('id', '=', $uid)->update(['phone'=>$phone]);
    }

    public function getPhone(){
        $uid = TokenService::getTokenVar('id');
        $result = User::get($uid);
        return $result->phone;
    }

    public function updatePhone($phone){
        $uid = TokenService::getTokenVar('id');
        User::where('id', '=', $uid)->update(['phone'=>$phone]);
    }

    public function getToken($code){
       // (new TokenMustRequireValidate())->goCheck();
        $service = new TokenService();
        $token = $service->getToken($code);
        if(!$token){
            throw new TokenException();
        }
        return $token;
    }

    public function checkVip(){
       $uid = TokenService:: getTokenVar('id');
       $result = User::get($uid);
       if($result->vip == 0){
           return false;
       }
       return true;
    }

    public function getUserByManage(){
        $model = new User();
        $data['total'] = $model->count();
        $data['rows'] = $model->order('create_time desc')->select();
        return $data;
    }

    public function getRechargeOrderByReferrer($phone){
        $referrer = new Referrer();
        $result = $referrer->where('number', '=', $phone)->find();
        $id = $result->id;
        $recharge = new Recharge();
        $record = $recharge->with(['lists'])->where('referrer_id', '=', $id)->select();
        return $record;
           // return "123";
    }

    public function getComboRechargeOrderByReferrer($phone){
        $referrer = new Referrer();
        $result = $referrer->where('number', '=', $phone)->find();
        $id = $result->id;
        $combo = new ComboRecharge();
        $record = $combo->with(['combo.img'])->where('referrer_id', '=', $id)->select();
        return $record;
    }

    public function checkReferrer($phone){
        $referrer = new Referrer();
        $result = $referrer->where('number', '=', $phone)->find();
        if(!$result){
            return false;
        }
        return true;
    }
}