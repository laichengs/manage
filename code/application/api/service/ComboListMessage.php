<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/12
 * Time: 15:41
 */

namespace app\api\service;


class ComboListMessage extends Message
{
    private $template_id;
    public function __construct()
    {
        parent::__construct();
        $this->template_id = 'B97uo99fqDlhrjMqrOobmbP5A0jQUowSL3bBtem-SYI';
    }

    public function sendMessage($params){
        $data = [];
        $data['touser'] = $params['openid'];
        $data['template_id'] = $this->template_id;
        $data['form_id'] = $params['prepay_id'];
        $data['data'] = [
            "keyword1" => [
                'value' => $params['title']
            ],
            "keyword2" => [
                'value' => $params['create_time']
            ],
            "keyword3" => [
                'value' => '订单消费--'.$params['name']
            ]
        ];
        $data['emphasic_keyword'] = 'keyword1.DATA';
        curl_post($this->url, json_encode($data));
    }
}