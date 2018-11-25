<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/4
 * Time: 16:32
 */

namespace app\api\controller;


use app\api\model\Account;
use app\api\service\AccountService;
use app\api\service\TokenService;
use app\Exception\DataException;

class AccountController
{
    public function getAccount(){
        $uid = TokenService::getTokenVar('id');
        $result  = Account::with('user')->where('user_id', '=', $uid)->find();
        if(!$result){
            throw new DataException();
        }
        return $result;
    }

    public function checkBalance($count){
        $service = new AccountService();
        return $service->checkBalance($count);
    }

    public function reduceAccountBalance($count, $order_id, $prepay_id){
        $service = new AccountService();
        return $service->reduceAccountBalance($count, $order_id, $prepay_id);
    }
}