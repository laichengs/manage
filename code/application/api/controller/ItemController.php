<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/29
 * Time: 10:19
 */

namespace app\api\controller;


use app\api\model\Item;
use app\api\service\ItemService;
use app\api\validate\IDValidate;
use app\Exception\DataException;
use think\Cache;

class ItemController
{
    public function getItem($id){
        if(cache('item'.$id)){
            return unserialize(cache('item'.$id));
        }else{
            (new IDValidate())->goCheck();
            $model = new Item();
            $result = $model->with(['titleImg','mainImg', 'detail.img', 'thumbImg'])->find($id);
            if(!$result){
                throw new DataException();
            }
            cache('item'.$id, serialize($result));
            return $result;
        }
    }

    public function getIndexItems(){
        if(cache('index_item')){
            return unserialize(cache('index_item'));
        }else{
            $model = new Item();
            $result = $model->with(['titleImg', 'thumbImg'])->order('sort ASC')->where('is_show_index', '=', 1)->select();
            if(!$result){
                throw new DataException();
            }
            cache('index_item', serialize($result));
            return $result;
        }
    }

    public function getItemByManage($start, $number, $status=null, $referrer=null, $orderno=null, $combo=null){
        $model = new Item();
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
        $data['rows'] = $model->with(['titleImg', 'mainImg', 'thumbImg'])->where($map)->limit($start, $number)
            ->order('sort DESC')->select();
        return $data;
    }

    public function changeItemSort($id, $sort){
        $result = Item::where('id', '=', $id)->update(['sort'=> $sort]);
        return $result;
    }

    public function getOneItemByManage($id){
        $result = Item::with(['titleImg', 'mainImg', 'detail.img', 'thumbImg'])->where('id', '=', $id)->find();
        return  $result;
    }

    public function updateItemByManage($params){
        $service = new ItemService();
        return $service->updateItem($params);
    }


    public function addItemByManage($params){
        $service = new ItemService();
        return $service->addItem($params);
    }

    public function deleteItemByManage($id){
        $service = new ItemService();
        return $service->deleteItem($id);
    }
}