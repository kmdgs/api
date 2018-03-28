<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 17:18
 */

namespace api\common\models;


use common\models\content\content\Article;

class ApiArticle extends Article
{
    public function fields()
    {
        return [
            'id' ,
            'title',
            'abstract',
            'content',
            'catid' ,
            'scatid' ,
            'addtime' ,
            'keywords',
            'titles',
            'discription',
            'hits' ,
            'picurl',
            'aorder' ,
            'sources',
            'flowers' ,
            'userid',
            'download',
            'favorite',
            'comment' ,
            'share' ,
            'ischeck' ,
            'tags' ,
            'top' ,
            'support',
            'against',
            'flower_person' ,
            'star' ,
            'report' ,
            'outurl',
            'author' ,
            'vid' ,
            'headline',
            'home_carousel',
            'column_carousel' ,
            'hot'  ,
            'subtitle' ,
            'isimport' ,
            'attachment',
            'siteid',
            'update_at',
            'reject',
        ];
    }


    /**
     * 根据传递参数生成查询过滤条件
     * @author 黄东 kmdgs@qq.com
     * @param $requestParams
     * @return array
     */
    public static function getFilterParams($requestParams)
    {
        $params_eq = ['id','catid'];
        $parasm_like=['title'];
        $filter = [];
        //等于查询条件
        foreach ($params_eq as $value) {
            if(!empty($requestParams[$value])){
                $filter[$value]=$requestParams[$value];
            }
        }
        //模糊查询条件
        foreach ($parasm_like as $value) {
            if(!empty($requestParams[$value])){
                $filter[$value]=['like'=>$requestParams[$value]];
            }
        }

        return $filter;
    }
}