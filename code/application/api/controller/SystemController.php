<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/11/19
 * Time: 16:03
 */

namespace app\api\controller;


use think\Cache;
use think\cache\driver\Memcached;

class SystemController
{
    public function clearAllCache(){
        Cache::clear();
    }
}