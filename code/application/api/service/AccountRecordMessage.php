<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/12
 * Time: 9:58
 */

namespace app\api\service;


class AccountRecordMessage extends Message
{
    private $template_id;
    public function __construct()
    {
        parent::__construct();
        $this->template_id = '3Nvh_0Z1aJqITrbXHOlKujUTUJ4mC4bXb3K8Ew9mDws';
    }

    public function sendMessage($params){
        $data = [];
        $data['touser'] = $params['openid'];
        $data['template_id'] = $this->template_id;
        $data['form_id'] = $params['form_id'];
        $data['data'] = [
            "keyword1" => [
                'value' => $params['serial_no']
            ],
            "keyword2" => [
                'value' => $params['count']
            ],
            "keyword3" => [
                'value' => $params['create_time']
            ],
            "keyword4" => [
                'value' => $params['balance']
            ],
            "keyword5" => [
                'value' => '订单消费--'.$params['title']
            ]
        ];
        $data['emphasic_keyword'] = 'keyword1.DATA';
        curl_post($this->url, json_encode($data));
    }
}