<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/10/17
 * Time: 11:44
 */

namespace app\api\service;


use app\api\model\Sms;
use app\Exception\CodeException;

class CodeService
{
    /*获取短信验证码*/
    public function getCode($phone){
        $code = rand(100000, 999999);
        $this->recordCode($code, $phone);
        $this->sendCode($code, $phone);
        return $code;
    }
    /*检查短信验证码*/
    public function checkCode($code, $phone){
        return $this->checkCodeFromDb($code, $phone);
    }

    /*到数据库检查code*/
    private function checkCodeFromDb($code, $phone){
        $sms = new Sms();
        $map = [
            'code' => $code,
            'phone' => $phone,
        ];
        $result = $sms->where($map)->find();
        if(!$result){
            throw new CodeException();
        }
        if(time() - $result->time > 300){
            throw new CodeException(['msg'=>'验证码已失效!请重新发送']);
        }
        return json(['msg'=>'验证成功', 'status'=>'ok'], 200);
    }

    /*记录code到数据库*/
    private function recordCode($code, $phone){
        $sms = new Sms();
        $data = [
            'code' => $code,
            'phone' => $phone,
            'time' => time()
        ];
        $sms->save($data);
    }

    /*发送验证码给客户手机*/
    private function sendCode($code, $phone){
        $sms = new SmsService();
        $data = [
            'phone' => $phone,
            'sms_id' => 'SMS_148380872',
            'params' => [
                'code' => $code
            ]
        ];
        $sms->sendSms($data);
    }
}