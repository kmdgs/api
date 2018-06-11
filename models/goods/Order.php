<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/6
 * Time: 17:30
 */

namespace api\models\goods;


use common\models\goods\order\OrderGoods;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{


    /**
     * tableName
     * 黄东 kmdgs@qq.com
     * 2018/6/6 17:31
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%order}}';
    }


    /**
     * getAttr
     * 黄东 kmdgs@qq.com
     * 2018/6/6 17:43
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(OrderGoods::class,
            ['order_id' => 'order_id']);
    }

}