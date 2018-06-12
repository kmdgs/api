<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 17:18
 */

namespace api\common\models;


use yii\base\DynamicModel;

class ApiArticle extends \api\models\Article
{

    public function rules()
    {
        return [
            [['title'], 'string']
        ];
    }



    /**
     * 根据传递参数生成查询过滤条件
     *
     * @author 黄东 kmdgs@qq.com
     * @param $requestParams
     * @return array
     */
    public static function getFilterParams($requestParams)
    {
        $params_eq = ['id', 'catid'];
        $parasm_like = ['title', 'abstract'];
        $filter = [];
        //等于查询条件
        foreach ($params_eq as $value) {
            if (!empty($requestParams[$value])) {
                $filter[$value] = $requestParams[$value];
            }
        }
        //模糊查询条件
        foreach ($parasm_like as $value) {
            if (!empty($requestParams[$value])) {
                $filter[$value] = ['like' => $requestParams[$value]];
            }
        }
        return $filter;
    }


    /**
     * 获取搜索模型
     * getSearchModel
     * 黄东 kmdgs@qq.com
     * 2018/6/7 9:37
     *
     * @throws \yii\base\InvalidConfigException
     * @return DynamicModel
     */
    public static function getSearchModel()
    {

        $model = (new DynamicModel(['id' , 'title' , 'catid', 'abstract']))
            ->addRule('title', 'trim')
            ->addRule(['abstract','title'], 'string')
            ->addRule(['id','catid'],'integer');
        return $model;
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
        return ['user', 'side', 'category'];
    }


}