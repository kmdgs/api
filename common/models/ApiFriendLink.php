<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/28 15:41
 */

namespace api\common\models;


use common\models\module\Friendlink;

class ApiFriendLink extends Friendlink
{
    /**
     * 根据传递参数生成查询过滤条件
     * @author 黄东 kmdgs@qq.com
     * @param $requestParams
     * @return array
     */
    public static function getFilterParams($requestParams)
    {
        $params_eq = ['type'];
        $filter = [];
        //等于查询条件
        foreach ($params_eq as $value) {
            if (!empty($requestParams[$value])) {
                $filter[$value] = $requestParams[$value];
            }
        }
        return $filter;
    }

}