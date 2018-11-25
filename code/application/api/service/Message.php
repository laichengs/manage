<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/4
 * Time: 17:34
 */

namespace app\api\service;


class Message
{
    protected $url;
    protected $accessToken;
    public function __construct()
    {
        $this->accessToken = AccessToken::getAccessToken();
        $this->url = sprintf(config('wx.wx_sendMessage_url'), $this->accessToken);
    }
}