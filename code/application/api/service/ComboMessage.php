<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/7
 * Time: 10:41
 */

namespace app\api\service;


class ComboMessage extends Message
{
    private $template_id;
    public function __construct()
    {
        parent::__construct();
        $this->template_id = '6oyCtVRSqBO_VD1QPSOFciYZ7sODhkeOrV72fxyPEy4';
    }

    public function sendMessage($params){
        $data = [];
        $data['touser'] = $params['openid'];
        $data['template_id'] = $this->template_id;
        $data['form_id'] = $params['prepay_id'];
        $data['data'] = [
            "keyword1" => [
                'value' => $params['name']
            ],
            "keyword2" => [
                'value' => $params['price']
            ],
            "keyword3" => [
                'value' => $params['serial_no']
            ]
        ];
        $data['emphasic_keyword'] = 'keyword1.DATA';
        curl_post($this->url, json_encode($data));
    }
}