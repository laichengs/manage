<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/9/7
 * Time: 9:58
 */

namespace app\api\model;


class ComboList extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function title(){
        return $this->belongsTo('Combo', 'combo_id', 'id');
    }

    public function combo(){
        return $this->belongsTo('Combo', 'combo_id', 'id');
    }
}