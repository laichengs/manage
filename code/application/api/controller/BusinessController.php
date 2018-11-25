<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/11/8
 * Time: 16:01
 */

namespace app\api\controller;


use app\api\model\Business;
use app\api\model\BusinessItem;
use app\api\model\City;
use app\api\model\Image;

class BusinessController
{

    public function getBusinessByCity($id){
        if(cache('business'.$id)){
            return unserialize(cache('business'.$id));
        }else{
            $model = new Business();
            $result = $model->with(['img'])->where('city_id', '=', $id)->select();
            cache('business'.$id, serialize($result));
            return $result;
        }
    }

    public function getBusinessListByManage(){
        $model = new Business();
        $data = [];
        $data['total'] = $model->count();
        $data['rows'] = $model->with(['city', 'img'])->select();
        return  $data;
    }

    public function getCityListByManage(){
        $model = new City();
        return $model->select();
    }

    public function addBusinessByManage($params){
        $model = new Business();
        $img = new Image();
        $img->save(['url'=> $params['img']]);
        $img_id = $img->id;
        $data = [
            'city_id' => $params['city'],
            'name' => $params['name'],
            'thumb_img_id' => $img_id,
            'order' => $params['order'],
        ];
        if($model->save($data)){
            return json('ok', 200);
        }else{
            return json('error', 404);
        }

    }

    public function deleteBusinessByManage($id){
        $model = new Business();
        $model->where('id', '=', $id)->delete();
        return true;
    }

    public function updateBusinessByManage($params){
        $model = new Business();
        $img = new Image();
        $img->where('id', '=', $params['img']['id'])->update(['url'=> $params['img']['url']]);
        $data = [
            'name' => $params['name'],
            'order' => $params['order'],
            'city_id' => $params['city']
        ];
        $model->where('id', '=', $params['id'])->update($data);
        return true;
    }

    public function getOneBusinessByManage($id){
        $model = new Business();
        $result = $model->with(['img'])->where('id', '=', $id)->find();
        return $result;
    }


    public function getBusinessItemsById($id){
        $model = new BusinessItem();
        $result = $model->with(['img'])->where('business_id', '=', $id)->select();
        return $result;
    }

    public function getBusinessItemsByManage($id){
        $data = [];
        $model = new BusinessItem();
        $data['total'] = $model->where('business_id', '=', $id)->count();
        $data['rows'] = $model->where('business_id', '=', $id)->with(['img', 'business.city'])->select();
        return $data;
    }

    public function getOneBusinessItemByManage($id){
        $model = new BusinessItem();
        $result = $model->where('id', '=', $id)->with(['img', 'business.city'])->find();
        return $result;
    }

    public function updateBusinessItemByManage($params){
        try{
            $model = new BusinessItem();
            $image = new Image();
            $image->where('id', '=', $params['img']['id'])->update(['url'=>$params['img']['url']]);
            $map = [
                'describe' => $params['describe'],
                'business_id' => $params['business'],
                'address' => $params['address'],
                'latitude' => $params['latitude'],
                'longitude' => $params['longitude'],
                'phone' => $params['phone'],
                'recommend' => $params['recommend'],
                'tag' => $params['tag']
            ];
            $model->where('id', '=', $params['id'])->update($map);
            return true;
        }catch(Exception $e){
            return false;
        }

    }

    public function addBusinessItemByManage($params){
        try{
            $image = new Image();
            $image->save(['url'=>$params['img']]);
            $img_id = $image->id;
            $model = new BusinessItem();
            $data = [
                'describe' => $params['describe'],
                'business_id' => $params['business'],
                'address' => $params['address'],
                'latitude' => $params['latitude'],
                'longitude' => $params['longitude'],
                'phone' => $params['phone'],
                'recommend' => $params['recommend'],
                'tag' => $params['tag'],
                'img_id' => $img_id
            ];
            if($model->save($data)){
                return true;
            };

        }catch(Exception $e){
            return false;
        }
    }

    public function deleteBusinessItemByManage($id){
        try{
            $model = new BusinessItem();
            if($model->where('id', '=', $id)->delete()){
                return true;
            };
        }catch(Exception $e){
            return false;
        }
    }

}