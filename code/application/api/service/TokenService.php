<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/31
 * Time: 8:44
 */

namespace app\api\service;


use app\api\model\Account;
use app\api\model\User;
use app\Enum\ScopeEnum;
use app\Exception\TokenException;
use think\Cache;
use think\Db;
use think\Exception;
use think\Request;

class TokenService
{
    private $url;
    private $appid;
    private $app_secret;
    private $openid;
    private $cacheValue = [];
    private $cacheKey;
    public function __construct()
    {
        $this->url =  config('wx.openid_url');
        $this->appid = config('wx.app_id');
        $this->app_secret = config('wx.app_secret');
    }

    public function getToken($code){
        $url = $this->processUrl($code);
        $result = json_decode(curl_get($url));
        if(!empty($result->errcode)){
           throw new TokenException(['msg'=>'code无效或已过期']);
        }
        $this->openid = $result->openid;
        $token = self::createToken();
        $this->cacheData($token);
        return $token;
    }

    public static function createToken(){
        return md5(sha1(rand(0, 100000000)).time().config('wx.token_solt'));
    }

    private function cacheData($key){
        $cacheKey = $key;
        $cacheValue = $this->getCacheValue($this->openid);
        $result = cache($cacheKey, json_encode($cacheValue), config('wx.token_expire'));
        if(!$result){
            throw new TokenException(['msg' => 'token缓存失败']);
        }
    }

    private function getCacheValue($openid){
        $userModel = new User();
        $result = $userModel->where('openid', '=', $openid)->find();
        if(!$result){
            $data = [
                'openid' => $openid,
                'scope' =>ScopeEnum::NORMAL,
                'vip' => 0
            ];
            try{
                Db::startTrans();
                $insert = $userModel->save($data);
                if(!$insert){
                    throw new Exception(['msg'=>'新增用户数据失败']);
                }
                $map = [
                    'user_id' => $userModel->id,
                    'balance' => 0.00
                ];
                $account = new Account();
                $account->save($map);
                Db::commit();
            }catch(Exception $e){
                Db::rollback();
            }

            $data['id'] = $userModel->id;
        }else{
            $data = [
                'id' => $result->id,
                'openid' => $result->openid,
                'vip' => $result->vip,
                'scope' => $result->scope
            ];
        }
        $this->cacheValue = $data;
        return $data;
    }

    private function processUrl($code){
        $url = sprintf($this->url, $this->appid,$this->app_secret,$code);
        return $url;
    }

    public static function getTokenVar($var){
        $token = Request::instance()->header('token');
        $result = json_decode(Cache::get($token), true);
        if(!$result){
            throw new TokenException();
        }
        return $result[$var];
    }
}