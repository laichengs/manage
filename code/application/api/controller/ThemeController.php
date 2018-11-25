<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/29
 * Time: 8:44
 */

namespace app\api\controller;


use app\api\model\Image;
use app\api\model\Theme;
use app\api\model\ThemeItem;
use app\api\validate\IDValidate;
use app\Exception\DataException;
use think\Cache;

class ThemeController
{
    public function getTheme($id){
        (new IDValidate())->goCheck();
        if(Cache('theme'.$id)){
           return unserialize(Cache('theme'.$id));
        }else{
            $model = new Theme();
            $result =  $model->with([
                'item' => function($query){
                    $query->with(['img'])->order('order', 'asc');
                }
            ])->where('id', '=', $id)->find();
            if(!$result){
                throw new DataException();
            }
            Cache('theme'.$id, serialize($result));
            return $result;
        }
    }

    /*后台获取主题数据*/
    public function getThemeItemsByManage($start, $number){
        $theme = new ThemeItem();
        $data['total'] = $theme->count();
        $data['rows'] = $theme->with(['img', 'item', 'theme', 'combo'])->select();
        return $data;
    }
    /*后台获取主题分类*/
    public function getThemesByManage(){
        $theme = new Theme();
        return $theme->select();
    }
    /*增加主题单个栏目*/
    public function addThemeItemByManage($params){
        $theme = new ThemeItem();
        $data = [
            'img_id' => getImageID($params['img']),
            'item_id' => $params['item_id'],
            'theme_id' => $params['theme_id'],
            'combo_id' => $params['combo_id'],
            'type' => $params['type'],
            'title' => $params['title'],
            'describe' => $params['describe']
        ];
        $theme->save($data);
        $id = $theme->id;
        $theme->where('id', '=', $id)->update(['order' => $id]);
        return true;
    }

    /*后台单个主题排序*/
    public function changeThemeSort($sort, $id){
        $theme = new ThemeItem();
        $theme->where('id', '=', $id)->update(['order'=>$sort]);
    }

    /*获取单个主题数据*/
    public function getOneThemeItemByManage($id){
        $theme = new ThemeItem();
        $result = $theme->with('img')->where('id', '=', $id)->find();
        return $result;
    }

    /*修改主题数据*/
    public function updateThemeItemByManage($params){
        $theme = new ThemeItem();
        $image = new Image();
        $image->where('id', '=', $params['img']['id'])->update(['url'=> $params['img']['url']]);
        $map = [
            'title' => $params['title'],
            'describe' => $params['describe'],
            'item_id' => $params['item_id'],
            'combo_id' => $params['combo_id'],
            'theme_id' => $params['theme_id'],
            'order' => $params['order'],
            'type' => $params['type']
        ];
        $theme->where('id', '=', $params['id'])->update($map);
        return true;
    }
    /*删除主题*/
    public function deleteThemeItemByManage($id){
        $theme = new ThemeItem();
        $theme->where('id', '=', $id)->delete();
        return true;
    }
}