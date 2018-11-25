<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/31
 * Time: 8:38
 */

return [
    'app_id'=> 'wxb861990fcb580cae',
    'app_secret' => '151e316a14c70acdb69570b919cf51e8',
    'openid_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
    'wx_notify_url' => 'https://minicdn.meijinguanjia.com/api/notify/',
    'wx_accessToken_url' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
    'wx_sendMessage_url' => 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=%s',
];