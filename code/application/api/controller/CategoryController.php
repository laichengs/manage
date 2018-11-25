<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/28
 * Time: 17:44
 */

namespace app\api\controller;


use app\api\model\Category;
use app\Exception\CategoryException;

class CategoryController
{
    public function getCategory(){
        $category = new Category();
        $result = $category::with('img')->order('order','asc')->select();
        if(!$result){
            throw new CategoryException();
        }
        return $result;
    }
}