<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/6
 * Time: 17:31
 */

namespace api\common\models\goods;


use api\models\goods\Goods;
use yii\base\DynamicModel;
use yii\helpers\Url;
use yii\web\Link;

class ApiGoods extends Goods
{

    /**
     * 显示可用的字段
     * fields
     * 黄东 kmdgs@qq.com
     * 2018/6/6 17:43
     *
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();

        // 删除一些包含敏感信息的字段
        unset($fields['commission'],$fields['spu'], $fields['sku'], $fields['template_id']);

        return $fields;
    }


    /**
     * getLinks
     * 黄东 kmdgs@qq.com
     * 2018/6/8 14:08
     *
     * @return array
     */
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user/view', 'id' => 1], true),
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