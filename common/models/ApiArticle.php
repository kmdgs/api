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
            'id',
            'title',
            'abstract',
            'catid',
            'addtime',
            'hits',
            'picurl',
            'sources',
            'author',
            'update_at'
        ];
    }
}