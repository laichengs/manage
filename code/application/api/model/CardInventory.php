<?php
/**
 * Created by PhpStorm.
 * User: laicheng
 * Date: 2018/11/23
 * Time: 14:42
 */

namespace app\api\model;


class CardInventory extends BaseModel
{
    public function item(){
        return $this->belongsTo('Item', 'item_id', 'id');
    }

    public function card(){
        return $this->beLongsTo('CardList', 'card_id', 'id');
    }
}