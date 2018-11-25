<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/6
 * Time: 15:01
 */

namespace app\api\service;
use app\api\model\User;

class OrderMessage extends Message
{
    private $template_id;
    public function __construct()
    {
        parent::__construct();
        $this->template_id = 'Gr3A3Pi6DRIjBSVSXLEfhGhwOGw9VaJboLx70HOEG8k';
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
                'value' => $params['serial_no']
            ],
            "keyword3" => [
                'value' => $params['order_time']
            ],
            "keyword4" => [
                'value' => $params['address']
            ],
            "keyword5" => [
                'value' => $params['total_price']
            ],
            "keyword6" => [
                'value' => '已支付'
            ]
        ];
        $data['emphasic_keyword'] = 'keyword1.DATA';
        curl_post($this->url, json_encode($data));
    }
}