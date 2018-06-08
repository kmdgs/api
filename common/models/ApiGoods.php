<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/6
 * Time: 17:31
 */

namespace api\common\models;


use api\models\goods\Goods;
use common\models\goods\GoodsAttr;
use common\models\goods\GoodsAttribute;
use Yii;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class ApiGoods extends Goods
{

    /**
     * fields
     * 黄东 kmdgs@qq.com
     * 2018/6/6 17:43
     *
     * @return array
     */
    public function fields()
    {
        return [
            'goods_id',
            'goods_name'
        ];
    }


    /**
     * 表联合查询
     * extraFields
     * 黄东 kmdgs@qq.com
     * 2018/6/6 17:44
     *
     * @return array
     */
    public function extraFields()
    {
        return ['attr'];
    }

    /**
     * 搜索参数
     * getSearchModel
     * 黄东 kmdgs@qq.com
     * 2018/6/8 10:32
     *
     * @return mixed
     */
    public static function getSearchModel()
    {

        $model = (new DynamicModel(['goods_id', 'goods_name', 'cat_id']))
            ->addRule('goods_name', 'trim')
            ->addRule(['goods_name'], 'string')
            ->addRule(['goods_id', 'cat_id'], 'integer');
        return $model;
    }
}