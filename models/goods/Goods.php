<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/6
 * Time: 17:30
 */

namespace api\models\goods;


use yii\db\ActiveRecord;

class Goods extends ActiveRecord
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
        return '{{%goods}}';
    }


    /**
     * getAttr
     * 黄东 kmdgs@qq.com
     * 2018/6/6 17:43
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttr()
    {
        return $this->hasMany(GoodsAttr::class,
            ['goods_id' => 'goods_id']);
    }

}