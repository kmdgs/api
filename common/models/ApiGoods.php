<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/6
 * Time: 17:31
 */

namespace api\common\models;


use api\models\goods\Goods;

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
}