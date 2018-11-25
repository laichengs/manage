<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/8/30
 * Time: 16:09
 */

namespace app\api\controller;


use app\api\model\OrderTimeList;
use app\api\model\OrderTimeRecord;
use app\api\model\User;

class TimeController
{
    public function getTime($id, $item_id){
        $now = date('Y-m-d H:i:s');
        $time = [];
        $lists = OrderTimeList::where(['city_id'=>$id, 'item_id'=>$item_id])->select();
        $record = new OrderTimeRecord();
        for($i=0; $i<7;$i++){
            $temp = [];
            $temp['year'] = date('Y-m-d', strtotime('+'.$i.'days',strtotime($now)));
            $temp['date'] = date('m月d日', strtotime('+'.$i.'days',strtotime($now)));
            //$weekDay = date('w', strtotime('+'.$i.'days',strtotime($now)));
            $timeTemp = [];
             foreach($lists as $key=>$value){
                 $tem = [];
                 $tem['time'] = $value->time;
                 $condition = [
                     'order_data' => $temp['year'],
                     'order_time' => $value->time,
                     'item_id' => $item_id,
                     'city_id' => $id
                 ];
                 $count = $record->where($condition)->count();
                 if($count < $value->stock){
                     $tem['stock'] = true;
                 }else{
                     $tem['stock'] = false;
                 }
                 array_push($timeTemp, $tem);
             }
            $temp['timestr'] = $timeTemp;
            $temp['weekday'] = $this->getWeekDay(strtotime('+'.$i.'days',strtotime($now)));
            array_push($time, $temp);
        }
        return $time;
    }

    private function getWeekDay($date){
        $weekArr = ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'];
        $weekDay = date('w', $date);
        return $weekArr[$weekDay];
    }
}