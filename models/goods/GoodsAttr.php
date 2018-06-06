<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/6
 * Time: 17:38
 */

namespace api\models\goods;


use yii\db\ActiveRecord;

class GoodsAttr extends ActiveRecord
{
    /**
     * tableName
     * 黄东 kmdgs@qq.com
     * 2018/6/6 17:42
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%goods_attr}}';
    }
}