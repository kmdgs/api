<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 17:18
 */

namespace api\common\models\module;


use yii\base\DynamicModel;

class ApiPosterSpace extends \api\models\module\PosterSpace
{

    public function fields()
    {
        return [
            'spaceid',
            'siteid',
            'name',
            'type',
            'path',
            'width',
            'height',
            'setting',
            'description',
            'items',
            'disabled',
            'example',
            'rows',
        ];
    }


    /**
     * 获取管理内容
     * extraFields
     * 黄东 kmdgs@qq.com
     * 2018/6/6 16:29
     *
     * @return array
     */
    public function extraFields()
    {
        return ['poster'];
    }


}