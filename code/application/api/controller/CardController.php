<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/11/23
 * Time: 14:41
 */

namespace app\api\controller;


use app\api\model\CardCombo;
use app\api\model\CardInventory;
use app\api\model\CardKey;
use app\api\model\CardList;
use app\api\model\User;
use app\api\service\TokenService;
use app\api\validate\CardValidate;
use app\Enum\CardEnum;
use app\Exception\CardException;
use think\Db;
use think\Exception;

class CardController
{
    /*检测密匙是否有效*/
    public function checkCard($key){
        (new CardValidate())->goCheck();
        $model = new CardKey();
        $result = $model->where('key', '=', $key)->find();
        if(!$result){
            throw new CardException();
        }
        if($result->status == '0'){
            throw new CardException(['msg' => '该密匙已使用', 'error'=>403]);
        }
        /*执行数据库操作*/
        Db::startTrans();
        try{
            //更改cardkey状态
            $model->where('key', '=', $key)->update(['status'=>0]);
            //发送电子券给客户
            $this->sendCardCombo($result->card_combo_id);
            Db::commit();
            return json(['msg'=>'兑换成功', 'code'=>200, 'error'=>0], 200);
        }catch(Exception $e){
            Db::rollback();
            throw new Exception();
        }
    }

    /*发送电子券*/
    private function sendCardCombo($card_combo_id){
        $result = CardCombo::get($card_combo_id);
        $list = $result->card_combo;
        $model = new CardList();
        $cards = $model->where('id', 'in', $list)->select();
        $user_id = TokenService::getTokenVar('id');
        $user = User::get($user_id);
        $phone = $user->phone;
        foreach ($cards as $value){
            $cardInventory = new CardInventory();
            $grant_date = time();
            $expiry_date = time() + 60*60*24*$value->circle;
            $data = [
                'card_id' => $value->id,
                'grant_date' => $grant_date,
                'expiry_date' => $expiry_date,
                'item_id' => $value->item_id,
                'user_id' => $user_id,
                'phone' => $phone,
                'amount' => $value->value,
                'minimum_amount' => $value->minimum_amount,
                'status' => '0'
            ];
            $cardInventory->save($data);
        }
    }
    /*获取不同类型券的数量*/
    public function getCardTypeCount(){
        $data = [
            'no_use' => 0,
            'used' => 0,
            'expire' => 0
        ];
        $user_id = TokenService::getTokenVar('id');
        $model = new CardInventory();
        $result = $model->where('user_id', '=', $user_id)->select();
        if(!$result){
            throw new CardException(['msg'=>'暂无套餐数据','error'=>404]);
        }
        foreach($result as $value){
            switch($value->status){
                case CardEnum::NO_USED :
                    $data['no_use']++;
                    break;
                case CardEnum::USED :
                    $data['used']++;
                    break;
                case CardEnum::EXPIRE :
                    $data['expire']++;
                    break;
            }
        }
        return json(['msg'=>'ok','result'=>$data, 'error'=>0], 200);
    }

    /*获取当前类型券的详细列表信息*/
    public function getOneTypeDetails($type){
        $user_id = TokenService::getTokenVar('id');
        $model = new CardInventory();
        $result = $model->with(['item', 'card'])->where('user_id', '=', $user_id)->where('status', '=', $type)->select();
        if(!$result){
            throw new CardException(['msg'=>'没有对应的数据', 'error'=>404]);
        }
        foreach($result as $key=>$value){
            $result[$key]['expiry_date'] = date('Y-m-d', $value->expiry_date);
        }
        return $result;
    }

    /*获取用户当前有效可用的优惠券*/
    public function getCardByUseAndItem($item){
        $user_id = TokenService::getTokenVar('id');
        $model = new CardInventory();
        $result = $model->with(['item', 'card'])
            ->where('user_id', '=', $user_id)->select();
        if(!$result){
            throw new CardException(['msg'=>'没有对应的数据', 'error'=>404]);
        }
        $data = $use = $no_use = [];
        foreach($result as $key=>$value){
            if($value->item_id == $item){
                array_push($use, $value);
            }else{
                array_push($no_use, $value);
            }
            $result[$key]['expiry_date'] = date('Y.m.d', $value->expiry_date);
            $result[$key]['grant_date'] = date('Y.m.d', $value->grant_date);
        }
        array_push($data, $use);
        array_push($data, $no_use);
//        $data['use'] = $use;
//        $data['no_use'] = $no_use;
        return json(['msg'=>'ok', 'result'=>$data], 200);
    }
}