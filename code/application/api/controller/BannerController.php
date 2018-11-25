<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 11:25
 */

namespace app\api\controller;


use app\api\model\Banner;
use app\api\model\BannerItem;
use app\api\model\Image;
use app\api\validate\IDValidate;
use app\Exception\DataException;
use think\Cache;
use think\Db;
use think\db\exception\DataNotFoundException;

class BannerController
{
    public function getIndexBanner($id){
        if(cache('banner'.$id)){
            return unserialize(cache('banner'.$id));
        }else{
            $validate = new IDValidate();
            $validate->goCheck();
            $model = new Banner();
            $result = $model->where('id', '=', $id)->with('item.img')->find();
            if(!$result){
                throw new DataException();
            }
            cache('banner'.$id, serialize($result));
            return $result;
        }
    }

    public function getBannerItemsByManage(){
        $banner = new BannerItem();
        $data['total'] = $banner->count();
        $data['rows'] = $banner->with(['img', 'item', 'banner'])->select();
        return $data;
    }

    public function getBannersByManage(){
        $banner = new Banner();
        return $banner->select();
    }
    /*增加banner*/
    public function addBannerByManage($params){
        $image = new Image();
        $image->save(['url'=>$params['img']]);
        $img_id = $image->id;
        $map = [
            'img_id' => $img_id,
            'item_id' => $params['item_id'],
            'banner_id' => $params['banner_id']
        ];
        $banner = new BannerItem();
        if($banner->save($map)){
            return true;
        }else{
            return false;
        }
    }
    /*获取一个bannerItem*/
    public function getOneBannerItemByManage($id){
        $result = BannerItem::with(['img'])->where('id', '=', $id)->find();
        return $result;
    }
    /*修改banner*/
    public function updateBannerItemByManage($params){
        Db::startTrans();
        try{
            /*修改图片img*/
            $image = new Image();
            $image->where('id', '=', $params['img']['id'])->update(['url' => $params['img']['url']]);
            /*修改banner数据*/
            $map = [
                'item_id' => $params['item_id'],
                'banner_id' => $params['banner_id']
            ];
            $banner = new BannerItem();
            $banner->where('id', '=', $params['id'])->update($map);
            Db::commit();
            return true;
        }catch(Exception $e){
            return false;
        }

    }
    /*删除banner*/
    public function deleteBannerItemByManage($id){
        $banner = new BannerItem();
        $banner->where('id', '=', $id)->delete();
    }
}