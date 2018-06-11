<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/6
 * Time: 17:31
 */

namespace api\common\models\goods;




use api\models\goods\Order;
use yii\base\DynamicModel;
use yii\helpers\Url;
use yii\web\Link;

class ApiOrder extends Order
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
       /* $fields = parent::fields();
        // 删除一些包含敏感信息的字段
        unset($fields['deleted'],$fields['is_distribut'], $fields['order_prom_id'], $fields['order_prom_amount']);*/
       $fields=['order_id','order_sn'];
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
        return ['goods'];
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

        $model = (new DynamicModel(['order_sn', 'cat_id']))
            ->addRule(['order_sn'], 'string')
            ->addRule(['order_status', 'shipping_status'], 'integer');
        return $model;
    }
}