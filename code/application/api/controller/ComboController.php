<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/5
 * Time: 14:08
 */

namespace app\api\controller;


use app\api\model\Combo;
use app\api\model\ComboList;
use app\api\model\ComboRecharge;
use app\api\model\ComboRecord;
use app\api\model\Image;
use app\api\model\Order;
use app\api\model\Referrer;
use app\api\model\User;
use app\api\service\ComboService;
use app\api\service\TokenService;
use app\Exception\DataException;
use think\Db;

class ComboController
{
    public function getCombo(){
        if(cache('combos')){
            return unserialize(cache('combos'));
        }else{
            $combo = new Combo();
            $result = $combo->with('img')->where('status', '=', '1')->select();
            if(!$result){
                throw new DataException();
            }
            cache('combos', serialize($result));
            return $result;
        }
    }

    public function getComboDetail($id){
        if(cache('combo'.$id)){
            return unserialize(cache('combo'.$id));
        }else{
            $combo = new Combo();
            $result = $combo::get($id);
            if(!$result){
                throw new DataException();
            }
            cache('combo'.$id, serialize($result));
            return $result;
        }
    }

    public function createComboOrder($params){
        $service = new ComboService();
        $result = $service->createOrder($params);
        if(!$result){
            throw new DataException();
        }
        return $result;
    }
    /*获取消费记录*/
    public function getComboRecord(){
        $uid = TokenService::getTokenVar('id');
        $record = new ComboRecord();
        $results = $record->with(['item'])->where('user_id', '=', $uid)->select();
        return $results;
    }

    public function getComboCount(){
        $uid = TokenService::getTokenVar('id');
        $count = ComboList::where('user_id', '=', $uid)->count();
        return $count;
    }

    public function getComboList(){
        $uid = TokenService::getTokenVar('id');
        $result = ComboList::with(['title', 'combo'])->where('user_id', '=', $uid)->select();
        return $result;
    }

    public function showComboList($order_id){
        $order = Order::get($order_id);
        $item_id = $order->item_id;
        $uid = TokenService::getTokenVar('id');
        $result = ComboList::with(['title'])->where(['item_id'=>$item_id, 'user_id' => $uid])->select();
        return $result;
    }

    public function reduceComboList($combo_list_id, $order_id, $prepay_id){
        $service = new ComboService();
        return $service->reduceComboList($combo_list_id, $order_id, $prepay_id);
    }

    public function getComboByManage($start, $number, $status=null, $referrer=null, $orderno=null, $combo=null){
        $model = new ComboRecharge();
        $map = [];
        if(!empty($orderno)){
            $map['serial_no'] = $orderno;
        }
        if(!empty($status) && $status != 3){
            $map['status'] = $status;
        }
        if(!empty($combo) && $combo != 0){
            $map['combo_id'] = $combo;
        }
        if(!empty($referrer)){
            $result = Referrer::where('name', '=', $referrer)->find();
            if($result){
                $map['referrer_id'] = $result->id;
            }
            //$map['referrer_id'] = $referrer;
        }
        $data['total'] = $model->where($map)->limit($start, $number)->count();
        $data['rows'] = $model->with(['combo','user','referrer'])->where($map)->limit($start, $number)->select();
        return $data;
    }

    /*后台首页管理修改套餐*/
    public function getCombosByManage(){
        $model = new Combo();
        $data['total'] = $model->count();
        $data['rows'] = $model->with(['item', 'img'])->select();
        return $data;
    }
    /*后台增加套餐*/
    public function addComboByManage($params){
        $image= new Image();
        $image->save(['url'=> $params['img']]);
        $img_id = $image->id;
        $map = [
            'img_id' => $img_id,
            'count' => $params['count'],
            'item_id' => $params['item'],
            'describe' => $params['describe'],
            'price' => $params['price'],
            'title' => $params['name'],
            'status' => $params['status'],
            'unit' => $params['unit']
        ];
        $tem = explode(',', $params['tags']);
        $map['tags'] = json_encode($tem);
        $combo = new Combo();
        if($combo->save($map)){
            return true;
        };
        //return gettype($map['tags']);
    }
    /*获取后台单个套餐*/
    public function getOneComboByManage($id){
        $result = Combo::with(['img'])->where('id', '=', $id)->find();
        return $result;
    }
    /*修改套餐内容*/
    public function updateComboByManage($params){
        Db::startTrans();
        try{
            /*更改图片url*/
            Image::where('id', '=', $params['img']['id'])->update(['url' => $params['img']['url']]);
            /*更改套餐内容*/
            $map = [
                'count' => $params['count'],
                'item_id' => $params['item'],
                'title' => $params['name'],
                'unit' => $params['unit'],
                'describe' => $params['describe'],
                'tags' => json_encode(explode(',', $params['tags'])),
                'status' => $params['status'],
                'price' => $params['price']
            ];
            Combo::where('id', '=', $params['id'])->update($map);
            Db::commit();
            return true;
        }catch(Exception $exception){
            Db::rollback();
            return false;
        }
    }
    /*删除套餐内容*/
    public function deleteComboByManage($id){
        if(Combo::where('id', '=', $id)->delete()){
            return true;
        }else{
            return false;
        }
    }
}