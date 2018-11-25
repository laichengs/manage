<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/4
 * Time: 17:38
 */

namespace app\api\service;


class RechargeMessage
{
    private $template_id;
    public function __construct()
    {
        parent::__construct();
        $this->template_id = 'QnXiwEdoy6PxSJTKiSnOEOIGn9LrAvFVxTjoIIq-4M4';
    }

    public function sendMessage($params){
        $data = [];
        $data['touser'] = $params['openid'];
        $data['template_id'] = $this->template_id;
        $data['form_id'] = $params['prepay_id'];
        $data['data'] = [
            "keyword1" => [
                'value' => '美今管家-会员充值'
            ],
            "keyword2" => [
                'value' => $params['create_time']
            ],
            "keyword3" => [
                'value' => $params['amount'].'元'
            ],
            "keyword4" => [
                'value' => $params['serial_no']
            ],
            "keyword5" => [
                'value' => $params['balance']
            ]
        ];
        $data['emphasic_keyword'] = 'keyword1.DATA';
        curl_post($this->url, json_encode($data));
    }
}