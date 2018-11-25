<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/10/22
 * Time: 15:40
 */

namespace app\api\behavior;


class CORS
{
    public function appInit(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: token, Origin, X-Requested-With, Content-Type, Accept');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        if(request()->isOptions()){
            exit();
        }
    }
}