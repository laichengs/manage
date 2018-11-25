<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/13
 * Time: 9:40
 */

namespace app\api\controller;


use app\api\model\Manage;
use app\api\service\TokenService;
use app\Exception\LoginException;
use think\Cache;

class ManageController
{
    private $cacheKey;
    private $cacheValue;
    public function login($user, $pass){
        $model = new Manage();
        $map = [
            'appid' => $user,
            'app_secret' => $pass
        ];
        $result = $model->where($map)->find();
        if(!$result){
            throw new LoginException();
        }
        $value = [];
        $value['uid'] = $result->id;
        $value['scope'] = $result->scope;
        $this->cacheValue = json_encode($value);
        $this->cacheData();
        return $this->cacheKey;
    }

    private function cacheData(){
        $this->cacheKey = TokenService::createToken();
        cache($this->cacheKey, $this->cacheValue, config('setting.token_expire'));
    }

    public function checkStatus($token){
        $result = Cache::get($token);
        if(!$result){
            return false;
        }
        return true;
    }


}