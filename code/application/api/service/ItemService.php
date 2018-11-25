<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/17
 * Time: 14:24
 */

namespace app\api\service;


use app\api\model\Image;
use app\api\model\Item;
use app\api\model\ItemImage;
use think\Db;
use think\Exception;

class ItemService
{
    private $id;
    private $delete_ids;
    public function addItem($params){
        Db::startTrans();
        try{
            /*增加主数据并返回Item_id*/
            $this->addItemData($params);
            /*增加商品详情并返回ID*/
            $this->addDetailImage($params);
            Db::commit();
            return true;
        }catch(Exception $e){
            Db::rollback();
            return false;
        }
    }

    public function updateItem($params){
        Db::startTrans();
        try{
            $this->updateItemData($params);
            $this->updateDetailImg($params);
            Db::commit();
            return true;
        }catch(Exception $e){
            Db::rollback();
            return false;
        }
    }

    public function deleteItem($id){
        $this->deleteItemData($id);
        $this->deleteItemImage($id);
        $this->deleteDetail($id);
        return $this->delete_ids;
    }

    private function deleteItemImage($id){
        $item = new ItemImage();
        $result = $item->where('item_id', '=', $id)->select();
        foreach($result as $value){
            $this->delete_ids .= $value['img_id'].',';
        }
        $item->where('item_id', '=', $id)->delete();
    }

    private function deleteItemData($id){
        $result = Item::get($id);
        Item::where('id', '=', $id)->delete();
        $this->delete_ids = $result['title_img_id'].','.$result['main_img_id'].','.$result['thumb_img_id'].',';
    }

    private function deleteDetail(){
        $this->delete_ids = substr($this->delete_ids, 0, -1);
        $image = new Image();
        $image->where('id', 'in', $this->delete_ids)->delete();
    }

    private function updateItemData($params){
        $map = [
            'name' => $params['name'],
            'is_show_index' => $params['is_show_index'],
            'price' => $params['price'],
            'vip_price' => $params['vip_price'],
            'start_price' => $params['start_price']
        ];
        Item::where('id', '=', $params['id'])->update($map);
    }

    private function updateDetailImg($params){
        foreach($params['detail'] as $value){
            if(!array_key_exists('url', $value)){
                $url = null;
            }else{
                $url = $value['url'];
            }
            Image::where('id', '=', $value['id'])->update(['url'=> $url]);
        }
    }

    private function addItemData($params){
        $map = [
            'name' => $params['name'],
            'price' => $params['price'],
            'vip_price' => $params['vip_price'],
            'start_price' => $params['start_price'],
            'is_show_index' => $params['is_show_index'],
            'title_img_id' => $this->addImage($params['title']),
            'main_img_id' => $this->addImage($params['main']),
            'thumb_img_id' => $this->addImage($params['thumb'])
        ];
        $item = new Item();
        $item->save($map);
        $this->id = $item->id;
        $item->where('id', '=', $this->id)->update(['sort' => $this->id]);
    }

    private function addDetailImage($params){
        $details = $params['detail'];
        $item_image = new ItemImage();
        foreach($details as $value){
            $map = [
                'img_id' => $this->addImage($value['url']),
                'order' => $value['order'],
                'item_id' => $this->id
            ];
            $item_image->insert($map);
        }
    }

    private function addImage($url){
        $image = new Image();
        $image->save(['url' => $url]);
        return $image->id;
    }
}