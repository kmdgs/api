<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/8
 * Time: 10:18
 */

namespace api\traits;


use yii\helpers\ArrayHelper;

trait Params
{
    /**
     * getFilterParams
     * 黄东 kmdgs@qq.com
     * 2018/6/8 10:22
     *
     * @param $requestParams
     * @param $params
     * @return array
     */
    public static function getFilterParams($requestParams, $params)
    {
        $params_eq = ArrayHelper::getValue($params, 'eq');
        $parasm_like = ArrayHelper::getValue($params, 'like');
        $filter = [];
        //等于查询条件

        if (!empty($params_eq)) {
            foreach ($params_eq as $value) {
                if (!empty($requestParams[$value])) {
                    $filter[$value] = $requestParams[$value];
                }
            }
        }


        //模糊查询条件
        if (!empty($parasm_like)) {
            foreach ($parasm_like as $value) {
                if (!empty($requestParams[$value])) {
                    $filter[$value] = ['like' => $requestParams[$value]];
                }
            }
        }

        return $filter;
    }
}