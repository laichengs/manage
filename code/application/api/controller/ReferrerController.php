<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/5
 * Time: 15:38
 */

namespace app\api\controller;


use app\api\model\Referrer;
use app\Exception\DataException;

class ReferrerController
{
    public function checkReferrer($number){
        $referrer = new Referrer();
        $result = $referrer->where('number', '=', $number)->find();
        if(!$result){
            throw new DataException(['msg' => '没有此推荐人']);
        }
        return $result;
    }
}