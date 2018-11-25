<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/29
 * Time: 17:10
 */

namespace app\api\controller;


use app\api\model\Image;
use app\api\model\Package;
use app\api\model\PackageItem;
use app\api\validate\IDValidate;
use app\Exception\DataException;

class PackageController
{
    public function getPackage($id){
        if(cache('package'.$id)){
            return unserialize(cache('package'.$id));
        }else{
            (new IDValidate())->goCheck();
            $model = new Package();
            $result = $model->with('item.img')->where('id', '=', $id)->find();
            if(!$result){
                throw new DataException();
            }
            cache('package'.$id, serialize($result));
            return $result;
        }

    }

    //


    /*后台获取所有packageItem*/
    public function getPackageItemsByManage($start, $number){
        $package = new PackageItem();
        $data['total'] = $package->count();
        $data['rows'] = $package->with(['img', 'package', 'item'])->limit($start, $number)->select();
        return $data;
    }
    /*后台获取所有package*/
    public function getPackagesByManage(){
        $package = new Package();
        return $package->select();
    }

    /*增加packageItem*/
    public function addPackageItemByManage($params){
        $package = new PackageItem();
        $data = [
            'img_id' => getImageID($params['img']),
            'item_id' => $params['item_id'],
            'package_id' => $params['package_id'],
            'title' => $params['title'],
            'describe' => $params['describe']
        ];
        $package->save($data);
        return true;
    }

    /*后台获取一个packageItem*/
    public function getOnePackageItemByManage($id){
        $package = new PackageItem();
        return $package->with(['img'])->where('id', '=', $id)->find();
    }
    /*修改packageItem*/
    public function updatePackageItemByManage($params){
        $map = [
            'describe' => $params['describe'],
            'title' => $params['title'],
            'item_id' => $params['item_id'],
            'package_id' => $params['package_id']
        ];
        $package = new PackageItem();
        $package->where('id', '=', $params['id'])->update($map);
        $image = new Image();
        $image->where('id', '=', $params['img']['id'])->update(['url'=> $params['img']['url']]);
        return true;
    }

    /*删除packageitem*/
    public function deletePackageItemByManage($id){
        $package = new PackageItem();
        $package->where('id', '=', $id)->delete();
    }
}