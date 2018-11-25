<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/4
 * Time: 17:20
 */

namespace app\api\service;


use app\Exception\DataException;

class AccessToken
{
    public static function getAccessToken(){
        $url =  sprintf(config('wx.wx_accessToken_url'), config('wx.app_id'), config('wx.app_secret'));
        $result = curl_get($url);
        if(!$result){
            throw new DataException(['获取accesstoken失败']);
        }
        return json_decode($result)->access_token;
    }
}